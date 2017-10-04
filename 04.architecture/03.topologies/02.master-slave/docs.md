---
title: Master-Slave
---
| Support Status  | Test Case |  
| ----------------|-----------|
| Production      | 175 |       


**replication-manager**  supports 2-node and x nodes setup, it is advice to use at least 3 nodes cluster to keep the cluster tolerant to HA when losing or stopping a slave.  

**replication-manager** can ignore slaves or have a preferred node to failover or switchover.

By default **replication-manager** assume a flat rejoin topology but can also promote multi-tier topology with some additional setting, scenario is you stop a slave and his master die or change, when the old master rejoin the topology it can keep his slave under himself instead of switching the slave to the new master d



##### `replication-multi-tier-slave` (2.0), `multi-tier-slave` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Enable relay slaves topology |
| Type | boolean |
| Default Value | false |   
