---
title: Logs and Troubleshooting
taxonomy:
    category: docs
---

## 1. Logs and Troubleshooting

replication-manager writes several log files and diagnostic files. Knowing where each one lives and what it contains is the first step when investigating unexpected behaviour.

---

## 2. replication-manager Daemon Log

The main process log captures everything replication-manager does: topology discovery, failover decisions, API calls, job execution, and internal errors.

| Setting | Effect |
|---|---|
| `log-file` | Path to write the daemon log. Defaults to stdout if not set. Example: `/var/log/replication-manager.log` |
| `log-level` | Verbosity from 1 (errors only) to 7 (full monitoring loop trace). Levels above 3 print every monitoring tick and are intended for debugging only. |
| `log-syslog` | Mirror all log output to the local UDP syslog port in addition to the log file. Useful for feeding Graylog, Splunk, or ELK without a file agent. |
| `log-rotate-max-age` | Rotate logs older than this many days. Default: `7`. |
| `log-rotate-max-backup` | Keep at most this many rotated log files. Default: `7`. |
| `log-rotate-max-size` | Rotate when the log file exceeds this size in MB. Default: `5`. |
| `verbose` | Add extra debugging output on top of `log-level`. |

All of these are set in the global (non-cluster) section of the configuration file. See [Daemon Monitoring](../01.configuration-guide/00.server) for the full parameter reference.

### 2.1 Typical log-level guide

| Level | When to use |
|---|---|
| `1` | Production — errors and critical events only |
| `2` | Default — normal operations including state changes |
| `3` | Failover debugging — shows decision logic |
| `5–7` | Full trace — every SQL sent to monitored servers, every monitoring tick |

---

## 3. Per-Cluster Diagnostic Files

These files are written inside the replication-manager data directory, one subdirectory per cluster:

```
{monitoring-datadir}/{cluster-name}/
├── sql_general.log      All SQL sent to backend servers during monitoring
├── capture_*.log        Snapshot dumps triggered by error codes
└── {host}_{port}/
    └── ...              Per-server config and state files
```

### 3.1 sql_general.log

Enabled by `log-sql-in-monitoring = true`. Records every SQL query that replication-manager sends to monitored servers (SHOW SLAVE STATUS, SHOW GLOBAL STATUS, etc.).

This log is valuable during failover and rejoin analysis: you can replay exactly what replication-manager queried and in what order to reconstruct the decision path.

```toml
log-sql-in-monitoring = true
```

### 3.2 Capture files

When `monitoring-capture` is enabled, replication-manager automatically dumps a diagnostic snapshot whenever a monitored error code fires. Each capture saves:

- `SHOW SLAVE STATUS`
- `SHOW PROCESSLIST`
- `SHOW GLOBAL STATUS`
- `SHOW ENGINE INNODB STATUS`

Capture is triggered by the error codes listed in `monitoring-capture-trigger` (default: `ERR00076,ERR00041` — max connections threshold and slave delay). The dump runs for 5 monitoring ticks and the oldest files are purged once the count exceeds `monitoring-capture-file-keep`.

| Setting | Default | Effect |
|---|---|---|
| `monitoring-capture` | `true` | Enable automatic capture on error |
| `monitoring-capture-trigger` | `"ERR00076,ERR00041"` | Comma-separated error codes that start a capture |
| `monitoring-capture-file-keep` | `5` | Maximum number of capture files to retain |

---

## 4. Memory Profiling

```toml
memprofile = "/tmp/repmgr.mprof"
```

When set, replication-manager writes a Go memory profile to this path. Load it with `go tool pprof` to investigate memory growth:

```bash
go tool pprof /tmp/repmgr.mprof
```

---

## 5. Common Troubleshooting Steps

### 5.1 Replication-manager won't connect to a server

1. Set `log-level = 5` temporarily and restart
2. Watch the daemon log for the connection attempt and error
3. Verify `db-servers-hosts`, `db-servers-credential`, and network access
4. Check that `log-sql-in-monitoring = true` to see the exact queries being sent

### 5.2 Failover triggered unexpectedly

1. Check the daemon log around the time of the event — look for the `ERR` code that fired
2. Check the capture files in `{monitoring-datadir}/{cluster-name}/` — they contain the exact server state at the moment of the error
3. Look at `monitoring-capture-trigger` to see which codes arm a capture

### 5.3 Alert firing but no notification received

1. Verify the error code is in `monitoring-alert-trigger` (see [Alerting Configuration Guide](../../08.alerting/01.configuration-guide))
2. Check if the code is in `monitoring-ignore-errors` (suppression list)
3. Check if `scheduler-alert-disable` is active (scheduled blackout)
4. Set `log-level = 3` to see alert dispatch in the daemon log

### 5.4 High log volume

- Lower `log-level` back to `1` or `2` for production
- Enable `log-rotate-max-size` to cap file growth
- Use `log-syslog` with a log aggregator that handles volume and retention externally
