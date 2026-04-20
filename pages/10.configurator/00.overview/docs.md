---
title: Overview
taxonomy:
    category: docs
---

## Database Configurator

The **Database Configurator** is replication-manager's built-in rules engine for generating, delivering, and tracking database and proxy configuration files. It translates a set of cluster **tags** and **hardware resource settings** into ready-to-use `my.cnf` files, directory structures, and bootstrap scripts — packaged as a `config.tar.gz` archive that an init container or SSH script can unpack directly into the service file system.

The configurator runs on every cluster. It is the sole source of truth for the configuration files deployed to each monitored database server.

---

## How It Works

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

## Config Discovery

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

## Config Delivery

### Container mode (pro / OpenSVC / Kubernetes)

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

### SSH mode (osc / onpremise)

replication-manager regenerates the archive locally and can push it to database hosts over SSH. The config is unpacked into the server's data directory, and the `dbjobs_new` script is placed in `{datadir}/init/init/dbjobs_new`.

### Secure download

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

## Regenerating Config

To force immediate regeneration of all config archives for a cluster (without waiting for the next monitoring tick):

```
POST /api/clusters/{clusterName}/actions/regenerate-configs
```

replication-manager re-evaluates tags and resource settings, writes new `init/` trees, and rebuilds all `config.tar.gz` archives. Servers pick up the updated config on their next init-container launch or SSH apply cycle.

---

## Filesystem Layout

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
