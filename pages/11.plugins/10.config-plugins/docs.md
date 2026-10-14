---
title: Config Plugins
taxonomy:
    category: docs
---

## 11.8.1 Config Plugins

> **Available since:** replication-manager **v3.1.44**

Config plugins report facts about a database server's configuration that need an operator's action. Like the other plugins they run no query of their own: they read what replication-manager has already observed (job results, the server's error log, its version). Their findings go to the **Config** view of the dashboard (the config pill of the top bar), never to the HA view, and they never influence failover. A finding that has a fix carries a **Remediate** button that runs it through the API.

Config plugins run in the plugin evaluation loop, so `log-plugin` must be on (it is off by default):

```toml
log-plugin = true
```

| Plugin | Finding | Since | Reads |
|---|---|---|---|
| `config-system-tables` | ERR00116 | 3.1.44 | the server's logical backups, its error log |

---

## 11.8.2 config-system-tables

**Finding:** `ERR00116` (error)  
**Since:** 3.1.44 — MariaDB, MySQL and Percona Server

A server upgraded to a new release without running `mariadb-upgrade` (`mysql_upgrade` on MySQL before 8.0.16) keeps the system tables of its previous release. The server runs, but anything that reads those tables fails, first of all the logical backups and every reseed from a dump:

```
mariadb-dump: Couldn't execute 'SHOW FUNCTION STATUS WHERE Db = ...':
Column count of mysql.proc is wrong. Expected 22, found 21.
Created with MariaDB 110404, now running 130002. Please use mariadb-upgrade to fix this error (1558)
```

The plugin raises `ERR00116` on that server when:

- **the last logical backup of the server** (mysqldump or mydumper) failed with `Column count of mysql.<table> is wrong`, or
- **the server's error log since its last start** reports it, or `Incorrect definition of table mysql.<table>`.

The most recent observation decides, so the error closes on its own once the tables are fixed, whoever fixed them:

| Last observation | ERR00116 |
|---|---|
| a logical backup failed on a system table | open |
| a logical backup ended OK | closed |
| a system tables upgrade ended OK | closed |
| a logical backup failed for another reason | unchanged (proves nothing) |

Error log lines from before the server's last start are ignored: the start may have run the upgrade.

### Remediation

The error's **Remediate** button runs the server action **Upgrade System Tables**, also available in the server menu (DB Utils) and through the API:

```
POST /api/clusters/{clusterName}/servers/{serverName}/actions/upgrade-system-tables
```

The action queues the dbjob task `mariadbupgrade`: the server's jobs container, which runs the database image and so the upgrade tool of the server's own release, runs `mariadb-upgrade --upgrade-system-tables` (`mysql_upgrade` on older MySQL). Only the system tables are upgraded, user tables are not checked, and nothing is written to the binary log: each server of a cluster is upgraded by its own action. Its output is in the server's job log, and the job ends in error if the tool fails.

The action is refused for a server that is down, for MySQL 8.0.16 and later (they upgrade their system tables at start) and for PostgreSQL. It needs the `db-maintenance` grant.

The official MariaDB image can also upgrade at start: replication-manager provisions MariaDB servers with `MARIADB_AUTO_UPGRADE=1`, so a server restarted after a release change upgrades its system tables itself. A server provisioned before that variable existed gets it with **Update OpenSVC template**, then a restart.
