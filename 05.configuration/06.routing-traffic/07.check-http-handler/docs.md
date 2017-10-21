---
title: Check HTTP Handlers
---

**replication-manage 2.0** can be used from proxies like HaProxy to check the status of the monitored servers.

We provide different checks for master and slave status.

```
https://{monitor-api-address}/api/clusters/{clusterName}/servers/{serverName}/master-status
https://{monitor-api-address}/api/clusters/{clusterName}/servers/{serverHost}/{serverPort}/master-status
http://{http-bind-address}:{http-port}/clusters/{clusterName}/servers/{serverHost}/{serverPort}/master-status

```
Return Code 200 if server id is a master

```
https://{monitor-api-address}/api/clusters/{clusterName}/servers/{serverName}/slave-status
https://{monitor-api-address}/api/clusters/{clusterName}/servers/{serverHost}/{serverPort}/slave-status
http://{http-bind-address}:{http-port}/clusters/{clusterName}/servers/{serverHost}/{serverPort}/slave-status
```
Return Code 200 if server id is a slave   

> **{clusterName}** is the name of the section name define in the config file default should be it if no multiple clusters are defines


> **{serverName}** can be found via the client command **topology**

  - [x] cluster in passive state return error code.  
  - [x] Servers in maintenance, failed or suspect will return error code.  
  - [x] Slaves with replication lags still return valid code
  - [x] Slaves with replication stopped return error code
  - [x] Master with READONLY return error code
