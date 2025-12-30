---
title: OpenSVC Collector API
taxonomy:
    category: docs
---


The collector API is the oldest way to deployed services in OpenSVC , a more new way is via using the cluster API delivering http2 API from any agent node.  


**replication-manager** is using a secure client API to the OpenSVC collector. This collector is used for posting actions to a cluster of agents, fetch cluster nodes information and uploading his own set of playbooks for provisioning.

The Signal18.io SAS collector can be used for faster testing or not to have to maintain an extra piece of infrastructure, if not possible, an evaluation version of the collector needs to be installed, or it can be installed co-located to **replication-manager** or on a separate machine.

**replication-manager** talks to the default SAS collector or it can be setup to talk to on premise collector via the following parameters:



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



To use the Signal18 SAS collector you need to login to signal18.io and request for your evaluation or licence account.yaml file. Copy it into the share/opensvc directory of  **replication-manager-pro**
