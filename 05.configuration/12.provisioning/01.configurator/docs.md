---
title: Provisioning Configurator
---

## Configurator

Since version 2.1 **replication-manager** can generate database and proxy configurations based on cluster tagging and resources definition.

Resources and tagging choices are uniform over a full cluster.

The client API provide an archive tar.gz embedding all configurations files of a monitored service.

```
wget http://replication-manager:10001/api/clusters/cluster-mdbshardproxy-shard1/servers/db1/3331/config
```
On replication-manager data directory one can found such disk organization per cluster and per server

```
./mixr-dev
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/log
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/var
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init/etc
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init/etc/mysql
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init/etc/mysql/custom
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init/etc/mysql/rc.d
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init/etc/mysql/ssl
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init/init
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init/data
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init/data/.system
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init/data/.system/tokudb
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init/data/.system/tmp
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init/data/.system/repl
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init/data/.system/logs
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init/data/.system/innodb
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init/data/.system/innodb/undo
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init/data/.system/innodb/redo
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/init/data/.system/aria
./mixr-dev/db-fr-1.mixr-dev.svc.cloud18_3306/bck
```

The cluster/server/init subdirectories contains the files hierarchy to mount containers volumes   
data -> /var/lib/mysql
etc/mysql -> /etc/mysql

The cluster level contains a config.tar.gz of the init sub-directories used in API

etc/mysql/rc.d contain symlinks to config files in etc/mysql
etc/mysql/custom read last to enable mounting custom user config on top of tagging

Orchestrators can use init containers to provision a new service with the selected replication-manager practices. Some meta data tags like ZFS will setup multiple symlink to configuration files including MySQL or MariaDB features needed to best run on ZFS (noodirect noaio nodoublewrite)


### Kubernetes

```
apiVersion: v1
kind: Pod
metadata:
  name: init-demo
spec:
  containers:
  - name: mariadb
    image: mariadb
    ports:
    - containerPort: 3306
    volumeMounts:
    - name: workdir
      mountPath: /data
    - name: config
        mountPath: /etc
  # These containers are run during pod initialization
  initContainers:
  - name: install
    image: busybox
    command:
    - wget
    - "-O"
    - "/api/clusters/cluster-mdbshardproxy-shard1/servers/db1/3306/config"
    - http://replication-manager:10001
    command:
    - tar
    - "xzf"
    - "config"
    volumeMounts:
    - name: workdir
      mountPath: /data
    - name: config
        mountPath: /etc
  dnsPolicy: Default
  volumes:
  - name: workdir
    emptyDir: {}


```
### OpenSVC

```
[container#0002]
detach = false
type = docker
image = busybox
netns = container#0001
rm = true
volume_mounts = /etc/localtime:/etc/localtime:ro {env.base_dir}/pod01:/data
command = sh -c 'wget -qO- http://{env.mrm_api_addr}/api/clusters/{env.mrm_cluster_name}/servers/{env.ip_pod01}/{env.port_pod01}/config|tar xzvf - -C /data'
```


The following databases software services are managed

| Item | Docker |
| ---- | ------ |
| MariaDB | Y   |
| MySQL | Y   |
| Percona | Y   |


##  Configuring resourcess

##### `prov-db-tags (1.1)`

| Item | Value |
| ---- | ----- |
| Description | Database tags for compliance configuration. |
| Type | String |
|| Example | "innodb,noquerycache,threadpool,logslow" |

Engines:
```
innodb, myrocks, spider, sphinx, blackhole, connect, oqgraph, tokudb
```

Disks:
```
nodoublewrite, noodirect, noaio, smallredolog, nodurable, autodefrag, ssd, zfs, compressbinlog
```

Logs:
```
audit, slow, sqlerrors, general, pfs, userstats, metadatalocks
```

Network:
```
noquerycache, threadpool, resolvdns, proxyprotocol
```

Security:
```
ssl, pwdchecksimple, pwdcheckcracklib, encryptfile
```

Optimizer:
```
compresstables, lowercasetable ,sqlmodeunstrict, sqlmodeoracle
 noautocommit, eits
```

Replication:
```
multidomains, nologslaveupdates, mysqlgtid, wsrep, semisync
```

##### `prov-db-service-type` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database type of Micro-Services deployment|
| Type | Enum |
| Values | Docker,Package |
| Example | "Docker" |

Type of Micro-Services can be docker or package not that if package it need the package install on the agent as **replication-manager** will only call the binary for bootstrapping and expect it to be present on the agent.    

## Configure Resource Usage

Database bootstrap is deploying some database configurations files that are auto adapted to following cluster parameters :

##### `prov-db-disk-size` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Databse disk size in g for micro service VM . |
| Type | String |
| Default | "20" |
| Example | "20  |


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
