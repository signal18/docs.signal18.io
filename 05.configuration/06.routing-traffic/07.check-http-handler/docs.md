---
title: Check HTTP Handlers
---

**replication-manage 2.0** can be used from proxies like HaProxy to check the status of the monitored servers.

We provide 2 different checksfor this.

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
