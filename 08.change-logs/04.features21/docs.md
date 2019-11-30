---
title: 2.1 Features
taxonomy:
    category: docs
---

### 2.1 Features

* CORE: Dynamic config cluster store default config in $datadir/cluster.d (done)
* CORE: MyProxy internal proxying in go based on the Vitess parser and Siddon proxy (done)
* CORE: Job scheduler (done)
* CORE: Streaming xtrabackup mariabackup xbstream to replication-manager (done)
* CORE: Streaming db error log on schedule SST external script (done)
* CORE: Streaming db slow query on schedule SST external script  (done)
* CORE: Streaming backups for reseeding new node on schedule SST external script (done)
* CORE: Capture slow queries via db table, output in local log
* CORE: maintain slow queries digest buffer (done)
* CORE: Schema Monitoring (done)
* CORE: Sharding Schema Monitoring based on same table name, assuming hash of PK (done)
* CORE: Sharding backend schema discover and auto pushdown to spider proxy (done)
* CORE: Sharding Resharding table via API Call (done)
* CORE: Sharding Table move cluster via API Call (done)
* CORE: Full Server state preserved on unstable network, reduce alert false positive (done)
* CORE: SMTP Auth (done)
* CORE: Multi Tenant Cluster Auth (done)
* CORE: Slack alerting (done)
* CORE: MYSQL GTID Errant transactions detection on  non ignored slave (done)
* CORE: Trigger capture database infos on alert  (done)
* HTTP/API: Database Process (done)
* HTTP/API: Database slow Queries (done)
* HTTP/API: Database Query Digest PFS/SLOW (done)
* HTTP/API: Database Query Explain (done)
* HTTP/API: Database Metadata Locks (done)
* HTTP/API: Database Variables (done)
* HTTP/API: Database Status Delta (done)
* HTTP/API: Database Queries Response Time (done)
* HTTP/API: Prometheus URI (done)
* PRO: Rejoin via ZFS snapback to last snapshot for prefered master when binlog ahead (done)
* PRO: OpenSVC agent 1.9 using overlay network (Weave, ipip tunneling ) (done)
* PRO: Kubernetes (In progress)
* CORE: Archive backups based on restic, stored via local directory or Amazon first (done)
* CORE: Failover Switchover state trace (done)
* CORE: Multi source aggregation slaves (in progress)
* CORE: Schema Checksum (done)
* CORE: Failover Switchover SQL Logs (done)
* CONFIG: Generation (done)
