---
title: Overview
taxonomy:
    category: docs
---
## 1.1.1 Goals

**replication-manager** is an open source database cluster orchestrator that embeds best practices for configuration, deployment, HA operations, maintenance, monitoring, and troubleshooting for MySQL, MariaDB, and Percona. The design hides database clustering complexity while maintaining simplicity similar to Amazon RDS.

**replication-manager** is configurable using nix-style configuration, supports multi-tenancy, provides security through encryption and ACL, and offers API, command line, and web interfaces for database and proxy management.

**replication-manager** delivers multi-cluster sharding and routing solutions to address scalability and high availability challenges.

## 1.1.2 History

**replication-manager** was initially written to close the gap between Galera Cluster and MySQL Master HA. While Galera Cluster addresses many clustering needs, it has limitations around performance and cluster-wide locking. **replication-manager** leverages newer MySQL and MariaDB features including Global Transaction ID, Semi-Synchronous replication, and binary log flashback to provide high availability and node switchover with minimal performance impact.

**replication-manager** evolved to manage full-stack database cluster deployments for testing and production. Clusters can be provisioned, tested, and unprovisioned across different topologies and product releases. The solution supports both existing deployments and architecture-as-a-service driven by external orchestrators.

Integration support includes:
- **OpenSVC**: Using collector API and direct cluster node VIP API
- **Kubernetes**: Cluster management and orchestration
- **SlapOS**: Open source hyperconverged and edge computing infrastructure where the API checks and performs actions on database infrastructure components      


## 1.1.3 HA Workflow

To perform switchover on leader replicas while preserving data consistency, **replication-manager** uses an improved workflow similar to common MySQL failover tools such as MHA:

- [x] Verify replication settings
- [x] Check replication on the slaves (configurable)
- [x] Check for long running queries and transactions on master
- [x] Elect a new master (defaults to most up-to-date replica, or a designated candidate)
- [x] Call optional script to remove IP address from master
- [x] Notify proxies about the state change
- [x] Reject writes on master by calling FLUSH TABLES WITH READ LOCK
- [x] Reject writes on master by setting READ_ONLY flag
- [x] Reject writes on master by decreasing MAX_CONNECTIONS
- [x] Kill pending connections on master if any remain
- [x] Watch for all slaves to catch up to the current GTID position
- [x] Promote the candidate slave to be the new master
- [x] Call optional script to assign IP address to new master
- [x] Notify proxies about the new state
- [x] Switch other slaves and old master to replicate from the new master
- [x] Set slave read-only

## 1.1.4 Traffic Routing

**replication-manager** is commonly used as an arbitrator with a routing layer that directs write database traffic to a single leader database node.

- [x] Layer 3: DNS routing updates discovery services like Consul or other API-based DNS to enable direct routing without proxying
- [x] Layer 4: HAProxy configuration generation or reconfiguration via API calls
- [x] Layer 4: HAProxy external checks via **replication-manager** API as a resource manager reporting cluster node states
- [x] Layer 4: External scripts to reuse existing routes like VIP failover, Puppet/Ansible scenarios, or ldirector reconfiguration
- [x] Layer 7: ProxySQL or MariaDB MaxScale receive instant notifications of topology changes without relying on their own monitoring
- [x] Layer 7: MariaDB Spider layer for database traffic splitting or table sharding across different database clusters

Multiple strategies can be combined depending on feature maturity, security, or performance requirements.

## 1.1.5 Software Configurator

**replication-manager** includes a built-in **Software Configurator** — a rules engine that generates, delivers, and tracks database and proxy configuration files.

Database fine-tuning is complex. The configurator enforces best practices out of the box while leaving full freedom for the user to override any setting. Each configuration fragment is documented through the plugin system, which provides inline documentation helpers so users can understand what each option does, why it matters, and when to change it. The goals are:

- **Best-practice defaults without constraints** — translate a set of cluster tags (`innodb`, `semisync`, `threadpool`, `pfs`, …) and hardware resource settings (memory, disk, IOPS, cores) into ready-to-use `.cnf` files with correct buffer pool sizes, log paths, and engine-specific settings. Users can override any generated value through the preserved/delta/agreed layering system without fighting the tool
- **Config discovery** — point replication-manager at an existing database server and have it reverse-engineer the tag set and memory profile that matches the running configuration, so nothing is lost when onboarding existing deployments
- **Drift detection** — continuously compare the live database variables against the expected configuration and surface differences as preserved, delta, or agreed deviations (see [Config Tracking](/provisioning/configurator/config-tracking))
- **Documentation helpers** — the plugin system annotates each configuration fragment with documentation, explaining the purpose and impact of each setting so DBAs can make informed decisions rather than guessing
- **Secure delivery** — package config files as a `config.tar.gz` archive served over the REST API, consumed by init containers (OpenSVC, Kubernetes) or SSH provisioners with optional JWT authentication
- **Rolling config updates** — when tags or resources change, regenerate all config archives and coordinate delivery through rolling restarts without manual intervention

The configurator is the sole source of truth for configuration files deployed to each monitored database server. See the [Software Configurator](/provisioning/configurator/overview) section for a full explanation of tags, config delivery, the `.system` directory layout, and config tracking.

## 1.1.6 Scheduled Maintenance

**replication-manager** embeds a cluster scheduler that automates recurring database maintenance tasks.

Our team has maintained heavily loaded databases where maintenance alone consumed more than two days a week of multiple DBAs. The goal is to deliver maintenance-free database systems by automating the best practices around backups, defragmentation, and database growth control — so DBAs can focus on architecture and optimization rather than routine upkeep.

- **Automated backups** — schedule logical backups (mysqldump, mydumper) and physical backups (mariabackup, xtrabackup) with configurable retention and optional Restic archiving to S3-compatible storage
- **Defragmentation** — schedule `OPTIMIZE TABLE` across all schemas to reclaim wasted space from InnoDB fragmentation, and `ANALYZE TABLE` to keep optimizer statistics accurate on large, write-heavy workloads
- **Database growth control** — monitor table sizes, row counts, and data growth over time; detect schema drift between replicas with checksums; alert on unexpected growth before disk pressure becomes critical
- **Log management** — collect error logs, slow query logs, and audit logs from each database server; rotate log tables on schedule
- **Rolling operations** — perform rolling restarts and rolling re-provisioning one node at a time, maintaining cluster availability throughout
- **Flexible execution** — tasks run either logically (SQL from replication-manager) or remotely (via the dbjobs script on each database host), depending on whether they need local filesystem access
- **Two dispatch modes** — SQL mode (traditional job table polling) or API mode (REST-based task discovery that works even when the database is down, enabling crash log delivery)

Each task is individually enabled and given its own cron expression. The scheduler is disabled by default. See the [Maintenance](/maintenance/overview) section for a full explanation of the job system, task types, dbjobs script delivery, and dispatch modes.
