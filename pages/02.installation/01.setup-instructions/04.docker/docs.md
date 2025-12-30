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

**Step 1: Create directory structure**

Create directories on your Docker host as the `repman` user (or your preferred user):

```bash
mkdir -p ~/etc/cluster.d ~/data ~/config
```

**Step 2: Download configuration templates**

```bash
# Download main config
wget -O ~/etc/config.toml \
  https://raw.githubusercontent.com/signal18/replication-manager/refs/heads/3.1/etc/config.toml

# Download cluster config sample
wget -O ~/etc/cluster.d/cluster1.toml.sample \
  https://raw.githubusercontent.com/signal18/replication-manager/refs/heads/3.1/etc/cluster.d/cluster1.toml.sample
```

**Step 3: Customize configuration**

Edit `~/etc/config.toml` for global settings that apply to all clusters.

Edit `~/etc/cluster.d/cluster1.toml.sample` and customize for your first cluster

```
[cluster1]
title = "cluster1"
prov-orchestrator = "onpremise"
db-servers-hosts = "127.0.0.1:3331"
db-servers-prefered-master = "127.0.0.1:3331"
db-servers-credential = "root:mariadb"
replication-credential = "root:mariadb"
```

Change to:

```
[my-first-cluster]
title = "my-first-cluster"
prov-orchestrator = "onpremise"
db-servers-hosts = "my-db1:3306,my-db2:3306"
db-servers-prefered-master = "my-db1:3306"
db-servers-credential = "root:my-secret-password"
replication-credential = "repl_user:repl-password"
```

**Step 4: Rename cluster configuration file**

```bash
mv ~/etc/cluster.d/cluster1.toml.sample ~/etc/cluster.d/my-first-cluster.toml
```

**Step 5: Start the Docker container**

**Version 3.x** (requires three volume mounts):
```bash
docker run -v ~/etc:/etc/replication-manager:rw \
           -v ~/data:/var/lib/replication-manager:rw \
           -v ~/config:/root/.config/replication-manager:rw \
           -p 443:10005 \
           -p 80:10001 \
           --name replication-manager \
           --detach \
           signal18/replication-manager:3.1
```

**Version 2.x** (two volume mounts):
```bash
docker run -v ~/etc:/etc/replication-manager:rw \
           -v ~/data:/var/lib/replication-manager:rw \
           -p 443:10005 \
           -p 80:10001 \
           --name replication-manager \
           --detach \
           signal18/replication-manager:2.3
```

**Volume mounts explained:**
- `~/etc` → `/etc/replication-manager` - Configuration files (config.toml and cluster.d/)
- `~/data` → `/var/lib/replication-manager` - Runtime data and cluster state
- `~/config` → `/root/.config/replication-manager` - Dynamic config changes (3.x only)

**Exposed ports:**
- `10001` - HTTP web interface
- `10005` - HTTPS web interface and API
- `10002-10004` - Graphite & pickle API (optional)

>__Important__: Version 3.x requires the third volume mount (`/root/.config/replication-manager`) to persist dynamic configuration changes. Without it, any configuration changes made via the API or web UI will be lost when the container restarts  


### [Documentation](https://docs.signal18.io)

### [Code](https://github.com/signal18/replication-manager)
