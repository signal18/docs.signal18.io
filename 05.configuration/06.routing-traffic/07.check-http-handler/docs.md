---
title: Check HTTP Handlers
---

**replication-manage 2.0** can be used from proxies like HaProxy to check the status of the monitored servers.

We provide 2 different checks for this.

```
https://{replication-manger-address}:3000/api/clusters/{clusterName}/servers/{serverName}/master-status
```
Return Code 200 if server id is a master

```
https://{replication-manger-address}:3000/api/clusters/{clusterName}/servers/{serverName}/slave-status
```
Return Code 200 if server id is a slave   

> **{clusterName}** is the name of the section name define in the config file default should be it if no multiple clusters are defines


> **{serverName}** can be found via the client command **topology**

-[X] Servers in maintenance, failed or suspect will return error code.  
-[X] Slaves with replication lags still return valid code
-[X] Slaves with replication stopped return error code
-[X] Master with READONLY return error code
