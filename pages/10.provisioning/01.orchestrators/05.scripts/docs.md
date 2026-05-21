---
title: Scripts
taxonomy:
    category: docs
---

## 10.2.5.1 Overview

replication-manager uses scripts at various stages of the provisioning lifecycle and for event-driven hooks. Scripts are either:

- **Built-in provisioning scripts** — served via HTTP, selected automatically by orchestrator type and DB tags (`rpm`, `package`, default=debian)
- **Provisioning hook scripts** — user-defined, called at specific lifecycle events (bootstrap, start, stop, cleanup)
- **On-premise SSH scripts** — custom overrides for the built-in provisioning scripts

All provisioning scripts receive environment variables from `GetSshEnv()` — see [Environment Variables](../../02.configurator/04.distributions#10353-environment-variables-for-ssh-scripts) for the full list.

---

## 10.2.5.2 Built-In Database Provisioning Scripts

Served at `/static/configurator/onpremise/...`, selected by tags:

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

---

## 10.2.5.3 Built-In Proxy Provisioning Scripts

| Script | Tags | Purpose |
|---|---|---|
| `repository/debian/proxysql/bootstrap` | *(default)* | ProxySQL first-time provisioning (apt) |
| `repository/debian/proxysql/start` | *(default)* | ProxySQL restart (apt) |
| `repository/redhat/proxysql/bootstrap` | `rpm` | ProxySQL first-time provisioning (yum) |
| `repository/redhat/proxysql/start` | `rpm` | ProxySQL restart (yum) |
| `package/linux/proxysql/bootstrap` | `package` | ProxySQL first-time provisioning (tarball) |
| `package/linux/proxysql/start` | `package` | ProxySQL restart (tarball) |

---

## 10.2.5.4 Container and Maintenance Scripts

| Script | Purpose |
|---|---|
| `opensvc/bootstrap` | OpenSVC init container bootstrap |
| `init/dbjobs_new` | Maintenance job dispatcher (embedded in config tarball). Handles backups, log shipping, config refresh, script upgrades. |
| `bin/replication-manager-cli` | CLI binary, served to nodes, auto-upgraded on start |

---

## 10.2.5.5 Provisioning Lifecycle Hook Scripts

Called during provisioning lifecycle events. These are **user-defined** scripts — repman calls them after the corresponding orchestrator action completes.

All database hooks receive the same arguments:

| Arg | Value |
|---|---|
| `$1` | Server hostname |
| `$2` | Server port |
| `$3` | Database user |
| `$4` | Database password |
| `$5` | Cluster name |

| Config key | When called |
|---|---|
| `prov-db-bootstrap-script` | After database provisioning completes |
| `prov-db-start-script` | After database start |
| `prov-db-stop-script` | After database stop |
| `prov-db-cleanup-script` | After database unprovision |

All proxy hooks receive the same arguments:

| Arg | Value |
|---|---|
| `$1` | Proxy hostname |
| `$2` | Proxy port |
| `$3` | Proxy user |
| `$4` | Proxy password |
| `$5` | Cluster name |

| Config key | When called |
|---|---|
| `prov-proxy-bootstrap-script` | After proxy provisioning completes |
| `prov-proxy-start-script` | After proxy start |
| `prov-proxy-stop-script` | After proxy stop |
| `prov-proxy-cleanup-script` | After proxy unprovision |

---

## 10.2.5.6 On-Premise SSH Script Overrides

These override the built-in provisioning scripts with custom paths. When set, the configurator uses the custom script instead of the tag-selected default.

##### `onpremise-ssh-start-db-script`

| | |
|---|---|
| Description | Custom database start script (replaces built-in start) |
| Type | String |
| Default | `""` (auto-select by tags) |

##### `onpremise-ssh-upgrade-db-script`

| | |
|---|---|
| Description | Custom database upgrade script (replaces built-in upgrade) |
| Type | String |
| Default | `""` (auto-select by tags) |

##### `onpremise-ssh-db-job-script`

| | |
|---|---|
| Description | Custom maintenance jobs script (replaces built-in dbjobs) |
| Type | String |
| Default | `""` (auto-select) |

##### `onpremise-ssh-start-proxy-script`

| | |
|---|---|
| Description | Custom proxy start script |
| Type | String |
| Default | `""` (auto-select by tags) |

##### `onpremise-ssh-stop-proxy-script`

| | |
|---|---|
| Description | Custom proxy stop script |
| Type | String |
| Default | `""` (auto-select by tags) |

---

## 10.2.5.7 Failover and Replication Hook Scripts

##### `failover-pre-script`

Called before failover starts.

| Arg | Value |
|---|---|
| `$1` | Old master hostname |
| `$2` | New master hostname |
| `$3` | Old master port |
| `$4` | New master port |
| `$5` | Old master MaxScale server name |
| `$6` | New master MaxScale server name |
| `$7` | Failover type (`failover` or `switchover`) |

##### `failover-post-script`

Called after failover completes. Same arguments as `failover-pre-script`.

##### `autorejoin-script`

Called when a failed server rejoins the cluster.

| Arg | Value |
|---|---|
| `$1` | Rejoining server hostname |
| `$2` | Master hostname |
| `$3` | Rejoining server port |
| `$4` | Master port |

##### `replication-error-script`

Called on replication error (broken replication, excessive lag).

| Arg | Value |
|---|---|
| `$1` | Server URL (`host:port`) |
| `$2` | Previous server state |
| `$3` | Current server state |

##### `arbitration-failed-master-script`

Called when the arbitrator detects a failed master.

| Arg | Value |
|---|---|
| `$1` | Failed master hostname |
| `$2` | Failed master port |

---

## 10.2.5.8 Monitoring Hook Scripts

##### `monitoring-open-state-script`

Called when a warning or error state is opened.

| Arg | Value |
|---|---|
| `$1` | Cluster name |
| `$2` | Server URL |
| `$3` | Error key (e.g. `WARN0042`, `ERR00012`) |

##### `monitoring-close-state-script`

Called when a warning or error state is resolved. Same arguments as `monitoring-open-state-script`.

##### `db-servers-state-change-script`

Called when a database server changes state.

| Arg | Value |
|---|---|
| `$1` | Cluster name |
| `$2` | Server hostname |
| `$3` | Server port |
| `$4` | New state |
| `$5` | Old state |

##### `proxy-servers-change-state-script`

Called when a proxy server changes state.

| Arg | Value |
|---|---|
| `$1` | Cluster name |
| `$2` | Proxy hostname |
| `$3` | Proxy port |
| `$4` | New state |
| `$5` | Old state |
| `$6` | Master state |

##### `monitoring-schema-change-script` (3.1)

Called when a table schema change is detected during the monitoring loop. The column diff is piped to **stdin** in unified format.

| Arg | Value |
|---|---|
| `$1` | Cluster name |
| `$2` | Server URL |
| `$3` | Schema name |
| `$4` | Table name |
| `$5` | Change type: `new`, `altered`, or `dropped` |

**Stdin** receives a column diff:
```
--- mydb.users (before)
+++ mydb.users (after)
- email varchar(50) NOT NULL
+ email varchar(255) NOT NULL
+ phone varchar(20) DEFAULT NULL
```

| Change type | Diff content |
|---|---|
| `new` | All columns as `+` additions |
| `altered` | Before/after diff of changed, added, and dropped columns |
| `dropped` | All columns as `-` removals |

First-time cache population on startup does not trigger the script.

##### `monitoring-long-query-script`

Registered in config but **not currently called** in the codebase. Reserved for future use.

---

## 10.2.5.9 Alert and Backup Hook Scripts

##### `alert-script`

Called when an alert is triggered.

| Arg | Value |
|---|---|
| `$1` | Cluster name |
| `$2` | Host that triggered the alert |
| `$3` | Previous state |
| `$4` | Current state |

##### `backup-save-script`

Called before a backup starts.

| Arg | Value |
|---|---|
| `$1` | Server hostname |
| `$2` | Master hostname |
| `$3` | Server port |
| `$4` | Master port |
| `$5` | Database user |
| `$6` | Database password |
| `$7` | Cluster name |
| `$8` | Destination file path |

##### `backup-load-script`

Called before a restore starts. Same arguments as `backup-save-script` (without `$8` destination).

##### `backup-logical-post-script`

Called after a logical backup completes.

| Arg | Value |
|---|---|
| `$1` | Cluster name |
| `$2` | Server hostname |
| `$3` | Server port |
| `$4` | Backup file path |

##### `backup-physical-post-script`

Called after a physical backup completes. Same arguments as `backup-logical-post-script`.

##### `binlog-copy-script`

Called when a binlog is archived to backup storage.

| Arg | Value |
|---|---|
| `$1` | Cluster name |
| `$2` | Server hostname |
| `$3` | Server port |
| `$4` | SSH port |
| `$5` | Binary log directory |
| `$6` | Backup destination directory |
| `$7` | Binlog filename |

##### `binlog-rotation-script`

Called when a binlog is rotated on the server.

| Arg | Value |
|---|---|
| `$1` | Cluster name |
| `$2` | Server hostname |
| `$3` | Server port |
| `$4` | Current binary log file |
| `$5` | Previous binary log file |
| `$6` | Oldest binary log file |

---

## 10.2.5.10 Staging Scripts

##### `topology-staging-refresh-script`

Called when a staging cluster is refreshed from its parent. Receives no positional arguments — uses environment variables from `GetExecEnv()`.

##### `topology-staging-post-detach-script`

Called after a staging cluster detaches from its parent. Uses environment variables from `GetExecEnv()`.

| Arg | Value |
|---|---|
| `$1` | Cluster name |
| `$2` | Server hostname |
| `$3` | Server port |
| `$4` | New state |
| `$5` | Old state |
| `$6` | Database user |
| `$7` | Database password |

---

## 10.2.5.11 Cloud and DNS Scripts

| Config key | When called |
|---|---|
| `cloud18-domain-add-script` | DNS record creation for new instance |
| `cloud18-domain-drop-script` | DNS record removal on unprovision |
| `cloud18-sales-subscription-script` | New subscription event |
| `cloud18-sales-subscription-validate-script` | Subscription validation |
| `cloud18-sales-unsubscribe-script` | Unsubscription event |
