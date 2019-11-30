---
title: Overview
---
## Goals

**replication-manager** have to be the best open source **database cluster orchestator**. Embed the best practices from configurations, deployments, HA operations, maintenance tasks, monitoring, troubleshooting. A toolkit for the best open source solutions. We design features to hide database clustering complexity, keeping Amazon RDS simplicity in mind.     

**replication-manager** also aims to be configurable with nix style, multi tenant, secured via encryption and ACL and user-friendly by offering API, cmd line and HTML interfaces around familiar database and proxying software.

**replication-manager** should bring multi cluster sharding and routing solutions to fixe the most requested issue around databases: **SCALABILITY**.

## History

**replication-manager** was initially written with the goal of closing the gap between Galera Cluster and MySQL Master HA.
While Galera Cluster is a really good product, it has some shortcomings such as performance and cluster-wide locking.
**replication-manager** was first a tailored solution on top of new features in MySQL and MariaDB such as Global Transaction ID, Semi-Sync Replication, Binary log Flashback, that aims to provide High Availability and Node Switchover without compromising the performance by a too large factor.

**replication-manager** was since day one adapted to manage deployments for his own testing requirements, a full stack cluster have to be provisioned, get tested and unprovisioned and move on other topologies or versions. We push the product from a local deployment, to  services managed by external orchestrator. The oldest integration is **OpenSVC** , with 2 modes:  with the collector API and more recently with direct cluster node VIP API. With the popularity of **Kubenrnetes** we also port our work to takeover. An other integration is currently being workout on **SlapOS** an open source hyperconverged and Edge computing infrastructure.      


## HA Workflow

To perform switchover, preserving data consistency, replication-manager uses an improved workflow similar to common MySQL failover tools such as MHA:

  - [x] Verify replication settings
  - [x] Check (configurable) replication on the slaves
  - [x] Check for long running queries and transactions on master
  - [x] Elect a new master (default to most up to date, but it could also be a designated candidate)
  - [x] Calling an optional script like putting down the IP address on master
  - [x] Tell proxies about the new state    
  - [x] Reject writes on master by calling FLUSH TABLES WITH READ LOCK
  - [x] Reject writes on master by setting READ_ONLY flag
  - [x] Reject writes on master by decreasing MAX_CONNECTIONS
  - [x] Kill pending connections on master if any remaining
  - [x] Watching for all slaves to catch up to the current GTID position
  - [x] Promote the candidate slave to be a new master
  - [x] Put up the IP address on new master by calling an optional script
  - [x] Tell proxies about the new state  
  - [x] Switch other slaves and old master to be slaves of the new master  
  - [x] Set slave read-only

## Traffic routing

__replication-manager__ is commonly used as an arbitrator with a layer that routes the database traffic to the leader database node (aka the MASTER):

  - [x] Layer 7 proxy as ProxySQL OR MariaDB MaxScale that can transparently follow a newly elected topology
  - [x] Layer 4 proxy, __replication-manager__ can produce new configuration with the new route or call external script. A common scenario is a VRRP Active Passive HAProxy sharing his configuration via a network disk or collocation of the proxy with replication-manager. Some other architecture components can be already in charge of the GCC.             
  - [x] Layer 4 proxy using __replication-manager__ as an API. __replication-manager__ can be called as a resource instructing the routing service on the states of the nodes, this is the case for haproxy external checks.
  - [x] DNS routing, __replication-manager__  will update some discovering service like consul or DNS to enable direct routing without proxying.
