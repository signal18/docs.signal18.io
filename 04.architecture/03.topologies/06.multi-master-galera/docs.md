---
title: Multi Master Galera
---
| Support Status  | Test Case |  
| ----------------|-----------|
| Experimental      | 0 |       

##### `replication-multi-master-wsrep (2.0)`

| Item | Value |
| ---- | ----- |
| Description | Enable Multi Master Galera  |
| Type | boolean |
| Default Value | false |  


Galera cluster is not always adapted for a Write to all node architecture, for those reasons:

- It do not support serialized isolation level, READ LOCKS acquire no cluster locks and can only be validated for REPEATABLE READ or SERIALIZED on the local node the transaction is executed.   

- It increase deadlock probability, while transactions are replicated in concurrency it's style take time to round trip the network, certification only apply on checkpointing, so the more nodes the more latency the more deadlock the workload may be exposed.

For those reasons your application as to be modify to write critical sections on a leader Galera node.

**replication-manager** can help you to get the best of Galera combine with layer7 proxy like MaxScale or ProxySQL.

**replication-manager** will elect a virtual master and failover or switchover it based on each Galera node status, if one node get excluded for any reason of that cluster.

**replication-manager** could help to get 2 node Galera Cluster with one **replication-manager** on each node and a **replication-manager-arb** to manage active-passive role on each.
The active **replication-manager**  can be use to start garbd and enable the cluster to survive.
