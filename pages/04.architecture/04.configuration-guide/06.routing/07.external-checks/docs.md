---
title: External Checks
taxonomy:
    category: docs
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

  - [x] Clusters in passive state return error code.  
  - [x] Servers in maintenance, failed or suspect will return error code.  
  - [x] Slaves with replication lags still return valid code
  - [x] Slaves with replication stopped return error code
  - [x] Master with READONLY return error code

---

## Version 3.1.42 Addendum

The following endpoint change complements the legacy paths above, which remain
documented for compatibility with existing scripts.

### Version 3.1.42, PR #1747: reader-status for new HAProxy checks

Newly provisioned HAProxy `externalcheck` configurations use the following
reader endpoint:

```text
http://{http-bind-address}:{http-port}/clusters/{clusterName}/servers/{serverHost}/{serverPort}/reader-status
```

Return code 200 means that the server is an eligible reader. Existing proxies
continue to use their generated `slave-status` script until they are
reprovisioned.
