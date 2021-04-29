---
title: OpenSVC Collector API
taxonomy:
    category: docs
---


The collector API is the oldest way to deployed services in OpenSVC , a more new way is via using the cluster API delivering http2 API from any agent node.  

##### `opensvc-host` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Address of the OpenSVC collector |
| Type | String |
| Default | "ci.signal18.io:9443" |
| Example | "127.0.0.1:443" |

##### `opensvc-admin-user` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Admin credential of the OpenSVC collector |
| Type | String |
| Default | "root@localhost.localdomain:opensvc" |
| Example | "root@signa18.io:secret" |



To use the SAS monitor you need to login to signal18.io and request for your evaluation or licence account.yaml file. Copy it into the share/opensvc directory of  **replication-manager-pro**
