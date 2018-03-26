---
title: 2.1 Features
taxonomy:
    category: docs
---

### 2.1 Features

* CORE: Dynamic add cluster store default config in $datadir/cluster.d (done)
* CORE: MyProxy internal proxying in go based on the Vitess parser and Siddon proxy (done)
* CORE: Internal job scheduler logical & physical (done)
* CORE: Streaming xtrabackup xbstream to replication-manager (done)
* CORE: Streaming db error log (done)
* CORE: Streaming db slow query log (done)
* CORE: Streaming backups for reseeding new node (done)
* CORE: Archive backups based on restic, stored via local directory or Amazon first
* CORE: Multi Tenant Cluster via HTTPS (in progress)    
* CORE: Slack alerting
* CORE: Failover Switchover SQL Logs
* CORE: Failover Switchover state trace (in progress)
* CORE: Multi source aggregation slaves (in progress)
* PRO: Rejoin via ZFS snapback to last snapshot for prefered master when binlog ahead   (done)
* PRO: OpenSCV agent 1.9 using overlay network  
