---
title: "PostgreSQL Topologies"
taxonomy:
    category: docs
---

Since 3.1.43, PostgreSQL servers are monitored as a cluster like MariaDB and MySQL servers, with their own switchover, failover and rejoin path: nothing is borrowed from the MariaDB statements. A PostgreSQL cluster is declared by one of three topology flags; the servers are the cluster's databases (on Cloud18 they are deployed from the PostgreSQL templates and sized by `prov-db-*`, never as apps).

## 4.4.14.1 The three topologies

| Topology | Flag | What it is |
| -------- | ---- | ---------- |
| Active-passive | `replication-active-passive` | One instance, no replication; the orchestrator restarts or moves it (failover placement, DRBD data volume on OpenSVC). See [Active-Passive Topology](/architecture/topologies/active-passive). |
| WAL streaming | `replication-master-slave-pg-stream` | A primary and physical standbys seeded by `pg_basebackup`, following the WAL. The role of a standby is armed by the jobs sidecar and applied at start, on any orchestrator that can restart the server. |
| Logical replication | `replication-master-slave-pg-logical` | A publisher followed by subscribers (publication / subscription, pglogical for DDL when `replication-pg-logical-ddl` is on). Subscribers are never in recovery: they are full read-write servers that only receive the published changes. |

The replicated members of a WAL streaming or logical cluster each live on one agent with their own data volume on the cluster's data pool (`prov-db-volume-data`): they never move with a failover, replication-manager promotes another member instead. Only the active-passive instance moves, on its DRBD volume.

## 4.4.14.2 Switchover, failover and rejoin

Measured on the Cloud18 preprod cluster with traffic on, every step through the API, health confirmed after each:

| Step | WAL streaming | Logical replication |
| ---- | ------------- | ------------------- |
| Switchover | 26–31 s: the primary is restarted as a standby of the promoted one (PostgreSQL cannot demote a running primary) | 2–3 s: the publisher is frozen with `default_transaction_read_only`, the subscriber promoted, the old publisher subscribes |
| Failover | 8 s: the most advanced standby is promoted, the others repointed | 8 s: the subscriber drops its subscription and publishes |
| Rejoin of the former primary | by `pg_rewind` (data kept), full copy when it cannot | re-subscribes without copy; writes taken after a failover are reported, not reconciled |
| What moves the routes | HAProxy leader and readers, ProxySQL writer and reader hostgroups, as for MariaDB | same |

## 4.4.14.3 What is available on PostgreSQL compared with MariaDB

✓ tested, ◐ partial, ✗ not available.

| Area | MariaDB | PostgreSQL |
| ---- | ------- | ---------- |
| Topology discovery, lag, replica state, health alerts | ✓ | ✓ |
| Processlist, variables, tables and sizes | ✓ | ✓ (processlist from `pg_stat_activity`, a mapped subset of variables) |
| Slow log, query digest, performance schema | ✓ | ✗ |
| Transaction log archive (binlog copy / WAL archive), PITR | ✓ | ◐ WAL archive shipped to replication-manager with `backup-binlogs`; PITR restore planned; no scan/flashback (no binary log) |
| Switchover, failover, rejoin | ✓ | ✓ WAL streaming and logical |
| Semi-sync, GTID, delayed and multi-source replicas | ✓ | ✗ (no equivalent) |
| Logical and physical backups with progress | mysqldump, mydumper, mariabackup, xtrabackup | `pg_dumpall`, `pg_basebackup` |
| Restore and reseed from a backup | ✓ | ✓ physical restore (`pg_basebackup` tar, then standby of the primary), logical restore of the primary; logical subscribers re-copied at the snapshot of a new slot |
| Scheduled optimize and analyze | ✓ | ✓ `vacuumdb --analyze` |
| HAProxy, traffic marker, write heartbeat | ✓ | ✓ |
| ProxySQL | ✓ | ✓ ProxySQL 3: `pgsql_servers`, `pgsql_users`, `pgsql_query_rules`, read/write split, verified through two switchovers |
| MaxScale, Spider | ✓ | ✗ |
| Provisioned and sized by the plan (Cloud18) | ✓ database path | ✓ database path: deployed from the PostgreSQL template, sized by `prov-db-*` (the app plan only initialises them), rolling restart and definition refresh tested |
| Resource usage graphs (cgroup, disk, network) | ✓ | ✓ |
| Internal metrics graphs (engine status series, workload, replication) | ✓ | ✓ workload, WAL and vacuum sections from `pg_stat_*`, the same Graphs page |
| Wait metrics graphed next to consumption (quota throttling, CPU, IO and memory pressure; semi-synchronous acknowledgment wait) | ✓ | ✓ the cgroup waits, the same sensor; no semi-sync on PostgreSQL |
| Sysbench through the proxy | ✓ | ✓ |
| Users and grants audit, security score | ✓ | ✗ |
| Database and user auto-created for linked apps | ✓ | ✓ |

## 4.4.14.4 Configuration

##### `replication-master-slave-pg-stream` (3.1)

| Item | Value |
| ---- | ----- |
| Description | The cluster's servers are PostgreSQL, a primary and WAL streaming standbys |
| Type | boolean |
| Default Value | false |

##### `replication-master-slave-pg-logical` (3.1)

| Item | Value |
| ---- | ----- |
| Description | The cluster's servers are PostgreSQL, a publisher and logical subscribers |
| Type | boolean |
| Default Value | false |

##### `replication-pg-logical-ddl` (3.1)

| Item | Value |
| ---- | ----- |
| Description | Replicate DDL on a logical cluster through pglogical |
| Type | boolean |
| Default Value | true |

The database credentials, proxies, backups and the Graphs page are configured as for MariaDB; see [Databases](/architecture/configuration-guide/databases) and [Apps](/provisioning/apps) for the Cloud18 templates (`postgres`, `postgres-standby`, `postgres-peer`).
