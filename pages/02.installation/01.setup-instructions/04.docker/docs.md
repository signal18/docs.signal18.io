---
title: Docker Image
taxonomy:
    category: docs
---

## About this Image

Official Signal18 container images provide a complete deployment of **replication-manager** with all dependencies included.

### Key Features

**Replication Management:**
- Replication monitoring (GTID, multi-source, delayed replication)
- Topology detection (async, semi-sync, multi-master, mesh, Galera, Group Replication, relay)
- Switchover (slave to master promotion)
- Failover (master election on failure detection)
- Best practice enforcement
- Target zero data loss in most failure scenarios

**Operations:**
- Multi-cluster management from single instance
- Proxy integration (ProxySQL, MaxScale, HAProxy, Spider)
- Maintenance automation (logical and physical backups, defragmentation, snapshot backups, log archiving)
- Database rejoining and reseeding policies
- Scriptable state changes and events
- Remote scripting via SSH
- Dynamic database and proxy configuration

**Deployment & Orchestration:**
- OpenSVC and Kubernetes service deployment with init containers
- SlapOS integration
- On-premise orchestration

**Security & Configuration:**
- Configuration file encryption
- Multi-layer configuration hierarchy
- Single sign-on (SSO)
- API with access control lists (ACL)
- Vault integration for credential rotation
- Automated replication and monitoring user password rotation

**Monitoring & Alerting:**
- Metrics history in Graphite format
- Graphite API integration
- Alerting via email, Pushover, Slack, Teams, Mattermost
- SLA tracking and reporting
- Performance capture during high load events

### Available Tags

**`version-pro`** (recommended for production)
- Includes all dependencies for database maintenance
- Ships with latest LTS MariaDB server
- Default orchestrator: `prov-orchestrator = "opensvc"`
- Supports all orchestration modes: OpenSVC, Kubernetes, SlapOS, on-premise

**`version`** (lightweight)
- Minimal dependencies for on-premise deployments
- Default orchestrator: `prov-orchestrator = "onpremise"`
- Smaller image size

**`nightly`**
- Latest development build from `develop` branch
- Includes all `pro` features
- Use for testing new features before release

**`dev`**
- Development environment with build tools
- No default entry point (manual container execution)
- For contributors building **replication-manager** from source

Example for development container:
```bash
docker run --user=124:132 \
           -e HOME=/go/src/github.com/signal18/replication-manager \
           --detach \
           --name=dev \
           --interactive \
           --tty \
           --volume=/home/dev/replication-manager:/go/src/github.com/signal18/replication-manager:rw \
           --volume=/home/dev/etc/replication-manager:/etc/replication-manager:rw \
           --volume=/home/dev/data:/var/lib/replication-manager:rw \
           docker.io/signal18/replication-manager:dev \
           /bin/bash
```

### Logs

Container logs are written to `/var/log/replication-manager.log` inside the container.


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
