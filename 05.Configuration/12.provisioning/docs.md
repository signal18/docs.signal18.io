---
title: Provisioning Configuration
---

## Provisioning

Since version 1.1 **replication-manager** can use an agent base cluster provisioning with the OpenSVC provisioning framework

Starting with version 2.0 provisioning is package via different binary call **replication-manager-pro**

```
./replication-manager-pro monitor
```

The following software services can be provisioned:
  - [x] MariaDB
  - [x] MySQL
  - [x] Percona
  - [x] MaxScale

We enable two type of micro-services:
  - [x] Docker
  - [x] Package


OpenSVC Agent drivers can provision disk resources on many SAN arrays and Cloud API, contact OpenSVC if you need custom type of disk provisioning for your architecture.

**replication-manager** is using a secure client API that talk to the OpenSVC collector and use the collector for posting actions to the agents, fetch cluster nodes informations and uploading his own set of playbook for provisioning.

After installing the evaluation version of the collector. It can be install colocated to **replication-manager** or install on a remote hardware this is tunable in config files via following parameters:

```
opensvc-host = "127.0.0.1:443"
opensvc-admin-user = "root@localhost.localdomain:opensvc"
```    

## Configurations options

Configuration of a micro-service is defined with a collection of resources:
  - [x] An existing disk device if none a loopback device to store service data
  - [x] An existing disk device if none a loopback device to store the docker data
  - [x] A file system type zfs|ext4|xfs|hfs|aufs
  - [x] A file system pool type lvm|zpool|none
  - [x] An IP address that need to be unused in the network

Resources choice is uniform over a full cluster.

Type of Micro-Services can be docker or package not that if package it need the package install on the agent as **replication-manager** will call the binary for bootstrapping.    
```
prov-db-service-type = "docker"
prov-db-docker-img = "mariadb:latest"
```

File system many drivers are available we do test xfs ext4 zfs the most othe like ceph or drbd would need additional testing to be used as may extra options need to be setup
```
prov-db-disk-fs = "zfs"
prov-db-disk-pool = "zpool"
```
Disks type can be loopback or device in case of type loopback the path is needed instead of device name
```
prov-db-disk-type = "loopback"
prov-db-disk-device = "/srv"
```

Network please check availability of the ip before using them , also some opensvc deployemetn can manage range of dhcp ip and DNS entries   
```
prov-db-net-iface = "br0"
prov0-db-net-gateway = "192.168.1.254"
prov-db-net-mask = "255.255.255.0"
```

Database bootstrap is deploying some database configurations files that are auto adapted to following cluster parameters and to tags:


Memory in M for micro service VM (default "256")
```
prov-db-memory = "256"  
```                         
Rnd IO/s in for micro service VM (default "300")
```  
prov-db-disk-iops = "300"                       
```  
Disk in g for micro service VM (default "20g")
```
prov-db-disk-size = "20g"                       
```
Disk in g for micro service VM (default "20g")
```
 prov-proxy-disk-size                    

```

### Extra database tags:
```
prov-db-tagsnfiguration = "semisync,innodb,noquerycache,threadpool,logslow"
```

Storage:
```
innodb, myrocks, tokudb, spider
```
Logs:
```
logaudit, logslow, logsqlerrors, loggeneral,
```
Features:
```
compress, noquerycache,  threadpool
```
Replication:
```
multidomains, nologslaveupdates, mysqlgtid, smallredolog
```

## Provisioning

Micro services placement will follow a round robin mode against the agents listed for a service.  

bootstap, and unprovision command can be found in the web interface

The client can also be used to provision fully a cluster defined in the configuration.
```
replication-manager-cli bootstrap  --cluster=cluster_haproxy_masterslave --with-provisioning
Provisioning done
```
## Database servers configuration (`db-servers`)

##### `db-servers-hosts` (2.0), `hosts` (1.1)

| Item | Value |
| ---- | ----- |
| Description | List of database hosts to monitor, IP and port (optional), specified in the host:[port] format and separated by a comma |
| Type | list |
| Example | "127.0.0.1:5055,127.0.0.1:5056" |

##### `db-servers-credential` (2.0), `user` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database login with root privileges, specified in the [user]:[password] format |
| Type | string |
| Example | "root:adminpassword" |

##### `db-servers-prefered-master` (2.0), `prefmaster` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Database preferred candidate for master election, in host:[port] format |
| Type          | string |
| Example       | "127.0.0.1:5055" |

##### `db-servers-ignored-hosts` (2.0), `ignore-servers` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Database list of hosts to ignore for master election |
| Type          | list |
| Example       | "127.0.0.1:5057" |

##### `db-servers-connect-timeout` (2.0), `connect-timeout` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Database connection timeout in seconds. The server will timeout if the connection cannot be established before that value. |
| Type          | integer |
| Default Value | 5 |

##### `db-servers-read-timeout` (2.0), `read-timeout` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Database I/O read timeout in seconds. The server will timeout if, on an already established connection, no data is received during a period equal to this option's value. |
| Type          | integer |
| Default Value | 15 |

##### `db-servers-read-timeout` (2.0), `read-timeout` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Database I/O read timeout in seconds. The server will timeout if, on an already established connection, no data is received during a period equal to this option's value. |
| Type          | integer |
| Default Value | 15 |
