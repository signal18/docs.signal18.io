---
title: Logs
taxonomy:
    category: docs
---

## 1. Logs

replication-manager uses a multi-tier logging system built on [Logrus](https://github.com/sirupsen/logrus). Every message is written simultaneously to one or more destinations: a rotating log file, journald (via stdout), the web interface, and optionally syslog or alert channels such as Slack.

---

## 2. Log Files Written

### 2.1 Main Log

Set by `log-file`. All cluster monitoring, failover, switchover, and API activity is written here.

```toml
log-file = "/var/log/replication-manager.log"
```

### 2.2 Derived Logs

When `log-file` is set, three specialised logs are created automatically beside it by appending a suffix to the base name:

| File | Suffix | Content |
|---|---|---|
| Main log | *(base)* | General monitoring, HA operations, API |
| Security log | `-security` | Authentication, authorisation, credential access |
| Workload log | `-workload` | Performance spikes, slow query events |
| Maintenance log | `-maintenance` | Backups, SST, tasks, purge, orchestrator provisioning |

Example: if `log-file = /var/log/replication-manager.log` then:

```
/var/log/replication-manager.log
/var/log/replication-manager-security.log
/var/log/replication-manager-workload.log
/var/log/replication-manager-maintenance.log
```

### 2.3 Per-Cluster SQL Logs

Two SQL logs are written per cluster under the monitoring data directory:

```
{monitoring-datadir}/{cluster-name}/sql-error.log   — SQL errors returned by the database
{monitoring-datadir}/{cluster-name}/sql-general.log — All statements executed by replication-manager
```

The default `monitoring-datadir` is `/var/lib/replication-manager`.

### 2.4 Panic Log

If replication-manager panics, a JSON-formatted stacktrace is written to:

```
{monitoring-datadir}/panic.log
```

### 2.5 Carbon / Graphite API Log

When the embedded Graphite backend is enabled:

```
{monitoring-datadir}/carbonapi.log
```

---

## 3. Log Levels

Log verbosity is controlled with integers. A lower number means fewer, more severe messages; a higher number means more verbose output.

| Level | Value | Meaning |
|---|---|---|
| ERROR | 1 | Errors only |
| WARN | 2 | Errors and warnings |
| INFO | 3 | Normal operational output (default) |
| DEBUG | 4 | Full diagnostic output |

The global level applies to all output unless overridden per module:

```toml
log-level = 3
```

---

## 4. Per-Module Log Levels

Every subsystem has its own level key. Set any module to `4` (DEBUG) when diagnosing a specific area without flooding the rest of the log.

```toml
# Core operations
log-level-topology       = 2    # Topology discovery and changes
log-level-heartbeat      = 1    # Heartbeat monitoring
log-level-writer-election = 1   # Primary election process

# Config and Git
log-level-config-load    = 2    # Config file merging and reload
log-level-git            = 2    # Git config backup operations

# Backups
log-level-backup-stream  = 4    # Streaming backup verbosity
log-level-restic         = 3    # Restic backup operations
log-level-sst            = 1    # State Transfer (SST / IST)

# Proxies
# (set in the cluster section — no global key, use log-level)

# Orchestrator and provisioning
log-level-orchestrator   = 2    # Provisioning orchestrator calls

# Tasks and maintenance
log-level-task           = 3    # Scheduled task execution

# Database log forwarding
log-level-database-errors    = 1   # MariaDB/MySQL error log lines
log-level-database-sql-errors = 1  # SQL errors from general_log
log-level-database-slowquery  = 2  # Slow query log lines
log-level-database-audit      = 3  # Audit plugin events
log-level-database-optimize   = 4  # Optimizer trace

# Alerting
log-level-mailer         = 3    # Email alert delivery
log-level-support        = 2    # Support bundle creation

# Other
log-level-external-script = 3  # External script execution
log-level-stats          = 1    # Internal statistics collection
log-level-vault          = 1    # HashiCorp Vault operations
log-level-graphite       = 2    # Graphite/Carbon metrics
log-level-binlog-purge   = 1    # Binary log purge operations
log-level-sql            = 2    # SQL statements executed by repman

# Plugin log tailer
log-plugin               = false  # Enable log tailer plugins
log-level-plugin         = 2      # Plugin verbosity
```

---

## 5. Log Message Format

Each log line carries a timestamp, cluster name, module tag, level, and message:

```
2006/01/02 15:04:05 [mycluster] [heartbeat] INFO  - Primary heartbeat received
2006/01/02 15:04:06 [mycluster] [topology]  WARN  - Replica 10.0.1.11:3306 lag > threshold
2006/01/02 15:04:07 [mycluster] [general]   STATE - OPENED ERR00076: Lost primary
```

**Level strings**:

| String | Meaning |
|---|---|
| `ERROR` | Hard error |
| `WARN` | Warning |
| `INFO` | Informational |
| `DEBUG` | Diagnostic detail |
| `STATE` | Cluster state change (`OPENED` / `RESOLV` prefix) |
| `ALERT` | Critical operational alert |
| `ALERTOK` | Alert cleared |
| `START` | Service start / bootstrap message |
| `TEST` | Regression test output |
| `BENCH` | Benchmark output |

**Module tags** appearing in log lines:

| Tag | Area |
|---|---|
| `general` | General monitoring |
| `topology` | Topology discovery |
| `heartbeat` | Heartbeat checks |
| `election` | Writer election |
| `sst` | State Transfer |
| `backup` | Backup streaming |
| `restic` | Restic operations |
| `task` | Task / job execution |
| `maintenance` | Maintenance operations |
| `proxy` | Generic proxy operations |
| `proxysql` | ProxySQL |
| `haproxy` | HAProxy |
| `maxscale` | MaxScale |
| `orchestrator` | Provisioning orchestrator |
| `conf` | Config load and merge |
| `git` | Git backup |
| `vault` | HashiCorp Vault |
| `graphite` | Graphite metrics |
| `purge` | Binlog purge |
| `mailer` | Email alerting |
| `support` | Support bundles |
| `external-script` | External scripts |
| `stats` | Internal statistics |
| `sql` | SQL statements |
| `errorlog` | Database error log |
| `slowquery` | Database slow query log |
| `auditlog` | Database audit log |
| `sqlerrorlog` | Database SQL errors |
| `optimize` | Database optimizer trace |
| `plugin` | Log tailer plugins |
| `app` | Application-level |

---

## 6. Log Rotation

Rotation is handled by [lumberjack](https://github.com/natefinch/lumberjack). All file-based logs (main, security, workload, maintenance, SQL) rotate automatically.

```toml
log-rotate-max-size   = 5    # Rotate when file reaches this size in MB
log-rotate-max-backup = 7    # Number of rotated files to keep
log-rotate-max-age    = 7    # Days before rotated files are deleted
```

Rotated files are gzip-compressed automatically. A daily forced rotation also runs via the internal scheduler.

---

## 7. Syslog

To forward all log entries to the local syslog daemon (rsyslog, syslog-ng, etc.):

```toml
log-syslog = true
```

replication-manager connects to `localhost:514` over UDP at `LOG_INFO` severity. The syslog hook is additive — file and syslog output both receive messages.

---

## 8. Viewing Logs on a Modern Linux System

### 8.1 journalctl — follow in real time

```bash
# Follow the main service journal
journalctl -u replication-manager -f

# Follow and show the last 100 lines
journalctl -u replication-manager -n 100 -f
```

### 8.2 journalctl — query by time

```bash
# Last hour
journalctl -u replication-manager --since "1 hour ago"

# Specific window
journalctl -u replication-manager --since "2024-06-01 00:00" --until "2024-06-01 06:00"

# Since last boot
journalctl -u replication-manager -b
```

### 8.3 journalctl — filter by priority

journald maps Logrus levels to syslog priorities automatically when replication-manager logs to stdout:

```bash
# Errors only
journalctl -u replication-manager -p err

# Warnings and above
journalctl -u replication-manager -p warning

# Full debug output
journalctl -u replication-manager -p debug
```

### 8.4 journalctl — output formats

```bash
# JSON output — pipe to jq
journalctl -u replication-manager -o json | jq '.MESSAGE'

# Short with microseconds
journalctl -u replication-manager -o short-precise

# Export to file
journalctl -u replication-manager --since today > /tmp/repman-today.log
```

### 8.5 journalctl — search inside messages

```bash
# Find all failover events
journalctl -u replication-manager -g "failover"

# Find specific cluster
journalctl -u replication-manager -g "\[mycluster\]"

# Find STATE changes
journalctl -u replication-manager -g "STATE"

# Find a specific error code
journalctl -u replication-manager -g "ERR00076"
```

### 8.6 Tailing the log file directly

If `log-file` is configured:

```bash
# Follow main log
tail -f /var/log/replication-manager.log

# Follow only security events
tail -f /var/log/replication-manager-security.log

# Follow maintenance/backup activity
tail -f /var/log/replication-manager-maintenance.log

# Filter a module in real time
tail -f /var/log/replication-manager.log | grep '\[topology\]'

# Filter an error code
tail -f /var/log/replication-manager.log | grep 'ERR0'

# Filter STATE changes only
tail -f /var/log/replication-manager.log | grep 'STATE'
```

### 8.7 Searching historical logs

```bash
# All ERR codes in the last 7 days of rotated logs
zgrep 'ERR0' /var/log/replication-manager.log* | sort

# All STATE OPENED events
zgrep 'OPENED' /var/log/replication-manager.log*

# Failover events across all rotated files
zgrep -h 'failover' /var/log/replication-manager.log* | sort -k1,2
```

---

## 9. Accessing Logs via the REST API

In-memory log buffers (last ~200 entries per cluster) are available through the API without reading the log files:

| Endpoint | Content |
|---|---|
| `GET /api/clusters/{name}/log` | General cluster log |
| `GET /api/clusters/{name}/log-task` | Task and backup log |
| `GET /api/clusters/{name}/log-security` | Security events |
| `GET /api/clusters/{name}/log-workload` | Workload spikes |

These endpoints require authentication. Use the admin or dba credential:

```bash
curl -sk -u admin:repman \
  https://localhost:10005/api/clusters/mycluster/log | jq .
```

---

## 10. Temporarily Increasing Verbosity

Bump a specific module to DEBUG without restarting — set the level via the API or edit the config and reload:

```toml
# Investigate a topology issue
log-level-topology = 4

# Trace a backup problem
log-level-backup-stream = 4
log-level-restic        = 4

# Debug SST / IST
log-level-sst = 4
```

After diagnosing, drop the levels back to their defaults to reduce log volume.

---

## 11. Diagnostic Quick Reference

| Goal | Command |
|---|---|
| Follow all logs | `journalctl -u replication-manager -f` |
| Find switchovers | `journalctl -u replication-manager -g "switchover"` |
| Find failovers | `journalctl -u replication-manager -g "failover"` |
| Find all ERR codes | `journalctl -u replication-manager -g "ERR0"` |
| Find WARN codes | `journalctl -u replication-manager -g "WARN0"` |
| Find state changes | `journalctl -u replication-manager -g "STATE"` |
| Topology module only | `journalctl -u replication-manager -g "\[topology\]"` |
| Security events | `tail -f /var/log/replication-manager-security.log` |
| Backup / maintenance | `tail -f /var/log/replication-manager-maintenance.log` |
| Slow SQL | `tail -f /var/log/replication-manager-workload.log` |
| SQL executed by repman | `tail -f {datadir}/{cluster}/sql-general.log` |
| Panic stacktrace | `cat {datadir}/panic.log \| jq .` |
