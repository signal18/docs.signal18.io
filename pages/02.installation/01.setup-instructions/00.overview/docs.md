---
title: Overview
taxonomy:
    category: docs
---

## Overview — Flavors and Binary Types

replication-manager is built from a single codebase with compile-time feature flags that produce several distinct **flavors**. Choose the flavor that matches your deployment model.

---

## Binary Flavors

| Flavor | Binary name | Description |
|---|---|---|
| **osc** | `replication-manager-osc` | Open Source Community — full HA monitoring, failover, switchover, proxy integration, backups, alerting. No cluster provisioning. Default orchestrator: `onpremise`. |
| **pro** | `replication-manager-pro` | Provisioning edition — everything in `osc` plus orchestrated cluster provisioning via OpenSVC, Kubernetes, or SlapOS. Default orchestrator: `opensvc`. |
| **arb** | `replication-manager-arb` | Arbitrator — lightweight split-brain arbitration service for replication-manager clustering. No monitoring, no GUI. |
| **tst** | `replication-manager-tst` | Testing edition — `osc` plus local bootstrap, benchmarking with Sysbench, and regression testing tooling. |
| **embedded** | `replication-manager` | Standalone binary with the full React dashboard and all static assets embedded. Portable — runs from any directory. |

For most on-premise deployments start with **osc**. Use **pro** when you need replication-manager to provision and manage the lifecycle of database containers through an orchestrator.

---

## Distribution Types

Each flavor is available in three distribution forms:

### Package (RPM / DEB)

Installed via the Signal18 repository. Uses system directories:

| Path | Contents |
|---|---|
| `/etc/replication-manager/` | Configuration files |
| `/usr/share/replication-manager/` | Static assets, dashboard, templates |
| `/var/lib/replication-manager/` | Runtime data, metrics, backups |
| `$HOME/.config/replication-manager/` | Dynamic config changes (v3+) |
| `/var/log/replication-manager.log` | Log file |

### Tarball (basedir)

Tarball releases use **basedir variants** (`-basedir` suffix) with static assets embedded in the archive. Self-contained under a single directory:

| Path | Contents |
|---|---|
| `/usr/local/replication-manager/etc/` | Configuration files |
| `/usr/local/replication-manager/share/` | Static assets |
| `/usr/local/replication-manager/data/` | Runtime data |

### Embedded Binary

The `replication-manager` binary (no suffix) bundles the dashboard, templates, and all assets directly inside the binary. Suitable for portable or container deployments — no separate asset directory required.

---

## Choosing a Flavor

```
Need cluster provisioning (Docker/Podman via OpenSVC or Kubernetes)?
  └─ replication-manager-pro

Monitoring and HA of existing clusters only?
  └─ replication-manager-osc

Running replication-manager in HA with split-brain protection?
  └─ replication-manager-arb  (one instance per availability zone)

Local testing, benchmarking, regression tests?
  └─ replication-manager-tst

Portable single-file deployment or Docker?
  └─ replication-manager  (embedded)
```

---

## Filesystem Hierarchy

### Package installations (RPM / DEB)

| Path | Contents |
|---|---|
| `/etc/replication-manager/` | Configuration files (`config.toml`, `cluster.d/`) |
| `/usr/share/replication-manager/` | Static assets, dashboard, templates |
| `/usr/share/replication-manager/dashboard/` | HTTP server root |
| `/var/lib/replication-manager/` | Runtime data, metrics, backups |
| `$HOME/.config/replication-manager/` | Dynamic config changes (v3+) |
| `/var/log/replication-manager.log` | Log file |

### Tarball installations

| Path | Contents |
|---|---|
| `/usr/local/replication-manager/etc/` | Configuration files |
| `/usr/local/replication-manager/share/` | Static assets |
| `/usr/local/replication-manager/data/` | Runtime data |

---

## CLI Client

**`replication-manager-cli`** is the command-line client. It communicates with the monitoring daemon over HTTPS using JWT authentication. It is bundled with all server packages but can be installed independently.
