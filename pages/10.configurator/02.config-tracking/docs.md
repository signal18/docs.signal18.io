---
title: Config Tracking
taxonomy:
    category: docs
---

## 1. Config Tracking

The configurator does not just generate a one-time config archive — it continuously monitors the live database configuration and tracks **drift** between what replication-manager expects to be deployed and what is actually running. This lets you detect unplanned changes, preserve intentional deviations, and keep config in a known state across rolling restarts and node replacements.

---

## 2. Three-Layer Config System

Each server's data directory contains three override files that sit in `etc/mysql/custom.d/` inside the config archive (read after all tag-generated fragments):

```
{datadir}/{cluster}/{host_port}/
├── 01_preserved.cnf    server-specific locked values
├── 02_delta.cnf        calculated drift (expected vs deployed)
└── 03_agreed.cnf       manually accepted deviations
```

These files are not generated from tags — they represent the **state of the deployed server** relative to the configurator's expectations, and are managed by replication-manager automatically (with overrides you can set via API or GUI).

### 2.1 01_preserved.cnf — Server-specific locked values

Variables listed here are **frozen** for this server. The configurator will not attempt to change them during regeneration. Use this for server-specific paths, hardware-specific tuning, or any setting that legitimately differs from the rest of the cluster.

Example: a server with a different disk layout needs a custom `innodb_data_home_dir`:

```ini
[mysqld]
# Preserved — non-standard disk layout on this host
innodb_data_home_dir = /data2/mysql
```

### 2.2 02_delta.cnf — Calculated drift

Written automatically by replication-manager on every monitoring tick. It contains variables where the **deployed (on-disk) value differs from the expected (tag-generated) value**. This file tells you exactly what diverged and by how much.

The delta file has a 4-layer safety framework for writing runtime values:
1. Empty values are never written
2. Read-only variables (e.g. `version`, `have_ssl`) are excluded
3. Only whitelisted runtime-fallback variables are included
4. Failed servers are excluded to avoid partial state

If the delta is empty after a config regeneration and restart, the server is fully converged.

### 2.3 03_agreed.cnf — Manually accepted deviations

When a delta value is reviewed and accepted — either by an operator using the GUI, or via the API — the variable moves from `02_delta.cnf` to `03_agreed.cnf`. This signals that the deviation is **known and intentional**, not drift to be remediated.

Accepting a variable via API:

```
POST /api/clusters/{clusterName}/servers/{serverName}/actions/set-variable-accepted
Body: {"variable": "MAX_CONNECTIONS"}
```

---

## 3. Cluster-Wide Preserved Variables

In addition to per-server preserved files, you can define **cluster-wide** variables that must be preserved across all servers. These are stored in:

```
{working-dir}/{cluster-name}/preserved_variables.cnf
```

This file is readable and writable via the API:

```
GET  /api/clusters/{clusterName}/preserved-vars-cnf
POST /api/clusters/{clusterName}/preserved-vars-cnf
```

The file format is a my.cnf-style `[mysqld]` section, with optional per-server exclusions:

```ini
[mysqld]
# Variables preserved cluster-wide during config regeneration

# This variable will be preserved on all servers
MAX_CONNECTIONS = 500

# This variable will be preserved on all servers EXCEPT the listed ones
# INNODB_BUFFER_POOL_SIZE = 4096 # exclude: server1,server2
```

Changes written via the POST endpoint are applied immediately without a restart — replication-manager reloads preserved variables automatically.

---

## 4. Configuration Keys

Two config flags control preserved variable behavior during config tar.gz generation:

##### `prov-db-config-preserve`

| | |
|---|---|
| Description | Copy the preserved variables into the generated `config.tar.gz`. When `true` (default), `01_preserved.cnf`, `02_delta.cnf`, and `03_agreed.cnf` are included in `custom.d/` inside the archive so the next init-container launch picks them up. Set to `false` to get a clean config from tags only. |
| Type | Boolean |
| Default | `true` |

##### `prov-db-config-preserve-vars`

| | |
|---|---|
| Description | Semicolon-separated list of variable names (or `name=value` pairs) to add to `01_preserved.cnf` at every regeneration. Use this to hard-code values that must survive any config update. Example: `innodb_data_home_dir=/var/lib/mysql;max_connections=1000` |
| Type | String |
| Default | `""` |

---

## 5. Monitoring Config Changes

replication-manager detects when the deployed config changes on disk (checksum comparison). When a change is detected:

1. Preserved variables are reloaded from `preserved_variables.cnf`
2. The delta file (`02_delta.cnf`) is recalculated for the affected server
3. A log entry is written at INFO level

This happens within the normal monitoring loop interval (`monitoring-ticker`), so config drift is detected quickly without polling overhead.

---

## 6. Reading Variables from Config Files

Before the database is running (e.g., during provisioning or after a crash), replication-manager can read configuration values directly from the deployed `.cnf` files rather than from `SHOW VARIABLES`. This allows it to know the intended configuration even for offline servers.

The read order mirrors MySQL's include chain:
1. Main `my.cnf` with `includedir` directives
2. `conf.d/` fragments (tag-generated)
3. `custom.d/` overrides (preserved, delta, agreed)

Variables read this way are used for topology decisions, failover calculations, and provisioning validation.
