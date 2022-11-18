---
title: Sharding Cluster
taxonomy:
    category: docs
---
| Support Status  | Test Case |  
| ----------------|-----------|
| Experiemental      | 0 |       

![Ring](/images/architecturesharding.png)

**replication-manager** can create each federation table on multiple master shard group inside spider proxies

**replication-manager** on failover and switchover of one master shard reattach the table federation to the new master in the shard group.

**replication-manager** constantly rediscover tables and schemas on each master shard group to maintain table definitions.

**replication-manager**  the advantage is that such topology maintain ACID capabilities between tables on different servers and can enable very efficient cross shard joins.

[plugin:youtube](https://www.youtube.com/watch?v=Gtb1oi_7Lq4)
