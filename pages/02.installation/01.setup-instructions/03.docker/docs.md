---
title: Install from Docker
taxonomy:
    category: docs
---

## Install from Docker

Official Signal18 container images are available on [Docker Hub](https://hub.docker.com/r/signal18/replication-manager/). Images bundle all external dependencies so no separate tool installation is required on the host.

---

## Image Tags

| Tag | Flavor | Description |
|---|---|---|
| `3.1` | osc | Open Source edition. Minimal dependencies, `prov-orchestrator = "onpremise"`. |
| `3.1-pro` | pro | Production edition with full dependencies. `prov-orchestrator = "opensvc"`. Includes MariaDB client tools, ProxySQL, MyDumper, HAProxy, Restic, Sysbench, Grafana. |
| `nightly` | pro | Latest develop-branch build. For testing new features before release. |
| `dev` | — | Build environment with Go toolchain. No default entrypoint — used for building from source inside a container. |

Rootless variants (run as non-root user `repman`, UID/GID 10001:10001) are available with the `-rootless` suffix: `3.1-rootless`, `3.1-pro-rootless`.

---

## What is Included

**Standard image (`3.1`)** — minimal:
- `replication-manager-osc` binary
- `replication-manager-cli`
- `gotty-client` (terminal-in-browser)
- MariaDB repository configured (client tools not installed)

**Pro image (`3.1-pro`)** — full dependency set:

| Tool | Version | Purpose |
|---|---|---|
| `mariadb` / `mariadb-dump` / `mariadb-binlog` | 11.4 LTS | Database client, logical backups, binlog processing |
| `mariadb-plugin-spider` | 11.4 | Spider sharding proxy |
| `mydumper` / `myloader` | 0.17 | Fast parallel logical backup |
| `restic` | latest | Snapshot-based backup archiving |
| `haproxy` | distro | TCP/HTTP load-balancing proxy |
| `proxysql` | 2.7.3 | Advanced MySQL-protocol proxy |
| `sysbench` | distro | Benchmarking and load injection |
| `grafana` | 8.1 | Metrics dashboards |
| `gotty-client` | 1.10 | Browser terminal sessions |
| `openssh-client` | distro | Remote scripting via SSH |
| `fuse` | distro | Restic FUSE mount for backup browsing |

---

## Quick Start

### Step 1 — Create directory structure

```bash
mkdir -p ~/repman/etc/cluster.d ~/repman/data ~/repman/config
```

### Step 2 — Download configuration templates

```bash
# Global configuration
curl -o ~/repman/etc/config.toml \
  https://raw.githubusercontent.com/signal18/replication-manager/refs/heads/3.1/etc/config.toml

# Cluster configuration sample
curl -o ~/repman/etc/cluster.d/cluster1.toml.sample \
  https://raw.githubusercontent.com/signal18/replication-manager/refs/heads/3.1/etc/cluster.d/cluster1.toml.sample
```

### Step 3 — Configure your first cluster

Edit `~/repman/etc/cluster.d/cluster1.toml.sample`:

```toml
[my-cluster]
title = "my-cluster"
prov-orchestrator = "onpremise"
db-servers-hosts = "db1:3306,db2:3306"
db-servers-prefered-master = "db1:3306"
db-servers-credential = "root:my-password"
replication-credential = "repl:repl-password"
```

Rename the file to match the cluster name:

```bash
mv ~/repman/etc/cluster.d/cluster1.toml.sample ~/repman/etc/cluster.d/my-cluster.toml
```

### Step 4 — Start the container

```bash
docker run \
  -v ~/repman/etc:/etc/replication-manager:rw \
  -v ~/repman/data:/var/lib/replication-manager:rw \
  -v ~/repman/config:/root/.config/replication-manager:rw \
  -p 443:10005 \
  -p 80:10001 \
  --name replication-manager \
  --detach \
  signal18/replication-manager:3.1
```

> The third volume mount (`/root/.config/replication-manager`) is required in v3.x to persist dynamic configuration changes made via the API or GUI. Without it, changes are lost on container restart.

### Volume Mounts

| Host path | Container path | Contents |
|---|---|---|
| `~/repman/etc` | `/etc/replication-manager` | `config.toml` and `cluster.d/` |
| `~/repman/data` | `/var/lib/replication-manager` | Runtime data, metrics, backups |
| `~/repman/config` | `/root/.config/replication-manager` | Dynamic config (v3+ required) |

### Exposed Ports

| Port | Protocol | Description |
|---|---|---|
| `10001` | HTTP | Web interface (unencrypted) |
| `10005` | HTTPS | Web interface and REST API |
| `10002–10004` | TCP | Graphite & pickle API (optional) |

---

## Rootless Images

Rootless images run as the `repman` user (UID/GID 10001:10001). Before starting a rootless container, set ownership of the mounted directories:

```bash
sudo chown -R 10001:10001 ~/repman/etc ~/repman/data ~/repman/config
```

```bash
docker run \
  --user repman \
  -v ~/repman/etc:/etc/replication-manager:rw \
  -v ~/repman/data:/var/lib/replication-manager:rw \
  -v ~/repman/config:/root/.config/replication-manager:rw \
  -p 443:10005 \
  -p 80:10001 \
  --name replication-manager \
  --detach \
  signal18/replication-manager:3.1-rootless
```

---

## Development Container

The `dev` image includes Go 1.24 and build tooling. Use it to build replication-manager from source inside a container:

```bash
docker run \
  --user=124:132 \
  -e HOME=/go/src/github.com/signal18/replication-manager \
  --detach --interactive --tty \
  --name=repman-dev \
  -v /home/dev/replication-manager:/go/src/github.com/signal18/replication-manager:rw \
  -v /home/dev/etc/replication-manager:/etc/replication-manager:rw \
  -v /home/dev/data:/var/lib/replication-manager:rw \
  signal18/replication-manager:dev \
  /bin/bash
```

---

## Logs

Container logs are written to `/var/log/replication-manager.log` inside the container. To follow them:

```bash
docker exec replication-manager tail -f /var/log/replication-manager.log
```
