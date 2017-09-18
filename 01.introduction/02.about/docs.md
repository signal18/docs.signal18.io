---
title: About Leader Election
---
Leader election cluster is best advise in such cases:

  - [x] Dysfunctional node have no impact on cluster performance
  - [x] Heterogeneous configuration and hardware have no impact on cluster performance
  - [x] Network speed have no impact on cluster performance
  - [x] Read scalability does not impact write scalability
  - [x] Network quality fluctuation have no impact on cluster performance
  - [x] Can benefit  human expertise on false positive failure detection
  - [x] Can benefit a minimum cluster size of two data nodes
  - [x] Can benefit having different database storage engines

This is achieved via some drawbacks:

  - [x] Overloading the leader can lead to data loss on failover or no failover depending of setup   
  - [x] READ on replica is eventually consistent  
  - [x] ACID can be preserved via route to leader always
  - [x] READ on replica can be COMMITTED READ under usage of the 10.2 semi-sync no slave behind feature


Leader Election Asynchronous Cluster can guarantee continuity of service at no performance cost for the leader and in some specific conditions with "No Data Loss".

In the field, regular scenario is to have long periods of time between hardware crashes: it is a requirement to repair the replication stream as fast as possible,  __replication-manager__ not only helps to do this, it also track failover SLA (Service Level Availability) to give an historical view on the replication stream latency and allow day to day switchover to enable database maintenance.  

It is not always desirable to perform  automatic failover in an asynchronous cluster, __replication-manager__ enforces some tunable settings to constraint the architecture state in which the failover can happen.

We can classify SLA for automatic failover scenario into 3 states:

  - [x] Replica stream in sync   
  - [x] Replica stream not sync but state allows automatic failover      
  - [x] Replica stream not sync but state does not allow automatic failover

! Staying in sync
!
! When the replication can be monitored in sync, the failover can be done without loss of data, provided that __replication-manager__ waits for all replicated events to be applied to the elected replica, before re-opening traffic.
! In order to reach this state most of the time, we advise next section settings.
