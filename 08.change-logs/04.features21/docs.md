---
title: 2.1 Features
taxonomy:
    category: docs
---

### 2.1 Features

* CORE: Dynamic add cluster store default config in $datadir/cluster.d (done)
* CORE: MyProxy internal proxying in go based on the Vitess parser and Siddon proxy (done)
* CORE: Internal backup scheduler logical & physical (done)
* CORE: Streaming xtrabackup xbstream to replication-manager (done)
* CORE: Streaming backups for reseeding new node
* CORE: Archive backups based on restic, stored via local directory or Amazon first   
* PRO: Rejoin via ZFS snapback to last snapshot for prefered master  when binlog ahead   
