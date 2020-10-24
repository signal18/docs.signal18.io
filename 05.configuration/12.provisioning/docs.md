---
title: Provisioning Configuration
taxonomy:
    category: docs
---

## Provisioning Settings

Since version 1.1 **replication-manager** can use an agent-based cluster provisioning with the OpenSVC provisioning framework.

Starting with version 2.0 provisioning is packaged in a separate binary called **replication-manager-pro**

```
./replication-manager-pro monitor
```

The following software services can be provisioned:

| Item | Docker | Package |
| ---- | ------ | ------- |
| MariaDB | Y   | Y |
| MySQL | Y   | Y |
| Percona | Y   | Y |
| MaxScale | Y   | Y |
| ProxySQL | Y   | N  |
| HaProxy | Y   | N  |
| Sphinx | Y   | N  |
| Consul | N  | N  |



**replication-manager** is using a secure client API to the OpenSVC collector. This collector is used for posting actions to a cluster of agents, fetch cluster nodes information and uploading his own set of playbooks for provisioning.

 The Signal18.io SAS collector can be used for faster testing or not to have to maintain an extra piece of infrastructure, if not possible, an evaluation version of the collector needs to be installed, or it can be installed co-located to **replication-manager** or on a separate machine.

 **replication-manager** talks to the default SAS collector or it can be setup to talk to on premise collector via the following parameters:


 ##### `prov-orchestrator` (2.1)

 | Item | Value |
 | ---- | ----- |
 | Description | Orchestration type |
 | Type | String |
 | Values | onpremise|opensvc|kube|slapos|local |
 | Default | onpremise|opensvc|kube|slapos|local for pro release or onpremise|local for osc release  |
 | Example | "opensvc" |

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

For on premise collector it is needed to make a first registration on the collector to create a regular user, group, roles and compliances.

```
replication-manager-pro monitor --opensvc-register
```

To use the SAS monitor you need to login to signal18.io and request for your evaluation or licence account.yaml file. Copy it into the share/opensvc directory of  **replication-manager-pro**

## Services Options

**micro-services** are isolating a collection of resources, each service can than be used for easy management, HA policy like DR plan in agent 1.8 and placement over a cluster on agent version 1.9

  * An existing disk device, if none a loopback device to store service data
  * An existing disk device, if none a loopback device to store the docker data
  * A file system type zfs|ext4|xfs|hfs|aufs
  * A file system pool type lvm|zpool|none
  * An IP address that needs to be unused in the network

Resources choice is uniform over a full cluster.


##### `prov-db-service-type` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database type of Micro-Services deployment|
| Type | Enum |
| Values | Docker,Package |
| Example | "Docker" |

Type of Micro-Services can be docker or package not that if package it need the package install on the agent as **replication-manager** will only call the binary for bootstrapping and expect it to be present on the agent.    

##### `prov-db-agents` (1.1)

| Item | Value |
| ---- | ----- |
| Description | List of agents for Database Micro-Services placement|
| Type | List |
| Values | Docker,Package |
| Example | "Docker" |

The agent names can be found in the web interface under the agents tab.

## Disk

##### `prov-db-docker-img` (1.1)

| Item | Value |
| ---- | ----- |
| Description | The database docker image to deploy|
| Type | String |
| Example | "mariadb:latest" |


##### `prov-db-disk-fs` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database type of FS deployment|
| Type | Enum |
| Values | xfs,ext4,zfs,ufs |
| Example | "zfs" |

File system many drivers are available we do test xfs ext4 zfs . Many other drivers like ceph or drbd would need additional testing to be used as extra options may need to be added.

OpenSVC Agent drivers can provision disk resources on many SAN arrays and Cloud API, contact support if you need custom type of disk provisioning for your architecture.

##### `prov-db-disk-pool` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database disk pool type for Micro Services deployment|
| Type | Enum |
| Values | none,zpool,lvm |
| Example | "zpool" |

##### `prov-db-disk-type` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database disk pool type for Micro Services deployment|
| Type | Enum |
| Values | loopback,physical,pool,directory|
| Example | "loopback" |

When loopback instead of a real device the FS path is needed instead of device path

##### `prov-db-disk-device` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database disk device path for Micro Services deployment|
| Type | String |
| Example | "/srv" |

Depends on  `prov-db-disk-type`

physical: define the device /dev/XXXXXXXX
pool: define the pool name
loopback: define  the path to create the loopback file
directory: define the path to create the service_name directory

##### `prov-db-disk-size` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Databse disk size in g for micro service VM . |
| Type | String |
| Default | "20g" |
| Example | "20g"  |

##### `prov-db-disk-snapshot` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Take daily snapshot. |
| Type | boolean |
| Default | false |


##### `prov-db-disk-snapshot-keep` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Keep this number of snapshot. |
| Type | int |
| Default | 7 |

## network

Network please check availability of the ip before using them , also some opensvc deployemetn can manage range of dhcp ip and DNS entries   

##### `prov-db-net-iface` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database ethernet device. |
| Type | String |
| Example | "br0" |

##### `prov0-db-net-gateway` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database network gateway. |
| Type | String |
| Example | "192.168.1.254" |

##### `prov-db-net-mask` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database eth device |
| Type | String |
| Example | "255.255.255.0" |

## Resource Usage

Database bootstrap is deploying some database configurations files that are auto adapted to following cluster parameters and to tags:

##### `prov-db-memory` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database memory in M for micro service. |
| Type | String |
| Example | "256" |

##### `prov-db-disk-iops` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database Rnd IO/s in for micro service. |
| Type | String |
| Default | "300" |
| Example | "300" |


##### `prov-db-cpu-cores` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Database number of cores for micro service. |
| Type | String |
| Default | "1" |
| Example | "4" |


### Database tags:

##### `prov-db-tags (1.1)`

| Item | Value |
| ---- | ----- |
| Description | Database tags for compliance configuration. |
| Type | String |
|| Example | "innodb,noquerycache,threadpool,logslow" |

Storage:
```
innodb, myrocks, tokudb, spider, sphinx
```
Logs:
```
logaudit, logslow, logsqlerrors, loggeneral, logpfs, loguserstats
```
Features:
```
compress, threadpool, ssl, lowercasetable, smallredolog,sqlmodeunstrict, sqlmodeoracle
 noquerycache,  nodurable, nodoublewrite,  noautocommit, noodirect
```
Replication:
```
multidomains, nologslaveupdates, mysqlgtid, wsrep, semisync
```

## Provisioning

Micro-services placement will follow a round robin mode against the agents listed for a service.  

bootstrap and unprovision commands can be found in the web interface.

The client can also be used to fully provision a cluster defined in the configuration.
```
replication-manager-cli bootstrap  --cluster=cluster_haproxy_masterslave --with-provisioning
Provisioning done
```
