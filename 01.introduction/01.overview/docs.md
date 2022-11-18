---
title: Overview
taxonomy:
    category: docs
---
## Goals

**replication-manager** have to be the best open source **database cluster orchestator**. Embed the best practices from configurations, deployments, HA operations, maintenance tasks, monitoring, troubleshooting. A toolkit for the best open source solutions. We design features to hide database clustering complexity, keeping Amazon RDS simplicity in mind.     

**replication-manager** also aims to be configurable with nix style, multi tenant, secured via encryption and ACL and user-friendly by offering API, command line and HTML interfaces around familiar databases and proxying softwares.

**replication-manager** should bring multi cluster sharding and routing solutions to fixe the most difficult issue around databases: **SCALABILITY** and **HIGH AVAILABILITY**.

## History

**replication-manager** was initially written with the goal of closing the gap between Galera Cluster and MySQL Master HA.
While Galera Cluster is a really good product, it has some shortcomings such as performance and cluster-wide locking.
**replication-manager** was first a tailored solution on top of new features in MySQL and MariaDB such as Global Transaction ID, Semi-Synchronous replication, binary log flashback, that aims to provide High Availability and node switchover without compromising  performance by a too large factor.

**replication-manager** was since day one adapted to manage deployments of clustered databases solutions for his own testing requirements, a full stack cluster have to be provisioned,  tested and unprovisioned, and move on using other topologies or product releases. We extend from solution for existing user deployments, to architecture as a service driven by external orchestrator. The oldest integration is **OpenSVC**, using 2 modes:  with the collector API and more recently with direct cluster node VIP API. With the popularity of **Kubenrnetes** we also port our work to takeover. An other integration is on **SlapOS** an open source hyperconverged and Edge computing infrastructure where ou API is used to check and perform actions on each component of the database infrastructure.      


## HA Workflow

To perform switchover on leader replicas , preserving data consistency, replication-manager uses an improved workflow similar to common MySQL failover tools such as MHA:

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

__replication-manager__ is commonly used as an arbitrator with a layer that routes write database traffic to a single leader database node.

 - [x] Layer 3: DNS routing, __replication-manager__  will update discovering service like consul or other API based DNS to enable direct routing without proxying.
 - [x] Layer 4: HaProxy __replication-manager__ can produce new configurations or be reconfigure HaProxy via API calls
 - [x] Layer 4: HaProxy can use external checks via calling __replication-manager__ API. as a resource manager instructing on the states of each cluster node,
 - [x] Layer 4:  __replication-manager can use external scripts to reused existing routes like VIP failover or executing Puppet and Ansible scenarios, ldirector reconfiguration scripts ...    
 - [x] Layer 7: ProxySQL OR MariaDB MaxScale can follow newly elected topology but get instant notifications of topology changes and does not need to rely on their own monitoring when exists
 - [x] Layer 7: MariaDB Spider layer can be instructed to split database traffic or to shard some tables to different database clusters


Based on each case one to many strategy can be used depending on each product feature maturity, security or performance requirements.
