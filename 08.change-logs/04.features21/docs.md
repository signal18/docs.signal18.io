---
title: 2.1 Features
taxonomy:
    category: docs
---

### 2.1 Features

* CORE: Dynamic config cluster store default config in $datadir/cluster.d
* CORE: IPV6 support [IPV6host]:port
* CORE: Schema change monitoring
* CORE: Use of MariaDB SHUTDOWN [WAIT FOR ALL SLAVES]
* CORE: Use of MariaDB mysqldump system=ALL

* CORE MONITORING: Multi Tenant Cluster Auth
* CORE MONITORING: SMTP can use Auth credentials
* CORE MONITORING: Slack alerting
* CORE MONITORING: Alerts preserved when flapping connections state on unstable network, reducing false positive alert
* CORE MONITORING: Support for MySQL 8.0 and MariaDB 10.5  

* CORE MAINTENANCE: Job scheduler rolling restart,
* CORE MAINTENANCE: Job scheduler rolling reprovision
* CORE MAINTENANCE: Job scheduler rolling optimize
* CORE MAINTENANCE: Job scheduler logical backup
* CORE MAINTENANCE: Job scheduler physical backup
* CORE MAINTENANCE: Job scheduler remote ssh job execution
* CORE MAINTENANCE: Tracking config change for rolling restart and rolling upgrade
* CORE MAINTENANCE: Streaming db error log on schedule SST external script
* CORE MAINTENANCE: Capture slow queries via db table, output in local log, log rotation
* CORE MAINTENANCE: slow queries digest  
* CORE MAINTENANCE: Capture loop for database infos to log (processlist,slave status, status, innodb status) trigger by alert
* CORE MAINTENANCE: Failover Switchover store state
* CORE MAINTENANCE: Failover Switchover capture SQL Logs produce by replication-manager
* CORE MAINTENANCE: Cluster configurator generation of tar.gz

* CORE BACKUP: Streaming xtrabackup mariabackup xbstream to replication-manager
* CORE BACKUP: Streaming db slow query on schedule SST external script  
* CORE BACKUP: Streaming backups for reseeding new node on schedule SST external script
* CORE BACKUP: Streaming backups to restic for achiving
* CORE BACKUP: Capture master binary logs and archive
* CORE BACKUP: mydumper integration
* CORE BACKUP: expose mysqldump parameter in configuration

* CORE SHARDING: Backend schema discover, pushdown spider table to proxy assuming universal table for duplicate names
* CORE SHARDING: Backend schema discover, pushdown spider table to proxy assuming hash of PK
* CORE SHARDING: Resharding table via API call
* CORE SHARDING: Table move cluster via API call

* CORE REPLICATION: Delayed replication
* CORE REPLICATION: MYSQL GTID errant transactions detection on  non ignored slave
* CORE REPLICATION: Checksum tables
* CORE REPLICATION: Multi source aggregation slaves

* CORE ROUTING: MyProxy internal proxying in go based on the Vitess parser and Siddon proxy
* CORE ROUTING: HaProxy Runtime API  

* HTTP/API: Database Process
* HTTP/API: Database slow Queries
* HTTP/API: Database Query Digest PFS/SLOW
* HTTP/API: Database Query Explain
* HTTP/API: Database Metadata Locks
* HTTP/API: Database Variables
* HTTP/API: Database Status Delta
* HTTP/API: Database Queries Response Time
* HTTP/API: Prometheus URI (done)
* HTTP/API: Cancel Rolling Restart
* HTTP/API: Need rolling restart or reprov on config change
* HTTP/API: Download Configurations

* PRO CORE REPLICATION: Rejoin via ZFS snapback to last snapshot for prefered master when binlog ahead (done)
* PRO CORE: OpenSVC agent 1.9 using overlay network (Weave, ipip tunneling)
* PRO CORE: OpenSVC agent 1.9 direct cluster API
* PRO CORE: Kubernetes (In progress)
* PRO CORE: Orchestrator onpremise  start server via ssh systemctl start
* PRO CORE: Orchestrator onpremise  provision server via ssh bootstrap configuration  
* PRO CORE: Track cluster state for reprovisioning, start, stop  based on configuration change and rolling actions
