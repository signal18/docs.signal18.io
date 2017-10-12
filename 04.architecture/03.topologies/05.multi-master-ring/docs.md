---
title: Multi Master Ring
---
| Support Status  | Test Case |  
| ----------------|-----------|
| Alpha      | 0 |

**replication-manager** can automate ring topology maintenance and failover.

A virtual master is elected on the ring to enable the proxies to reach a single node.

**replication-manager** failover close the ring around the available nodes and rejoin the node when maintenance is finished or if the node was failed and reintroduce the cluster.

**replication-manager** will put out a node of the ring if this node is set for maintenance via the the replication-manager clients. The node will still replicate as a slave of it's parent

A switchover is just changing the virtual master to the next node in the ring in descending order


##### `replication-multi-master-ring (2.0)`

| Item | Value |
| ---- | ----- |
| Description | Enable Multi Master Ring topology |
| Type | boolean |
| Default Value | false |  

![Ring](/images/architecturering.png)
