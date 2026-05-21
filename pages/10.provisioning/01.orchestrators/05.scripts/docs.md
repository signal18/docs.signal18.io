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

Called during provisioning lifecycle events. These are **user-defined** scripts — repman calls them after the corresponding orchestrator action completes. Configure via TOML:

##### `prov-db-bootstrap-script`

| | |
|---|---|
| Description | Called after database provisioning completes |
| Type | String |
| Default | `""` |

##### `prov-db-start-script`

| | |
|---|---|
| Description | Called after database start |
| Type | String |
| Default | `""` |

##### `prov-db-stop-script`

| | |
|---|---|
| Description | Called after database stop |
| Type | String |
| Default | `""` |

##### `prov-db-cleanup-script`

| | |
|---|---|
| Description | Called after database unprovision |
| Type | String |
| Default | `""` |

##### `prov-proxy-bootstrap-script`

| | |
|---|---|
| Description | Called after proxy provisioning completes |
| Type | String |
| Default | `""` |

##### `prov-proxy-start-script`

| | |
|---|---|
| Description | Called after proxy start |
| Type | String |
| Default | `""` |

##### `prov-proxy-stop-script`

| | |
|---|---|
| Description | Called after proxy stop |
| Type | String |
| Default | `""` |

##### `prov-proxy-cleanup-script`

| | |
|---|---|
| Description | Called after proxy unprovision |
| Type | String |
| Default | `""` |

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

| Config key | When called |
|---|---|
| `failover-pre-script` | Before failover starts |
| `failover-post-script` | After failover completes |
| `autorejoin-script` | When a failed server rejoins the cluster |
| `replication-error-script` | On replication error (broken replication, excessive lag) |
| `arbitration-failed-master-script` | Arbitrator detects a failed master |

---

## 10.2.5.8 Monitoring Hook Scripts

| Config key | When called | Default script |
|---|---|---|
| `monitoring-schema-change-script` | Schema change detected (DDL) | — |
| `monitoring-long-query-script` | Long-running query detected | — |
| `monitoring-open-state-script` | Warning or error state opened | `share/scripts/openstate.sh` |
| `monitoring-close-state-script` | Warning or error state resolved | `share/scripts/closestate.sh` |
| `db-servers-state-change-script` | Database server state changed (failover, switchover, crash) | `share/scripts/databasechangestate.sh` |
| `proxy-servers-change-state-script` | Proxy server state changed | `share/scripts/proxychangestate.sh` |

---

## 10.2.5.9 Backup Hook Scripts

| Config key | When called | Default script |
|---|---|---|
| `alert-script` | Alert triggered (any channel) | `share/scripts/alert.sh` |
| `backup-save-script` | Before backup starts | — |
| `backup-load-script` | Before restore starts | — |
| `backup-logical-post-script` | After logical backup completes | — |
| `backup-physical-post-script` | After physical backup completes | `share/scripts/post_backup.sh` |
| `binlog-copy-script` | Binlog archived to backup storage | `share/scripts/binlog_copy.sh` |
| `binlog-rotation-script` | Binlog rotated on server | `share/scripts/binlog_rotation.sh` |

---

## 10.2.5.10 Staging Scripts

| Config key | When called | Default script |
|---|---|---|
| `topology-staging-refresh-script` | Staging cluster refresh from parent | `share/scripts/staging_refresh.sh` |
| `topology-staging-post-detach-script` | After staging cluster detaches from parent | `share/scripts/topologystagingpostdetach.sh` |

---

## 10.2.5.11 Cloud and DNS Scripts

| Config key | When called | Default script |
|---|---|---|
| `cloud18-domain-add-script` | DNS record creation for new instance | `share/scripts/prov_domain_add_script.sh` |
| `cloud18-domain-drop-script` | DNS record removal on unprovision | `share/scripts/prov_domain_drop_script.sh` |
| `cloud18-sales-subscription-script` | New subscription event | — |
| `cloud18-sales-subscription-validate-script` | Subscription validation | — |
| `cloud18-sales-unsubscribe-script` | Unsubscription event | — |
