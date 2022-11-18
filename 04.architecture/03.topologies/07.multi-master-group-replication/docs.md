---
title: Multi Master Group Replication
taxonomy:
    category: docs
---
| Support Status  | Test Case |  
| ----------------|-----------|
| Stable     | 0 |       

##### `replication-multi-master-grouprep (2.3)`


| Item | Value |
| ---- | ----- |
| Description | Enable MySQL Multi Master Group Replication Monitoring |
| Type | boolean |
| Default Value | false |  


Group replication is not always adapted for a Write to all node architecture, for following reasons:


- It increase deadlock probability, while transactions are replicated in concurrency it's style take time to round trip the network, certification only apply on checkpointing, so the more nodes the more latency the more deadlock the workload may be exposed.

For that reason your applications may have to always point to a single leader Group Replication node.

**replication-manager** can help you to get the best of Group Replication combine with layer7 proxy ProxySQL or layer 4 like HaProxy to point to a single node

**replication-manager** can monitor PRIMARY group status and can elect a virtual master on switchover, based on each Group Replication node status, if one node get excluded for any reason of that cluster it it removed from leader or reader group and move to status Unconnected being rejected from routers and proxies.

**replication-manager** Can bootstrap the group replication on already well configured node and can produce valid configuration using following config tags  

prov-db-tags = "grouprep,nosplitpath"

**replication-manager** Does not yet check valid configuration for Group Replication but this can be added on demand, for now following the MySQL documentation is needed to setup correct deployment
