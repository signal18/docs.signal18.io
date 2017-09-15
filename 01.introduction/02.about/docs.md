---
title: About Leader Election
---
Leader Election Cluster is best used in such scenarios:

  - [x] Dysfunctional node does not impact leader performance
  - [x] Heterogeneous node in configuration and resources does not impact leader performance
  - [x] Leader peak performance is not impacted by data replication
  - [x] Read scalability does not impact write scalability
  - [x] Network interconnect quality fluctuation
  - [x] Can benefit of human expertise on false positive failure detection
  - [x] Can benefit a minimum cluster size of two data nodes
  - [x] Can benefit having different storage engines

This is achieved via the following drawbacks:

  - [x] Overloading the leader can lead to data loss during failover or no failover depending of setup   
  - [x] READ on replica is eventually consistent  
  - [x] ACID can be preserved via route to leader always
  - [x] READ on replica can be COMMITTED READ under usage of the 10.2 semi-sync no slave behind feature


Leader Election Asynchronous Cluster can guarantee continuity of service at no performance cost for the leader and in some specific conditions with "No Data Loss",

In the field, a regular scenario is to have long periods of time between hardware crashes: it is fine to analyze the state of the replication when crash happens, but  __replication-manager__ will track failover SLA(Service Level Availability) to give an historical view of the replication stream latency.


It is not always desirable to perform  automatic failover in an asynchronous cluster, __replication-manager__ enforces some tunable settings to constraint the architecture state in which the failover can happen.


We can classify SLA for automatic failover scenario into 3 states:

  - [x] Replica stream in sync   
  - [x] Replica stream not sync but state allows automatic failover      
  - [x] Replica stream not sync but state does not allow automatic failover

! Staying in sync
!
! When the replication can be monitored in sync, the failover can be done without loss of data, provided that __replication-manager__ waits for all replicated events to be applied to the elected replica, before re-opening traffic.
! In order to reach this state most of the time, we advise next section settings.
