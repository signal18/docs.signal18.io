---
title: First Config
taxonomy:
    category: docs
---

## 1. First Config

replication-manager uses a TOML configuration file. The default file is `config.toml`, split into a mandatory `[Default]` section and one or more named cluster sections.

---

## 2. Config File Structure

```toml
[Default]
# Global settings — applied to all clusters
monitoring-save-config = true
include = "/etc/replication-manager/cluster.d"

[cluster1]
# Settings for this cluster only
title                       = "cluster1"
prov-orchestrator           = "onpremise"
db-servers-hosts            = "10.0.1.10:3306,10.0.1.11:3306"
db-servers-prefered-master  = "10.0.1.10:3306"
db-servers-credential       = "root:password"
replication-credential      = "repl_user:repl_password"
```

**`[Default]`** is required. Variables placed here are enforced across all clusters — use it for instance-level settings like data directories, log paths, API credentials, and the `include` pointer. Most operational settings should go in a named cluster section so they can differ per cluster.

A single `[Default]` section with no separate cluster section is valid for single-cluster deployments.

---

## 3. Config File Search Order

replication-manager searches for `config.toml` in this order:

1. Path specified by `--config` flag
2. `$HOME/.config/replication-manager/config.toml`
3. `/etc/replication-manager/config.toml`
4. `/usr/local/replication-manager/etc/config.toml`
5. `./config.toml` (current directory)

The first file found wins.

---

## 4. Using the Include Directory

For multi-cluster setups, split configuration across individual files:

**`/etc/replication-manager/config.toml`** — global settings only:

```toml
[Default]
monitoring-save-config = true
include = "/etc/replication-manager/cluster.d"
http-server = true
http-bind-address = "0.0.0.0"
```

**`/etc/replication-manager/cluster.d/production.toml`** — one file per cluster:

```toml
[production]
title                       = "Production"
prov-orchestrator           = "onpremise"
db-servers-hosts            = "db1:3306,db2:3306,db3:3306"
db-servers-prefered-master  = "db1:3306"
db-servers-credential       = "root:prod-password"
replication-credential      = "repl:repl-password"
```

All `*.toml` files in the include directory are merged with the main config at startup.

---

## 5. Dynamic Configuration

When `monitoring-save-config = true` is set in `[Default]`, changes made via the API or GUI are persisted to the **active configuration directory**:

```
$HOME/.config/replication-manager/<cluster-name>/config.toml
```

This is separate from `/etc/replication-manager/` so the template config is never overwritten by runtime changes.

---

## 6. Minimal Working Config

```toml
[Default]
monitoring-save-config = true
http-server            = true
http-bind-address      = "0.0.0.0"
api-credentials        = "admin:change-me,dba:change-me"

[mycluster]
title                       = "mycluster"
prov-orchestrator           = "onpremise"
db-servers-hosts            = "db1:3306,db2:3306"
db-servers-prefered-master  = "db1:3306"
db-servers-credential       = "root:db-password"
replication-credential      = "repl:repl-password"
```

Change `api-credentials` before first start to avoid the [WARN0108 default password warning](../first-login#default-password-warning--warn0108).

---

## 7. Sample Configuration Files

Package installations include ready-to-use samples:

```bash
# Package installation — list available samples
ls /etc/replication-manager/*.sample*

# Copy a master-replica + HAProxy sample
cp /etc/replication-manager/config.toml.sample.masterslave-haproxy \
   /etc/replication-manager/config.toml
```

```bash
# Tarball installation
cp /usr/local/replication-manager/etc/config.toml.sample.masterslave-haproxy \
   /usr/local/replication-manager/etc/config.toml
```

---

## 8. Starting replication-manager

```bash
# Package / systemd
systemctl enable --now replication-manager

# Tarball
/usr/local/replication-manager/bin/replication-manager monitor \
  --config /usr/local/replication-manager/etc/config.toml \
  --http-server

# Embedded binary — reads config.toml from current directory or ~/.config
replication-manager monitor --http-server
```

---

## 9. Command Line Flags

Every configuration key has a corresponding command line flag. Flags on the command line take priority over the config file.

The `--cluster` flag restricts monitoring to a subset of the clusters defined in the config file:

```bash
replication-manager monitor --cluster production,staging
```

Key startup flags:

| Flag | Default | Description |
|---|---|---|
| `--config` | `/etc/replication-manager/config.toml` | Path to the configuration file |
| `--cluster` | *(all)* | Comma-separated list of cluster sections to load |
| `--http-server` | `false` | Start the HTTP/HTTPS web interface |
| `--monitoring-datadir` | `/var/lib/replication-manager` | Runtime data directory |
| `--monitoring-confdir` | `/etc/replication-manager` | Configuration directory |
| `--monitoring-sharedir` | `/usr/share/replication-manager` | Static assets directory |
| `--monitoring-save-config` | `true` | Persist API/GUI changes to datadir |
| `--api-credentials` | `admin:repman` | REST API users in `user:password` format |
| `--api-port` | `10005` | HTTPS API and web GUI port |

> Command line flags are overridden by variables found in the `config.toml` file — the config file takes precedence over flags.

---

## 10. Discovering All Available Flags

> **Tip:** `replication-manager monitor --help` is the authoritative reference — it prints all 400+ flags with defaults and descriptions for the exact version you have installed, including anything added after this documentation was written.

```bash
replication-manager monitor --help
```

Each flag name maps directly to a TOML key — strip the `--` prefix:

```bash
# Flag form:         --monitoring-ticker 2
# TOML equivalent:  monitoring-ticker = 2
```

Filter by feature area with `grep`:

```bash
replication-manager monitor --help | grep backup
replication-manager monitor --help | grep proxy
replication-manager monitor --help | grep failover
replication-manager monitor --help | grep ssl
```
