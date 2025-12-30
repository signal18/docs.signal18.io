---
title: Multi Tier Slaves
taxonomy:
    category: docs
---
| Support Status  | Test Case |  
| ----------------|-----------|
| Experimental    | 0 |       

**replication-manager** supports replication tree or relay slaves architecture, in case of master death one of the slaves under the relay is promoted as a master.

**replication-manager** does not manage when the relay server crash to replace it with a slave.

But in **replication-manager 2.2**  one can archive same goal using multi source, multi domains child clusters. All servers  of the same tenant will be monitor and inter cluster replication will discovered, so creating replication from the leader of a tier cluster on the leader of a parent cluster, multi tiers topology can then be switchover and failover  

```
Cluster-1-Leader
  -> Cluster-1-Replicate-1/Cluster2-Leader
     -> Cluster-2-Replicate-1
     -> Cluster-2-Replicate-2
  -> Cluster-1-Replicate-2/Cluster-3-Leader
     -> Cluster-3-Replicate-1     
     -> Cluster-3-Replicate-2
```

##### `replication-multi-tier-slave` (2.0), `multi-tier-slave` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Enable relay slaves topology |
| Type | boolean |
| Default Value | false |   
