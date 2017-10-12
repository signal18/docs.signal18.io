---
title: Sharding Cluster
---
| Support Status  | Test Case |  
| ----------------|-----------|
| Experiemental      | 0 |       

![Ring](/images/architecturesharding.png)

**replication-manager** can create each federation table on multiple master shard group inside spider proxies

**replication-manager** on failover and switchover of one master shard reattach the table federation to the new master in the shard group.

**replication-manager** constantly rediscover tables and schemas on each master shard group to maintain table definitions.

**replication-manager**  the advantage is that such topology maintain ACID capabilities beetwen tables on different servers and can enable very efficient cross shard joins. 
