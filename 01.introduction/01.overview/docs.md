---
title: Overview
taxonomy:
    category: docs
---
## Goals

**replication-manager** is an open source database cluster orchestrator that embeds best practices for configuration, deployment, HA operations, maintenance, monitoring, and troubleshooting for MySQL, MariaDB, and Percona. The design hides database clustering complexity while maintaining simplicity similar to Amazon RDS.

**replication-manager** is configurable using nix-style configuration, supports multi-tenancy, provides security through encryption and ACL, and offers API, command line, and web interfaces for database and proxy management.

**replication-manager** delivers multi-cluster sharding and routing solutions to address scalability and high availability challenges.

## History

**replication-manager** was initially written to close the gap between Galera Cluster and MySQL Master HA. While Galera Cluster addresses many clustering needs, it has limitations around performance and cluster-wide locking. **replication-manager** leverages newer MySQL and MariaDB features including Global Transaction ID, Semi-Synchronous replication, and binary log flashback to provide high availability and node switchover with minimal performance impact.

**replication-manager** evolved to manage full-stack database cluster deployments for testing and production. Clusters can be provisioned, tested, and unprovisioned across different topologies and product releases. The solution supports both existing deployments and architecture-as-a-service driven by external orchestrators.

Integration support includes:
- **OpenSVC**: Using collector API and direct cluster node VIP API
- **Kubernetes**: Cluster management and orchestration
- **SlapOS**: Open source hyperconverged and edge computing infrastructure where the API checks and performs actions on database infrastructure components      


## HA Workflow

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

## Traffic Routing

**replication-manager** is commonly used as an arbitrator with a routing layer that directs write database traffic to a single leader database node.

- [x] Layer 3: DNS routing updates discovery services like Consul or other API-based DNS to enable direct routing without proxying
- [x] Layer 4: HAProxy configuration generation or reconfiguration via API calls
- [x] Layer 4: HAProxy external checks via **replication-manager** API as a resource manager reporting cluster node states
- [x] Layer 4: External scripts to reuse existing routes like VIP failover, Puppet/Ansible scenarios, or ldirector reconfiguration
- [x] Layer 7: ProxySQL or MariaDB MaxScale receive instant notifications of topology changes without relying on their own monitoring
- [x] Layer 7: MariaDB Spider layer for database traffic splitting or table sharding across different database clusters

Multiple strategies can be combined depending on feature maturity, security, or performance requirements.
