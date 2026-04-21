---
title: Plugins Overview
taxonomy:
    category: docs
---

## 2.7.1.1 Plugins Overview

> **Available since:** replication-manager **v3.1.24**

replication-manager extends its monitoring capabilities through a plugin system. On every monitoring tick each enabled plugin is invoked, receives a full snapshot of the monitored server state as JSON on stdin, and writes findings and score checks as JSON on stdout.

Plugins are organised in three tiers:

| Tier | Who can use it | Requires | Examples |
|---|---|---|---|
| **Static** | Everyone | Nothing — bundled in the binary | `errorlog`, `slowlog`, `auditlog` |
| **Community** | Registered instances | Free account at gitlab.signal18.io | All workload, security, and score plugins |
| **Enterprise** | Support contract customers | Signal18 Support Contract | `plugin-critical-alerts` |

**Static plugins** run in-process as Go functions. They use Graphite-backed spike detection and need no registration.

**Community plugins** are external binaries distributed to registered instances. Registering your instance unlocks the full community library — workload anomaly detection, security auditing, and scoring — and keeps them updated automatically. See [Registration & SSO](../registration) for how to register.

**Enterprise plugins** are developed from Signal18's field experience and are available to customers with an active Support Contract.

---

## 2.7.1.2 Enabling Plugins

The plugin subsystem is controlled by two global settings:

```toml
[Default]
log-plugin       = true   # master switch — enable external plugin evaluation
log-level-plugin = 0      # verbosity: 0=off … 4=debug
```

Individual plugins are enabled per cluster:

```toml
[mycluster.plugin-config.plugin-connection-storm]
enabled = true
sleep-ratio-threshold = 0.75
```

---

## 2.7.1.3 What Community Plugins Do

### 2.7.1.3.1 Workload Plugins

Detect operational anomalies — performance problems, resource saturation, and regressions — by analysing the real-time server state. Findings carry `WARN` codes routed to the main HA log.

| Plugin | Finds |
|---|---|
| `plugin-innodb-corruption` | InnoDB corruption indicators in error log (WARN0300) |
| `plugin-slow-query-regression` | Query latency regressed vs PFS baseline (WARN0301) |
| `plugin-error-storm` | Repeated error template spike (WARN0302) |
| `plugin-replication-lag-predictor` | DML write burst predicting future lag (WARN0303) |
| `plugin-full-table-scan-spike` | Full-scan ratio exceeded (WARN0304) |
| `plugin-metadata-lock-contention` | MDL wait duration or count exceeded (WARN0305) |
| `plugin-tmp-table-storm` | On-disk temporary table spike (WARN0306) |
| `plugin-connection-storm` | Sleep ratio or lock-wait count exceeded (WARN0307) |
| `plugin-privilege-escalation` | Privilege DDL by non-admin account (WARN0308) |
| `plugin-off-hours-access` | DB access outside business hours (WARN0309) |
| `plugin-binlog-cleartext-password` | Cleartext password in binlog (WARN0310) |
| `plugin-binlog-creditcard-leak` | Credit card PAN detected in binlog (WARN0311) |

### 2.7.1.3.2 Security Plugins

Audit database configuration, user accounts, and activity logs for security weaknesses. Findings carry `SEC` codes and feed the **remediation engine** — many can be auto-fixed.

| Plugin | Finds |
|---|---|
| `plugin-security-no-password-user` | Account with empty password (SEC0100) |
| `plugin-security-weak-auth` | Weak or deprecated auth plugin (SEC0101) |
| `plugin-security-local-infile` | `local_infile=ON` (SEC0102) |
| `plugin-security-hardening` | CIS Benchmark controls SEC0103–SEC0118 |

### 2.7.1.3.3 Score Plugins

Compute binary pass/fail checks that feed the **SecurityScore** gauge in the cluster dashboard.

| Plugin | Scores |
|---|---|
| `plugin-score-ssl` | TLS/SSL enabled and configured |
| `plugin-score-encryption` | Table, binlog, and tmp encryption at rest |
| `plugin-score-auth` | Strong authentication plugins in use |
| `plugin-score-passwords` | Password validation plugins active |
| `plugin-score-audit` | Audit logging active |
| `plugin-score-network` | Network security controls |
| `plugin-score-lts` | Running a supported LTS version |
| `plugin-score-proxy` | Proxy layer present |

---

## 2.7.1.4 Full Plugin Reference

For wire protocol, configuration, prerequisites, remediation engine, and developer guide see the **[Plugins chapter](../../../plugins)**.
