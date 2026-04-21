---
title: Built-in Log Plugins
taxonomy:
    category: docs
---

## 11.2.1 Built-in Log Plugins

The four built-in plugins run **in-process** — they are Go functions registered in `logplugin.GlobalRegistry`, not external binaries. They share the same evaluation loop as external plugins but execute without spawning a subprocess.

Built-in plugins use **Graphite-backed spike detection**: a rolling baseline is maintained over a configurable window and a finding is raised when the current value exceeds `baseline + spike-sigma × σ`. This makes them adaptive to the normal workload of each individual server.

---

## 11.2.2 errorlog

**Source:** `cluster/logplugin/errorlog.go`

Analyses the MariaDB/MySQL error log for spikes in warning and error messages. Useful for catching sudden increases in replication errors, InnoDB warnings, authentication failures, and other server health indicators.

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `timeframe-hours` | `1` | Rolling window of error log lines to inspect |
| `min-log-level` | `Warning` | Minimum severity — only lines at or above this level are counted |
| `spike-sigma` | `2` | Standard deviations above rolling mean to trigger a finding |

**Min log level values:**

| Value | Lines included |
|---|---|
| `System` | Startup and shutdown messages only |
| `Note` | Informational and above |
| `Warning` | Warnings and errors *(default)* |
| `ERROR` | Errors only |

**Configuration example:**

```toml
[mycluster.plugin-config.errorlog]
enabled = true
timeframe-hours = 2
min-log-level = "Warning"
spike-sigma = 3
```

---

## 11.2.3 sqlerrorlog

**Source:** `cluster/logplugin/sqlerrorlog.go`

Analyses the SQL error log (the log of SQL statements that returned errors) for spikes. High error rates in the SQL log can indicate application bugs, connection pool exhaustion, or privilege misconfigurations.

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `timeframe-hours` | `1` | Rolling window of SQL error log lines |
| `spike-sigma` | `2` | Standard deviations above rolling mean |

---

## 11.2.4 slowlog

**Source:** `cluster/logplugin/slowlog.go`

Analyses the slow query log for spikes in slow query count and latency. A sudden spike indicates a query regression, a missing index being dropped, or an unexpected table scan caused by data growth.

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `timeframe-hours` | `1` | Rolling window of slow log entries |
| `spike-sigma` | `2` | Standard deviations above rolling mean |

**Note:** the slow query log must be enabled on the server (`slow_query_log = ON`, `long_query_time` set appropriately) for this plugin to receive data.

---

## 11.2.5 auditlog

**Source:** `cluster/logplugin/auditlog.go`

Analyses the MariaDB audit log for activity drift. Instead of simple spike detection, `auditlog` compares a **current window** against a longer **baseline window** to detect unusual shifts in the volume or pattern of audited operations. This catches slow-building anomalies that a pure spike detector would miss.

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `current-window-hours` | `1` | Recent audit log window for current activity baseline |
| `baseline-window-hours` | `24` | Historical window for expected activity comparison |
| `spike-sigma` | `2` | Standard deviations of drift to trigger a finding |

**Note:** requires the `server_audit` plugin to be loaded on the server (`server_audit_logging = ON`).

---

## 11.2.6 Spike Detection Algorithm

All four built-in plugins share the same underlying algorithm implemented in `cluster/logplugin/spike.go`:

1. Collect the metric (e.g. error count) from all log lines within `timeframe-hours`
2. Load the rolling history of that metric from Graphite
3. Compute `mean` and `σ` (standard deviation) over the history window
4. If `current_value > mean + spike_sigma × σ`: raise a finding
5. Store the current value back into Graphite for future baseline calculation

The Graphite storage is local to the replication-manager instance. No external Graphite server is required — replication-manager ships its own embedded metric store.

**Tuning `spike-sigma`:**

| Value | Sensitivity |
|---|---|
| `1.0` | Very sensitive — many false positives on bursty workloads |
| `2.0` | Balanced *(default)* — good for most production systems |
| `3.0` | Conservative — only fires on clear anomalies |
| `4.0+` | Very conservative — use on highly variable workloads |
