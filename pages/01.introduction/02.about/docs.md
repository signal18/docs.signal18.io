---
title: About Leader Election
taxonomy:
    category: docs
---
Leader election clusters are recommended in these cases:

- [x] Dysfunctional nodes have no impact on cluster performance
- [x] Heterogeneous configuration and hardware have no impact on cluster performance
- [x] Network speed has no impact on cluster performance
- [x] Read scalability does not impact write scalability
- [x] Network quality fluctuation has no impact on cluster performance
- [x] Benefits from human expertise on false positive failure detection
- [x] Supports minimum cluster size of two data nodes
- [x] Supports different database storage engines

Trade-offs include:

- [x] Overloading the leader can lead to data loss on failover or prevent failover depending on setup
- [x] Read operations on replicas are eventually consistent
- [x] ACID properties can be preserved by routing all operations to the leader
- [x] Read operations on replicas can be committed reads when using MariaDB 10.2 semi-sync no-slave-behind feature


Leader election asynchronous clusters can guarantee continuity of service at no performance cost for the leader and in specific conditions achieve zero data loss.

In production deployments, hardware failures occur infrequently. When failures happen, repairing the replication stream quickly is critical. **replication-manager** facilitates rapid recovery and tracks failover SLA (Service Level Availability) to provide historical view of replication stream latency. This enables routine switchovers for database maintenance.

Automatic failover in asynchronous clusters is not always desirable. **replication-manager** provides tunable settings to constrain the architecture state in which failover can occur.

Automatic failover scenarios can be classified into three SLA states:

- [x] Replica stream in sync
- [x] Replica stream not in sync but state allows automatic failover
- [x] Replica stream not in sync and state does not allow automatic failover

! Staying in sync
!
! When replication can be monitored in sync, failover can be performed without data loss, provided that **replication-manager** waits for all replicated events to be applied to the elected replica before re-opening traffic.
! To maintain this state, use the settings described in the next section.
