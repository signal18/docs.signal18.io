---
title: Provisioning Service Software Configurator
taxonomy:
    category: docs
---

## Configurator

Since version 2.1 **replication-manager** can generate database and proxy configurations based on cluster tagging and hardware resources description.

Resources and tagging choices are uniform over a cluster.

The client API enable to download an archive tar.gz embedding all configurations files and directories structure to bootstrap a service.

And easy and insecure way requesting no login information is available by default
```
wget http://replication-manager:10001/api/clusters/cluster-mdbshardproxy-shard1/servers/db1/3331/config
```

This open default can be disable for security reasons and not exposing the databases passwords that could be found in various configuration files

##### `api-credentials-secure-config` (2.1)
| Description | Need JWT token to download config tar.gz. |
| Type | Boolean |
| Default | false |
| Example | true |


A secure way of initial setup is via downloading a bootstrap code that uses ENV variables to provide the replication-manager credentials  

```
wget -q -O- http://repman.s18.svc.rs1:10001/static/configurator/opensvc/bootstrap

```
This script get a session TOKEN via pushing JSON credentials:

ENV variable: REPLICATION_MANAGER_USER=admin
ENV variable: REPLICATION_MANAGER_PASSWORD=repman

It call the secure HTTPS API define in:

ENV variable: REPLICATION_MANAGER_URL=https://repman.s18.svc.rs1:10005

Then use other ENV variables to built the URL and calling the API to download the config

ENV variable: REPLICATION_MANAGER_CLUSTER_NAME=bench
ENV variable: REPLICATION_MANAGER_HOST_NAME=db1.bench.svc.rs1
ENV variable: REPLICATION_MANAGER_HOST_PORT=3306

When Orchestrator is OpenSVC we push those informations into per service secret map  when they are sensible and to a config map for less sensible, we then build our service config by exposing them to the init container of the service.


### Init container sample in Kubernetes

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
###  Init container sample on OpenSVC

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


## Database Config content

Inside replication-manager data directory one can found similar disk organization per cluster and per server


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


etc/mysql/rc.d contain symlinks to config files in etc/mysql
etc/mysql/custom read last to enable mounting custom user config on top of tagging



##  Configuring database resources


## Configure Resource Usage

Database bootstrap is deploying some database configurations files that are auto adapted to following cluster parameters :

#### Disk

##### `prov-db-disk-size` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database disk size in g for micro service VM . |
| Type | String |
| Default | "20" |
| Example | "20  |

##### `prov-db-disk-iops` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database Rnd IO/s in for micro service. |
| Type | String |
| Default | "300" |
| Example | "300" |


### Memory

##### `prov-db-memory` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database memory in M for micro service. |
| Type | String |
| Example | "256" |

##### `prov-db-memory-shared-pct`(2.1)

| Item | Value |
| ---- | ----- |
| Description | split prov-db-memory shared per global buffer |
| Type | String |
| default | "threads:16,innodb:60,myisam:10,aria:10,rocksdb:1,tokudb:1,s3:1,archive:1,querycache:0" |

##### `prov-db-memory-threaded-pct`(2.1)                  


| Item | Value |
| ---- | ----- |
| Description | split prov-db-memory-shared-pct threads part per thread buffer |
| Type | String |
| default | "tmp:70,join:20,sort:10"|


### CPU

##### `prov-db-cpu-cores` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Database number of cores for micro service. |
| Type | String |
| Default | "1" |
| Example | "4" |

### Features

### Database tags:


##### `prov-db-tags (1.1)`

| Item | Value |
| ---- | ----- |
| Description | Database tags for compliance configuration. |
| Type | String |
| Example | "innodb,noquerycache,threadpool,logslow" |

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
