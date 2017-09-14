---
title: Overview
---

## History

**replication-manager** was initially written with the goal of closing the gap between Galera Cluster and MySQL Master HA.
While Galera Cluster is a really good product, it has some shortcomings such as performance and cluster-wide locking.
**replication-manager** is a tailored solution on top of existing technologies in MySQL and MariaDB such as Global Transaction ID, Semi-Sync Replication, Binary log Flashback, that aims to provide High Availability and Node Switchover without compromising the performance by a too large factor.
It also aims to be configurable and user-friendly by offering an HTML interface and interaction with familiar database proxying software.

## Workflow

To perform switchover, preserving data consistency, replication-manager uses an improved workflow similar to common MySQL failover tools such as MHA:

  - [x] Verify replication settings
  - [x] Check (configurable) replication on the slaves
  - [x] Check for long running queries and transactions on master
  - [x] Elect a new master (default to most up to date, but it could also be a designated candidate)
  - [x] Put down the IP address on master by calling an optional script
  - [x] Reject writes on master by calling FLUSH TABLES WITH READ LOCK
  - [x] Reject writes on master by setting READ_ONLY flag
  - [x] Reject writes on master by decreasing MAX_CONNECTIONS
  - [x] Kill pending connections on master if any remaining
  - [x] Watching for all slaves to catch up to the current GTID position
  - [x] Promote the candidate slave to be a new master
  - [x] Put up the IP address on new master by calling an optional script
  - [x] Switch other slaves and old master to be slaves of the new master  
  - [x] Set slave read-only

## Traffic routing

__replication-manager__ is commonly used as an arbitrator with a proxy that routes the database traffic to the leader database node (aka the MASTER). We can advise usage of:

  - [x] Layer 7 proxy as MariaDB MaxScale that can transparently follow a newly elected topology
  - [x]  With monitor-less proxies, __replication-manager__ can call scripts that set and reload the new configuration of the leader route. A common scenario is an VRRP Active Passive HAProxy sharing configuration via a network disk with the __replication-manager__ scripts           
  - [x]  Using __replication-manager__ as an API component of a group communication cluster. MRM can be called as a Pacemaker resource that moves alongside a VIP, the monitoring of the cluster is in this case already in charge of the GCC.
