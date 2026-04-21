---
title: What Was Installed
taxonomy:
    category: docs
---

## 1. What Was Installed

This page describes the files and directories created by each installation method.

---

## 2. Package Installation (RPM / DEB)

Installing via the Signal18 repository creates the following layout:

| Path | Contents |
|---|---|
| `/etc/replication-manager/config.toml` | Main configuration file (template on first install) |
| `/etc/replication-manager/cluster.d/` | Per-cluster TOML files (included via `include =` directive) |
| `/usr/share/replication-manager/` | Static assets, dashboard, templates, and test files |
| `/usr/share/replication-manager/dashboard/` | HTTP server root — the React web GUI |
| `/usr/share/replication-manager/tests/` | Non-regression test files and example MariaDB config files |
| `/var/lib/replication-manager/` | Runtime data: metrics, backup state, bootstrap data |
| `$HOME/.config/replication-manager/` | Dynamic config changes saved via API or GUI (v3+) |
| `/var/log/replication-manager.log` | Log file |
| `/usr/bin/replication-manager` | Server binary |
| `/usr/bin/replication-manager-cli` | CLI client binary |

The `/etc/replication-manager/` directory is the **template** source. On first startup, v3 copies it to `$HOME/.config/replication-manager/` which becomes the **active** configuration. Changes made via the API or GUI are written to the active directory, not back to `/etc/`.

---

## 3. Tarball Installation (basedir)

Tarball releases extract to a self-contained directory tree:

| Path | Contents |
|---|---|
| `/usr/local/replication-manager/etc/` | Configuration files and samples |
| `/usr/local/replication-manager/share/` | Static assets and dashboard |
| `/usr/local/replication-manager/share/dashboard/` | React web GUI |
| `/usr/local/replication-manager/data/` | Runtime data |
| `/usr/local/replication-manager/bin/replication-manager` | Server binary |
| `/usr/local/replication-manager/bin/replication-manager-cli` | CLI client binary |

A symlink is typically created:

```bash
ln -s /usr/local/replication-manager-osc-3.1.24 /usr/local/replication-manager
```

---

## 4. Embedded Binary

The embedded binary installs as a single file. All assets (dashboard, templates, scripts) are compiled into the binary itself — no `share/` directory is created. The working directories are created at first run:

| Path | Contents |
|---|---|
| `/usr/local/bin/replication-manager` | The binary (assets embedded) |
| `$HOME/.config/replication-manager/` | Dynamic configuration (created at first start) |

Configuration and data directory locations can be overridden:

```toml
[Default]
monitoring-datadir  = "/var/lib/replication-manager"
monitoring-sharedir = "/usr/share/replication-manager"
```

---

## 5. Docker

Inside the container the layout matches the package installation:

| Container path | Description |
|---|---|
| `/etc/replication-manager/config.toml` | Template configuration (from image) |
| `/etc/replication-manager/cluster.d/` | Cluster configs (mount from host) |
| `/var/lib/replication-manager/` | Runtime data (mount from host) |
| `/root/.config/replication-manager/` | Active dynamic config (mount from host, v3+) |
| `/var/log/replication-manager.log` | Log file |
| `/usr/bin/replication-manager` | Server binary |
| `/usr/bin/replication-manager-cli` | CLI client binary |
| `/usr/share/replication-manager/` | Static assets |

All three directories should be mounted from the host so that data survives container restarts. See [Install from Docker](../docker) for the full `docker run` command.

---

## 6. Configuration File Search Order

At startup replication-manager searches for `config.toml` in this order:

1. Path specified by `--config` flag
2. `$HOME/.config/replication-manager/config.toml`
3. `/etc/replication-manager/config.toml`
4. `/usr/local/replication-manager/etc/config.toml`
5. `./config.toml` (current directory)

The first file found wins. The `--config` flag is the recommended way to specify a non-default location.
