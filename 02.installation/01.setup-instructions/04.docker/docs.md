---
title: Docker Image
taxonomy:
    category: docs
---

### Featured Tags

Docker images can be tagged per feature and architecture  
  * `pro`
    Build with all the extra dependencies needed for database maintenance and the last LTS MariaDB server.
    Default prov-orchestrator="opensvc" but support all orchestrations solution "k8s", "slapos", "onpremise"  

  * `dev`
    Build with all tools to build replication-manager. It does not have an entry point

    ```
    docker run --user=124:132  -eHOME=/go/src/github.com/signal18/replication-manager --detach --name=dev --interactive --tty  --volume=/home/dev/replication-manager:/go/src/github.com/signal18/replication-manager:rw --volume=/home/dev/etc/replication-manager:/etc/replication-manager:rw --volume=/home/dev/data:/var/lib/replication-manager:rw signal18/replication-manager:3.1-dev /bin/bash
    ```

  * `nightly`
    The pro release that reflect the last commit on our develop branch  

  * `unfeatured`
    The osc release that default to  prov-orchestrator="onpremise"

Logs can be found in `/var/log/replication-manager.log`.


####  About this Image

![replication-manager](https://github.com/signal18/replication-manager/raw/3.1/dashboard/static/img/logo.png)

Official Signal18 container images for _replication-manager__

__replication-manager__ is an high availability solution to manage MariaDB >= 10.x and MySQL & Percona Server 5.7 GTID replication topologies.  

Main features are:
 * Replication monitoring (gtid, multi source, delayed)
 * Topology detection (Leader for assync, semi-sync, multi-master, mesh, wsrep, group-repl, relay)  
 * Slave to master promotion (switchover)
 * Master election on failure detection (failover)
 * Replication best practice enforcement
 * Target up to zero loss in most failure scenarios
 * Multi clusters management
 * Proxy integration (ProxySQL, MaxScale, HAProxy, Spider)
 * Maintenance automation (Logical & physical Backups, Defrag, Backups Snapshot, Log Archiving)
 * Metrics history in carbon, graphite API
 * Alerting via EMail, Pushover Slack, Teams, Mattermost
 * Database Rejoining and Reseeding policy
 * Scriptable state and event
 * Remote scripting via SSH
 * Database, Proxy configurator
 * OpenSVC a K8S service deployment including init container
 * Encrypt config file secret, multi layer configs  
 * SSO
 * API with ACL
 * Capture log on high load
 * SLA tracking
 * Replication and monitoring user/password rotation or Vault usage


#### How to use this Image      

Based on a system user named repman in the hypervisor one can create directory ./etc ./etc/cluster.d and ./data   

In  ./etc wget  https://raw.githubusercontent.com/signal18/replication-manager/refs/heads/develop/etc/config.toml
In  ./etc/cluster.d wget https://raw.githubusercontent.com/signal18/replication-manager/refs/heads/develop/etc/cluster.d/cluster1.toml.sample

Customize config.toml for global scope settings or for settings that spread against all your monitored clusters

Customize cluster1.toml.sample  according to a my-fisrt-cluster

```
[cluster1]
title = "cluster1"
prov-orchestrator = "onpremise"
db-servers-hosts = "127.0.0.1:3331"
db-servers-prefered-master = "127.0.0.1:3331"
db-servers-credential = "root:mariadb"
replication-credential = "root:mariadb"
```

to

```
[my-fisrt-cluster]
title = "my-fisrt-cluster"
prov-orchestrator = "onpremise"
db-servers-hosts = "my-db1,my-db2"
db-servers-prefered-master = "my-db1"
db-servers-credential = "valid-super-user:mon-secret-password"
replication-credential = "valid-replication-user:mon-secret-password"
```

Rename your fist cluster config  

./etc/cluster.d/cluster1.toml.sample  to etc/cluster.d/my-fisrt-cluster.toml

Start the docker image that map to your config and with an empty datadir directory

```
docker run  -v/home/repman/etc/replication-manager:/etc/replication-manager:rw -v/home/repman/data:/var/lib/replication-manager:rw -p443:10005 -p80:10001 signal18/replication-manager:3.1
```

Extra ports from 10002 to 10004 exposing graphite & pickle api

Extra volume /root/.config/replication-manager Since version 3 is used to backup dynamic config changes  


### [Documentation](https://docs.signal18.io)

### [Code](https://github.com/signal18/replication-manager)
