---
title: 2.1 Features
taxonomy:
    category: docs
---

### 2.1 Features

* CORE: Dynamic config cluster store default config in $datadir/cluster.d (done)
* CORE: MyProxy internal proxying in go based on the Vitess parser and Siddon proxy (done)
* CORE: Internal job scheduler logical & physical (done)
* CORE: Streaming xtrabackup xbstream to replication-manager (done)
* CORE: Streaming db error log on schedule SST external script (done)
* CORE: Streaming db slow query on schedule SST external script log (done)
* CORE: Streaming backups for reseeding new node on schedule SST external script (done)
* CORE: Capture slow queries via table, output in local log and maintain digest (done)
* CORE: Schema Monitoring (done)
* CORE: Sharding Schema Monitoring based on same table name, assuming hash of PK (done)
* CORE: Sharding backend schema discover and auto pushdown to spider proxy (done)
* CORE: PK Table Resharding based on all available clusters via API Call  (done)
* CORE: Table Resharding via API Call (done)
* CORE: Full Server state preserved on unstable network, reduce alert false positive (done)
* CORE: SMTP Auth (done)
* HTTP/API: Digest Query from slow query log or performance schema (done)
* HTTP/API: Processlist (done)
* HTTP/API: slow Queries (done)
* HTTP/API: Query Digest PFS/SLOW (done)
* HTTP/API: Query Explain (done)
* HTTP/API: Metadata Locks (done)
* HTTP/API: Variables (done)
* HTTP/API: Status Delta (done)
* HTTP/API: Queries Response Time (done)
* CORE: Multi Tenant Cluster (done)
* CORE: Slack alerting (done)
* CORE: Failover Switchover SQL Logs
* PRO: Rejoin via ZFS snapback to last snapshot for prefered master when binlog ahead (done)
* PRO: OpenSVC agent 1.9 using overlay network (Weave, ipip tunneling ) (done)

* CORE: Archive backups based on restic, stored via local directory or Amazon first (todo)
* CORE: Failover Switchover state trace (in progress)
* CORE: Multi source aggregation slaves (in progress)
* CORE: Schema Checksum (todo)
* CORE: Trigger Capture on alert (todo)
* CONFIG: Generation (todo)
