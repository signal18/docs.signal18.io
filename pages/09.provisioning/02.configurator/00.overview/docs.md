---
title: Overview
taxonomy:
    category: docs
---

## 9.3.1.1 Software Configurator

The **Software Configurator** is replication-manager's built-in rules engine for generating, delivering, and tracking database and proxy configuration files. It translates a set of cluster **tags** and **hardware resource settings** into ready-to-use `my.cnf` files, directory structures, and bootstrap scripts — packaged as a `config.tar.gz` archive that an init container or SSH script can unpack directly into the service file system.

The configurator runs on every cluster. It is the sole source of truth for the configuration files deployed to each monitored database server.

---

## 9.3.1.2 How It Works

```
prov-db-tags  +  prov-db-memory / disk / iops / cores
        │
        ▼
Compliance module  (embedded opensvc/moduleset_mariadb.svc.mrm.db.json)
        │
        │  evaluates tag filters → selects matching rulesets
        │  substitutes %%ENV:…%% template variables
        │
        ▼
  <datadir>/<cluster>/<host_port>/init/
        ├── etc/mysql/
        │       ├── conf.d/        tag-generated .cnf fragments (symlinked)
        │       ├── rc.d/          ordered symlinks to active fragments
        │       └── custom.d/      user overlay (01_preserved, 02_delta, 03_agreed)
        ├── init/
        │       └── dbjobs_new     maintenance job script
        └── data/
                └── .system/       InnoDB undo/redo/tmp directory skeleton

config.tar.gz   ←  packaged from init/ tree, served via HTTP API
```

Each time tags or resource settings change, replication-manager regenerates all config archives. The init container (container mode) or SSH provisioner (osc mode) unpacks the archive into the live service volume on the next apply or rolling restart.

---

## 9.3.1.3 Config Discovery

When a cluster is first connected — or when you request it explicitly — replication-manager reads the **live database variables and installed plugins** and automatically derives the matching tags. This means you can point replication-manager at an existing, hand-tuned MariaDB server and have it reconstruct the tag set that describes that configuration.

Discovery maps variables to tags in the following ways:

| Variable | Tag derived |
|---|---|
| `INNODB_DOUBLEWRITE=OFF` | `nodoublewrite` |
| `INNODB_FLUSH_LOG_AT_TRX_COMMIT≠1` | `nodurable` |
| `INNODB_FLUSH_METHOD≠O_DIRECT` | `noodirect` |
| `LOG_BIN_COMPRESS=ON` | `compressbinlog` |
| `INNODB_DEFRAGMENT=ON` | `autodefrag` |
| `INNODB_COMPRESSION_DEFAULT=ON` | `compresstable` |
| `QUERY_CACHE_SIZE=0` | `noquerycache` |
| `SLOW_QUERY_LOG=ON` | `slow` |
| `GENERAL_LOG=ON` | `general` |
| `PERFORMANCE_SCHEMA=ON` | `pfs` |
| `LOG_OUTPUT=TABLE` | `logtotable` |
| `HAVE_SSL=YES` | `ssl` |
| `READ_ONLY=ON` | `readonly` |
| `SKIP_NAME_RESOLVE=OFF` | `resolvdns` |
| `LOCAL_INFILE=ON` | `localinfile` |
| `LOG_BIN=OFF` | `nobinlog` |
| `LOG_SLAVE_UPDATES=OFF` | `nologslaveupdates` |
| `RPL_SEMI_SYNC_MASTER_ENABLED=ON` | `semisync` |
| `GTID_STRICT_MODE=ON` | `gtidstrict` |
| `TX_ISOLATION=READ-COMMITTED` | `readcommitted` |
| `LOWER_CASE_TABLE_NAMES=1` | `lowercasetable` |
| `USER_STAT_TABLES=PREFERABLY_FOR_QUERIES` | `eits` |
| `BINLOG_FORMAT=STATEMENT` | `statement` |
| `BINLOG_FORMAT=ROW` | `row` |
| `JOIN_CACHE_LEVEL=8` | `hashjoin` |
| `JOIN_CACHE_LEVEL=6` | `mrrjoin` |
| `JOIN_CACHE_LEVEL=2` | `nestedjoin` |
| `SQL_MODE=ORACLE` | `sqlmodeoracle` |
| `SQL_MODE=""` | `sqlmodeunstrict` |
| Plugin `BLACKHOLE` installed | `blackhole` |
| Plugin `QUERY_RESPONSE_TIME` | `userstats` |
| Plugin `SQL_ERROR_LOG` | `sqlerror` |
| Plugin `METADATA_LOCK_INFO` | `metadatalocks` |
| Plugin `SERVER_AUDIT` | `audit` |
| Plugin `CONNECT` | `connect` |
| Plugin `SPIDER` | `spider` |
| Plugin `MROONGA` | `mroonga` |
| Plugin `TOKUDB_CACHE_SIZE` variable present | `tokudb` |
| Plugin `ROCKSDB_BLOCK_CACHE_SIZE` variable present | `myrocks` |
| Plugin `S3_PAGECACHE_BUFFER_SIZE` variable present | `s3` |
| Plugin `CRACKLIB_PASSWORD_CHECK` | `pwdcheckcracklib` |
| Plugin `SIMPLE_PASSWORD_CHECK` | `pwdchecksimple` |
| wsrep plugin active | `wsrep` |

Discovery also reads memory values directly from the running server (`INNODB_BUFFER_POOL_SIZE`, `KEY_BUFFER_SIZE`, `ARIA_PAGECACHE_BUFFER_SIZE`, etc.) and sets `prov-db-memory` to the detected total — so the generated config targets the same memory footprint as the existing server.

---

## 9.3.1.4 Config Delivery

### 9.3.1.4.1 Container mode (pro / OpenSVC / Kubernetes)

The generated archive is served at:

```
GET /api/clusters/{clusterName}/servers/{host}/{port}/config
```

An **init container** that shares the network namespace with the database container fetches this URL at service startup and unpacks it:

```
# OpenSVC container spec
[container#0002]
type    = docker
image   = busybox
netns   = container#0001
command = sh -c 'wget -qO- http://{env.mrm_api_addr}/api/clusters/{env.mrm_cluster_name}/servers/{env.ip_pod01}/{env.port_pod01}/config | tar xzvf - -C /data'
```

```yaml
# Kubernetes init container
initContainers:
- name: install
  image: busybox
  command:
  - sh
  - -c
  - 'wget -qO- http://replication-manager:10001/api/clusters/my-cluster/servers/db1/3306/config | tar xzf - -C /data'
```

### 9.3.1.4.2 SSH mode (osc / onpremise)

replication-manager regenerates the archive locally and can push it to database hosts over SSH. The config is unpacked into the server's data directory, and the `dbjobs_new` script is placed in `{datadir}/init/init/dbjobs_new`.

### 9.3.1.4.3 Secure download

By default the config endpoint requires no credentials so init containers can bootstrap without pre-provisioned tokens. To require JWT authentication (protects embedded passwords):

```toml
api-credentials-secure-config = true
```

When enabled, the bootstrap script fetches a session token first using environment variables (`REPLICATION_MANAGER_USER`, `REPLICATION_MANAGER_PASSWORD`, `REPLICATION_MANAGER_URL`) and then requests the config archive with Bearer auth.

A pre-built bootstrap script that handles this flow is served at:

```
/static/configurator/opensvc/bootstrap
/static/configurator/onpremise/repository/debian/mariadb/bootstrap
/static/configurator/onpremise/repository/redhat/mariadb/bootstrap
```

---

## 9.3.1.5 Starting the Database

The configurator provides two distinct scripts for on-premise (SSH) operation — **bootstrap** and **start** — with different behavior around config fetching and data directory initialization.

### 9.3.1.5.1 Bootstrap vs Start

| Script | When to use | Config fetch | Data directory |
|---|---|---|---|
| `bootstrap` | First-time provisioning of a new node | Always downloads fresh config | **Wipes** `/var/lib/mysql`, copies `.system` skeleton |
| `start` | All subsequent restarts | Conditional (see below) | Copies `.system` with `cp -rpn` — **never overwrites existing files** |

Scripts are served by replication-manager at:

```
/static/configurator/onpremise/repository/debian/mariadb/bootstrap
/static/configurator/onpremise/repository/debian/mariadb/start
/static/configurator/onpremise/repository/redhat/mariadb/start
/static/configurator/onpremise/package/linux/mariadb/start
```

Both scripts receive all credentials and addressing via injected environment variables (`REPLICATION_MANAGER_URL`, `REPLICATION_MANAGER_USER`, `REPLICATION_MANAGER_PASSWORD`, `REPLICATION_MANAGER_CLUSTER_NAME`, `REPLICATION_MANAGER_HOST_NAME`, `REPLICATION_MANAGER_HOST_PORT`). See [Environment Variables](../../../06.maintenance/00.overview#615-environment-variables-injected-by-replication-manager) in the Maintenance chapter.

### 9.3.1.5.2 Conditional Config Fetch on Start

The `start` script does **not** unconditionally re-fetch the config archive. Before downloading anything it checks:

```
GET /api/clusters/{clusterName}/servers/{host}/{port}/need-config-fetch
```

- **200** → a new config is needed; the script downloads `config.tar.gz`, unpacks it, applies the `.cnf` files, and then starts the database
- **500** → no fetch needed; the script skips the download entirely and starts the database with the **existing config files already on disk**

This matters in normal restarts: if nothing changed on the replication-manager side since the last start, the server just starts immediately using its local config — no network round-trip to fetch the archive.

### 9.3.1.5.3 Controlling Config Fetch

The `need-config-fetch` response is controlled by a per-server cookie that replication-manager manages. The cookie state is driven by:

##### `prov-db-start-fetch-config`

| | |
|---|---|
| Description | When `true` (default), replication-manager clears the "no-fetch" cookie on each monitoring tick, so the next start will re-fetch config. Set to `false` to permanently suppress config fetching on start — the database will always start with whatever config is already on disk. |
| Type | Boolean |
| Default | `true` |

```toml
# Never re-fetch config on start; trust the existing files on disk
prov-db-start-fetch-config = false
```

Use `false` when you are managing config files externally (e.g., configuration management tools, manual tuning) and do not want replication-manager to overwrite them on restart.

### 9.3.1.5.4 Preserving an Existing my.cnf

When the `start` or `bootstrap` script copies new `.cnf` files from the archive, it applies a **non-destructive rule for `my.cnf`**:

- If `my.cnf` already exists and does **not** start with `# Generated by Signal18 replication-manager`, it is assumed to be a hand-written file. The script renames the new Signal18-generated file to `my.cnf.new` and keeps the existing `my.cnf` untouched.
- If `my.cnf` starts with `# Generated by Signal18 replication-manager`, it was placed by a previous run and is replaced normally.

To override this and force the Signal18 config to win regardless:

```bash
export REPLICATION_MANAGER_FORCE_CONFIG=true
```

### 9.3.1.5.5 The `.system` Directory

The archive always contains a `data/.system/` skeleton. This directory holds all files that must live inside the MySQL datadir but are **not** user data:

```
/var/lib/mysql/.system/
├── innodb/
│   ├── undo/          InnoDB undo tablespaces (innodb_undo_directory)
│   └── redo/          InnoDB redo log files (innodb_log_group_home_dir)
├── aria/              Aria engine transaction log (aria_log_dir_path)
├── tmp/               Temporary files (tmpdir)
├── repl/              Replication relay logs and position files
├── logs/
│   ├── error.log      MariaDB error log
│   ├── slow-query.log Slow query log
│   ├── server_audit.log  Audit plugin log
│   └── sql_errors.log SQL error log plugin
└── tokudb/            TokuDB data files
```

The generated `.cnf` fragments point InnoDB, Aria, and log variables at these paths using relative syntax (`./.system/...`). The configurator rewrites these to absolute paths depending on context:

- **Docker / container**: `./.system` → `/var/lib/mysql/.system`
- **SlapOS**: `./.system` → `{basedir}/var/lib/mysql/.system`
- **Galera (wsrep)**: system file paths are excluded from the archive entirely to avoid overwriting Galera's own state files

On **bootstrap**, the entire `/var/lib/mysql` is removed and the `.system` tree is copied in fresh — this is intentional for first-time provisioning.

On **start**, the `.system` tree is copied with `cp -rpn` which means **no file is ever overwritten**. InnoDB undo and redo logs, replication relay logs, and log files already present on disk are left completely intact. Only missing directories and files from the archive skeleton are created.

### 9.3.1.5.6 nosplitpath — Flat Datadir Layout

By default all `.system` sub-paths are used. If your environment cannot accommodate a `.system` hidden directory inside the datadir — for example because your backup tooling, snapshot strategy, or storage layout requires a flat datadir — add the `nosplitpath` tag:

```toml
prov-db-tags = "...,nosplitpath"
```

With `nosplitpath` set, all paths that normally point into `.system/` are moved to the root datadir or a dedicated location:

| Path | Default (without `nosplitpath`) | With `nosplitpath` |
|---|---|---|
| Error log | `{datadir}/.system/logs/error.log` | `{datadir}/error.log` |
| Slow query log | `{datadir}/.system/logs/slow-query.log` | `{datadir}/slow-query.log` |
| Audit log | `{datadir}/.system/logs/server_audit.log` | `{datadir}/server_audit.log` |
| SQL error log | `{datadir}/.system/logs/sql_errors.log` | `{datadir}/sql_errors.log` |
| Replication relay logs | `{datadir}/.system/repl/` | `{datadir}/` |
| Maintenance job dir | `{datadir}/.system/jobs/` | `/var/lib/replication-manager-jobs/` |

InnoDB undo and redo log directories follow the same pattern — the generated `.cnf` points them into `{datadir}` rather than `{datadir}/.system/innodb/undo` and `{datadir}/.system/innodb/redo`.

When the `nosplitpath` tag is added or removed at runtime, replication-manager detects that the live `innodb_undo_directory` value no longer matches the expected configuration and sets a **config path cookie** to trigger config regeneration and a scheduled restart — the same mechanism used for any other path variable change. No manual restart scheduling is needed.

---

## 9.3.1.6 Regenerating Config

To force immediate regeneration of all config archives for a cluster (without waiting for the next monitoring tick):

```
POST /api/clusters/{clusterName}/actions/regenerate-configs
```

replication-manager re-evaluates tags and resource settings, writes new `init/` trees, and rebuilds all `config.tar.gz` archives. Servers pick up the updated config on their next init-container launch or SSH apply cycle.

---

## 9.3.1.7 Filesystem Layout

Inside the replication-manager working directory:

```
{working-dir}/{cluster-name}/{host}_{port}/
├── config.tar.gz         ← packed archive served to init containers
├── init/
│   ├── etc/mysql/
│   │   ├── conf.d/       tag-generated .cnf fragments
│   │   ├── rc.d/         ordered symlinks (loaded by main my.cnf)
│   │   ├── custom.d/     user overlays (preserved, delta, agreed)
│   │   └── ssl/          TLS certificates
│   └── init/
│       └── dbjobs_new    maintenance job script
├── 01_preserved.cnf      server-specific locked variables (see Config Tracking)
├── 02_delta.cnf          calculated drift between expected and deployed
├── 03_agreed.cnf         manually accepted deviations
└── preserved_variables.cnf  cluster-wide preserved variables
```

`etc/mysql/rc.d` contains numbered symlinks that control load order. `etc/mysql/custom.d` is read last, so user overlays always win over tag-generated fragments. This is where the three-layer preserved/delta/agreed files land inside the container.

See [Config Tracking](../02.config-tracking) for a full explanation of `01_preserved.cnf`, `02_delta.cnf`, and `03_agreed.cnf`.

See [Tags](../01.tags) for the complete tag reference.

See [Configuration Guide](../03.configuration-guide) for all `prov-db-*` settings.

---

## 9.3.1.8 Script Reference

replication-manager uses scripts for provisioning, maintenance, and event hooks. Scripts are either served via HTTP (provisioning scripts) or configured via TOML keys (hooks). All provisioning scripts receive environment variables from `GetSshEnv()` — see [Environment Variables](../04.distributions#9353-environment-variables-for-ssh-scripts) for the full list.

### 9.3.1.8.1 On-Premise Database Provisioning Scripts

Served at `/static/configurator/onpremise/...`, selected by tags (`rpm`, `package`, default=debian):

| Script | Tags | Purpose | Custom override |
|---|---|---|---|
| `repository/debian/mariadb/bootstrap` | *(default)* | First-time provisioning: wipe datadir, install packages, init | — |
| `repository/debian/mariadb/start` | *(default)* | Restart: conditional config fetch, start service | `onpremise-ssh-start-db-script` |
| `repository/debian/mariadb/upgrade` | *(default)* | Version upgrade: update repo, pin version, install, mariadb-upgrade | `onpremise-ssh-upgrade-db-script` |
| `repository/redhat/mariadb/bootstrap` | `rpm` | First-time provisioning (yum/dnf) | — |
| `repository/redhat/mariadb/start` | `rpm` | Restart (yum/dnf) | `onpremise-ssh-start-db-script` |
| `repository/redhat/mariadb/upgrade` | `rpm` | Version upgrade (yum/dnf) | `onpremise-ssh-upgrade-db-script` |
| `package/linux/mariadb/bootstrap` | `package` | First-time provisioning (binary tarball) | — |
| `package/linux/mariadb/start` | `package` | Restart (binary tarball) | `onpremise-ssh-start-db-script` |
| `repository/debian/mysql/bootstrap` | *(MySQL)* | MySQL first-time provisioning (apt) | — |
| `repository/debian/mysql/start` | *(MySQL)* | MySQL restart (apt) | — |

### 9.3.1.8.2 Proxy Provisioning Scripts

| Script | Tags | Purpose |
|---|---|---|
| `repository/debian/proxysql/bootstrap` | *(default)* | ProxySQL first-time provisioning (apt) |
| `repository/debian/proxysql/start` | *(default)* | ProxySQL restart (apt) |
| `repository/redhat/proxysql/bootstrap` | `rpm` | ProxySQL first-time provisioning (yum) |
| `repository/redhat/proxysql/start` | `rpm` | ProxySQL restart (yum) |
| `package/linux/proxysql/bootstrap` | `package` | ProxySQL first-time provisioning (tarball) |
| `package/linux/proxysql/start` | `package` | ProxySQL restart (tarball) |

Custom overrides: `onpremise-ssh-start-proxy-script`, `onpremise-ssh-stop-proxy-script`

### 9.3.1.8.3 Container Scripts

| Script | Purpose |
|---|---|
| `opensvc/bootstrap` | OpenSVC init container bootstrap |
| `init/dbjobs_new` | Maintenance job dispatcher (embedded in config tarball) |
| `bin/replication-manager-cli` | CLI binary, served to nodes, auto-upgraded on start |

### 9.3.1.8.4 Provisioning Hook Scripts

Called during provisioning lifecycle events. Configure via TOML or GUI:

| Config key | When called | Default script |
|---|---|---|
| `prov-db-bootstrap-script` | After database provisioning | — |
| `prov-db-start-script` | After database start | — |
| `prov-db-stop-script` | After database stop | — |
| `prov-db-cleanup-script` | After database unprovision | — |
| `prov-proxy-bootstrap-script` | After proxy provisioning | — |
| `prov-proxy-start-script` | After proxy start | — |
| `prov-proxy-stop-script` | After proxy stop | — |
| `prov-proxy-cleanup-script` | After proxy unprovision | — |

### 9.3.1.8.5 Failover and Replication Hook Scripts

| Config key | When called | Default script |
|---|---|---|
| `failover-pre-script` | Before failover starts | — |
| `failover-post-script` | After failover completes | — |
| `autorejoin-script` | When a failed server rejoins | — |
| `replication-error-script` | On replication error (broken, lag) | — |
| `arbitration-failed-master-script` | Arbitrator detects failed master | — |

### 9.3.1.8.6 Monitoring Hook Scripts

| Config key | When called | Default script |
|---|---|---|
| `monitoring-schema-change-script` | Schema change detected | — |
| `monitoring-long-query-script` | Long query detected | — |
| `monitoring-open-state-script` | Warning/error state opened | `share/scripts/openstate.sh` |
| `monitoring-close-state-script` | Warning/error state resolved | `share/scripts/closestate.sh` |
| `db-servers-state-change-script` | Database server state changed | `share/scripts/databasechangestate.sh` |
| `proxy-servers-change-state-script` | Proxy server state changed | `share/scripts/proxychangestate.sh` |

### 9.3.1.8.7 Backup Hook Scripts

| Config key | When called | Default script |
|---|---|---|
| `alert-script` | Alert triggered | `share/scripts/alert.sh` |
| `backup-save-script` | Before backup starts | — |
| `backup-load-script` | Before restore starts | — |
| `backup-logical-post-script` | After logical backup completes | — |
| `backup-physical-post-script` | After physical backup completes | `share/scripts/post_backup.sh` |
| `binlog-copy-script` | Binlog archived to backup | `share/scripts/binlog_copy.sh` |
| `binlog-rotation-script` | Binlog rotated | `share/scripts/binlog_rotation.sh` |

### 9.3.1.8.8 Staging and Cloud Scripts

| Config key | When called | Default script |
|---|---|---|
| `topology-staging-refresh-script` | Staging cluster refresh | `share/scripts/staging_refresh.sh` |
| `topology-staging-post-detach-script` | After staging detach | `share/scripts/topologystagingpostdetach.sh` |
| `cloud18-domain-add-script` | DNS record for new instance | `share/scripts/prov_domain_add_script.sh` |
| `cloud18-domain-drop-script` | DNS record removal | `share/scripts/prov_domain_drop_script.sh` |
| `cloud18-sales-subscription-script` | New subscription | — |
| `cloud18-sales-subscription-validate-script` | Validate subscription | — |
| `cloud18-sales-unsubscribe-script` | Unsubscription | — |

### 9.3.1.8.9 On-Premise SSH Script Overrides

| Config key | Overrides | Purpose |
|---|---|---|
| `onpremise-ssh-start-db-script` | Default start script | Custom database start via SSH |
| `onpremise-ssh-upgrade-db-script` | Default upgrade script | Custom database upgrade via SSH |
| `onpremise-ssh-db-job-script` | Default dbjobs script | Custom maintenance jobs via SSH |
| `onpremise-ssh-start-proxy-script` | Default proxy start script | Custom proxy start via SSH |
| `onpremise-ssh-stop-proxy-script` | Default proxy stop script | Custom proxy stop via SSH |

See [Distributions & Rolling Upgrade](../04.distributions) for environment variables, version pinning, and upgrade flow details.

---

## 9.3.1.8 Script Reference

All scripts are served by replication-manager via HTTP and selected automatically based on orchestrator type and DB tags (`rpm`, `package`). Custom scripts can override any default via config settings.

### 9.3.1.8.1 On-Premise Provisioning Scripts

Served at `/static/configurator/onpremise/...`, selected by the configurator based on tags:

| Script | Tag selection | Purpose | Custom override |
|---|---|---|---|
| `repository/debian/mariadb/bootstrap` | Default | First-time provisioning: wipe datadir, install, init | — |
| `repository/debian/mariadb/start` | Default | Restart: conditional config fetch, start | `onpremise-ssh-start-db-script` |
| `repository/debian/mariadb/upgrade` | Default | Version upgrade: update repo, pin version, install, mariadb-upgrade | `onpremise-ssh-upgrade-db-script` |
| `repository/redhat/mariadb/bootstrap` | `rpm` | First-time provisioning (yum) | — |
| `repository/redhat/mariadb/start` | `rpm` | Restart (yum) | `onpremise-ssh-start-db-script` |
| `repository/redhat/mariadb/upgrade` | `rpm` | Version upgrade (yum/dnf) | `onpremise-ssh-upgrade-db-script` |
| `package/linux/mariadb/bootstrap` | `package` | First-time provisioning (tarball) | — |
| `package/linux/mariadb/start` | `package` | Restart (tarball) | `onpremise-ssh-start-db-script` |

### 9.3.1.8.2 Proxy Provisioning Scripts

| Script | Tag selection | Purpose |
|---|---|---|
| `repository/debian/proxysql/bootstrap` | Default | ProxySQL first-time provisioning (apt) |
| `repository/debian/proxysql/start` | Default | ProxySQL restart (apt) |
| `repository/redhat/proxysql/bootstrap` | `rpm` | ProxySQL first-time provisioning (yum) |
| `repository/redhat/proxysql/start` | `rpm` | ProxySQL restart (yum) |
| `package/linux/proxysql/bootstrap` | `package` | ProxySQL first-time provisioning (tarball) |
| `package/linux/proxysql/start` | `package` | ProxySQL restart (tarball) |

### 9.3.1.8.3 Container Bootstrap

| Script | Purpose |
|---|---|
| `opensvc/bootstrap` | OpenSVC init container bootstrap |

### 9.3.1.8.4 Maintenance Scripts

Located in `share/scripts/`, these handle event-driven operations:

| Script | Config key | Purpose |
|---|---|---|
| `dbjobs_new.sh` | *(embedded in config tarball)* | Maintenance job dispatcher: backups, log shipping, config refresh, jobs upgrade |
| `dbjobs_launcher.sh` | — | Container entrypoint that runs dbjobs in a loop |
| `dbjobs_launcher_with_sigterm.sh` | — | Same as above with SIGTERM handling for graceful shutdown |
| `alert.sh` | `alert-script` | External alerting hook (called on state changes) |
| `databasechangestate.sh` | `post-db-state-change-script` | Hook: database state changed (failover, switchover) |
| `proxychangestate.sh` | `post-proxy-state-change-script` | Hook: proxy state changed |
| `openstate.sh` | `post-state-open-script` | Hook: warning/error state opened |
| `closestate.sh` | `post-state-close-script` | Hook: warning/error state resolved |
| `post_backup.sh` | `post-backup-script` | Hook: backup completed |
| `binlog_copy.sh` | `binlog-copy-script` | Hook: binlog archived to backup |
| `binlog_copy_fail.sh` | `binlog-copy-script-fail` | Hook: binlog copy failed |
| `binlog_rotation.sh` | `binlog-rotation-script` | Hook: binlog rotated |
| `staging_refresh.sh` | — | Refresh staging cluster from parent |
| `topologystagingpostdetach.sh` | — | Post-detach hook for staging topology |
| `prov_domain_add_script.sh` | `cloud18-domain-add-script` | DNS: add domain record for new instance |
| `prov_domain_drop_script.sh` | `cloud18-domain-drop-script` | DNS: remove domain record |
| `aws_post_failover.sh` | — | AWS: update Route53 after failover |

### 9.3.1.8.5 Embedded Binary

| File | Purpose |
|---|---|
| `bin/replication-manager-cli` | CLI client, served to on-premise nodes and auto-upgraded on start |

See [Distributions & Rolling Upgrade](../04.distributions) for environment variables and version pinning.
