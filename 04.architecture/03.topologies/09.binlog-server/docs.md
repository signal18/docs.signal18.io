---
title: Binlog Server
---
| Support Status  | Test Case |  
| ----------------|-----------|
| Experiemental      | 0 |       


**replication-manager** supports Maxscale binlog server architecture, in case of master death one of the slaves under the  binlog server is promoted as a master.

**replication-manager** does not manage yet the binlog server crash to replace it with an other one.


[See Maxscale](/configuration/routing-traffic/maxscale)
