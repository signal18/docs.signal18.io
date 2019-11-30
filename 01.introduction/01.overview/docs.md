---
title: Overview
---
## Goals

**replication-manager** have to be the best open source **database cluster orchestator**. Embed the best practices from configurations, deployments, HA operations, maintenance tasks, monitoring, troubleshooting as a toolkit for the best open source solutions. We design features to hide database clustering complexity keeping Amazon RDS simplicity in mind.     

**replication-manager** also aims to be configurable with nix style, multi tenant,secured via encryption and ACL and user-friendly by offering API, cmd line and HTML interfaces to interaction with familiar database and proxying software.

**replication-manager** should bring multi cluster sharding and routing solutions to fixe the most requested issues around databases: **SCALABILITY**.

## History

**replication-manager** was initially written with the goal of closing the gap between Galera Cluster and MySQL Master HA.
While Galera Cluster is a really good product, it has some shortcomings such as performance and cluster-wide locking.
**replication-manager** was first a tailored solution on top of new features in MySQL and MariaDB such as Global Transaction ID, Semi-Sync Replication, Binary log Flashback, that aims to provide High Availability and Node Switchover without compromising the performance by a too large factor.

**replication-manager** was since day one adapt to manage deployment to manage his own testing requirement, a full stack cluster have to be provisioned, receive some tests to play and unprovisioned to move on other topology or version testing. We evolve the product from  local deployment, to full service managed by external orchestrator. Oldest integration is **OpenSVC** , with 2 modes one with the collector API and more recently with direct cluster node VIP API. With the popularity of **Kubenrnetes** we also port our work to it. An other integration is currently being workout on **SlapOS** an open source hyperconverged and Edge computing infrastructure.      


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
