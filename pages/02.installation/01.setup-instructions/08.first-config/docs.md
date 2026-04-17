---
title: First Config
taxonomy:
    category: docs
---

## First Config

replication-manager uses a TOML configuration file. The default file is `config.toml`, searched in the locations described in [What Was Installed](../what-was-installed). The file is split into a mandatory `[Default]` section and one or more named cluster sections.

---

## Config File Structure

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

A single `[Default]` section with no separate cluster section is valid for single-cluster deployments — replication-manager will treat the Default section as the cluster definition.

---

## Using the Include Directory

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

## Dynamic Configuration

When `monitoring-save-config = true` is set in `[Default]`, changes made via the API or GUI are persisted to the **active configuration directory**:

```
$HOME/.config/replication-manager/<cluster-name>/config.toml
```

This directory is separate from `/etc/replication-manager/` so that the template config is never overwritten by runtime changes.

---

## Sample Configuration Files

Package installations include ready-to-use samples:

```bash
# Package installation
ls /etc/replication-manager/

# Use a master-slave + HAProxy sample
cp /etc/replication-manager/config.toml.sample.masterslave-haproxy \
   /etc/replication-manager/config.toml
```

```bash
# Tarball installation
cp /usr/local/replication-manager/etc/config.toml.sample.masterslave-haproxy \
   /usr/local/replication-manager/etc/config.toml
```

---

## Minimal Working Config (copy-paste start)

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

Change `api-credentials` before first start to avoid the [WARN0108 default password warning](../../../first-login#default-password-warning--warn0108).

---

## Starting replication-manager

```bash
# Package / systemd
systemctl enable --now replication-manager

# Tarball
/usr/local/replication-manager/bin/replication-manager monitor \
  --config /usr/local/replication-manager/etc/config.toml \
  --http-server

# Embedded binary
replication-manager monitor --http-server
```

After starting, open the web GUI at `https://<host>:10005` and follow the [First Login](../../../first-login) guide to change default passwords and register your instance.
