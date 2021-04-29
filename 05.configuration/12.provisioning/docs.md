---
title: Provisioning Orchestration
taxonomy:
    category: docs
---

## Provisioning Settings

Since version 1.1 **replication-manager** can use an agent-based cluster provisioning with the OpenSVC provisioning framework.

Starting with version 2.0 provisioning is packaged in a separate binary called **replication-manager-pro**

In **replication-manager 2.1** one can specify an orchestrator for provisioning the default orchestrator is onpremise, that call external scripts for provisioning  


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
| SpiderProxy | Y   | N  |
| Sphinx | Y   | N  |
| Consul | N  | N  |


 ##### `prov-orchestrator` (2.1)

 | Item | Value |
 | ---- | ----- |
 | Description | Orchestration type |
 | Type | String |
 | Values | onpremise opensvc kube slapos local |
 | Default | onpremise |all for pro release and onpremise local for osc release  |
 | Example | "opensvc" |

 ##### `prov-orchestrator-cluster` (2.1)


 | Item | Value |
 | ---- | ----- |
 | Description | The orchestrated cluster used in FQDNS  |
 | Type | String |
 | Default |local |
 | Example | "cluster1" |

FQDNS off one service will be
```
<service_name>.<namespace_name>.svc.<cluster_name>
```


**replication-manager** affect service_name to host name defined in db-servers-hosts or proxy-servers-hosts, namespace_name to the cluster section and cluster_name to prov-orchestrator-cluster

AKA: db1.bench.svc.cluster1, db2.bench.svc.cluster1

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
| Values | docker,package,oci,podman |
| Example | "Docker" |

Type of Micro-Services can be docker or package not that if package it need the package install on the agent as **replication-manager** will only call the binary for bootstrapping and expect it to be present on the agent.    

OCI special meaning for OpenSVC podman or docker


## Placement

Micro-services placement will follow a round robin mode against the agents listed for a service.  

bootstrap and unprovision commands can be found in the web interface.

The client can also be used to fully provision a cluster defined in the configuration.
```
replication-manager-cli bootstrap  --cluster=cluster_haproxy_masterslave --with-provisioning
Provisioning done


##### `prov-db-agents` (1.1)

| Item | Value |
| ---- | ----- |
| Description | List of agents for Database Micro-Services placement|
| Type | List |
| Values | Docker,Package |
| Example | "Docker" |

The agent names can be found in the web interface under the agents tab.

## Network

We advice usage of Orchestrator CNI virtual network, predefined network made available from the orchestrator administrators
Volume is the only way to go inside K8S while you can refer to advanced options for OpenSVC where some internal network config can be refine  

##### `prov-net-cni-cluster` (2.0)


| Item | Value |
| ---- | ----- |
| Description | Name of orchestrator virtual network tu use |
| Type | Sting |
| Default | default |
| Example | backend1 |

```
om net status
name          type           network       size   used  free   pct    
|- backend    undef          undef         1      0     1      0.00%  
|- backendv6  routed_bridge  fdfe::/112    65536  4     65532  0.01%  
|- default    bridge         10.22.0.0/16  65536  0     65536  0.00%  
|- lo         loopback       127.0.0.1/32  1      0     1      0.00%  
```

##### `prov-net-cni` (2.0)
| Item | Value |
| ---- | ----- |
| Description | Does orchetrator use CNI |
| Type | Boolean |
| Default | true |


## Disk

We advice usage of Orchestrator volumes, predefined storage ressources made available from the orchestrator administrators
Volume is the only way to go inside K8S while you can refer to advanced options for OpenSVC where each FS can be refine  

prov-db-disk-type = "volume"
prov-db-volume-data = "db-nvme"
prov-proxy-disk-type = "volume"
prov-proxy-volume-data = "proxy-sas"


##### `prov-db-docker-img` (1.1)

| Item | Value |
| ---- | ----- |
| Description | The database docker image to deploy|
| Type | String |
| Example | "mariadb:latest" |


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
