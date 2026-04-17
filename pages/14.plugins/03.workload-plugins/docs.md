---
title: Workload Plugins
taxonomy:
    category: docs
---

## Workload Plugins

> **Tier:** Community — requires a free registered instance at gitlab.signal18.io

Workload plugins detect **operational anomalies** — performance problems, resource saturation, and regressions — by analysing the real-time server state passed through the wire protocol. They emit findings with `WARN` error keys that are routed to the main HA log and can trigger alerts.

All workload plugins are **external binaries** located in the cluster plugins directory.

---

## plugin-innodb-corruption

**Binary:** `plugin-innodb-corruption`  
**Finding:** `WARN0300`  
**Source:** `cluster/logplugin/plugins/plugin-innodb-corruption/main.go`

Scans the error log for InnoDB corruption indicators within the last 24 hours. Keywords include `corruption`, `corrupted`, `Database page corruption`, `InnoDB: Error: page`, `space id and page`, `is in the future`, and `checksum mismatch`.

**Prerequisites:** none — the error log is always available.

---

## plugin-connection-storm

**Binary:** `plugin-connection-storm`  
**Finding:** `WARN0307`  
**Source:** `cluster/logplugin/plugins/plugin-connection-storm/main.go`

Detects connection pool saturation through two complementary signals:

1. **Sleep ratio** — when `sleeping_connections / total_connections ≥ sleep-ratio-threshold` a connection leak is indicated: clients are opening connections but not closing them, and the server is spending capacity maintaining idle sessions.
2. **Lock waits** — when the number of threads stuck waiting on any lock (metadata lock, table lock, row lock) reaches `lock-wait-count`, a lock storm is indicated.

Evaluation is skipped entirely when total connections are below `min-connections` to avoid false positives on idle servers.

**Prerequisites:** none — uses `SHOW PROCESSLIST`, available on all servers.

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `sleep-ratio-threshold` | `0.60` | Fraction of sleeping / total connections to trigger |
| `lock-wait-count` | `3` | Concurrent threads in lock-wait state to trigger |
| `min-connections` | `10` | Minimum total connections before evaluating |

---

## plugin-error-storm

**Binary:** `plugin-error-storm`  
**Finding:** `WARN0302`  
**Source:** `cluster/logplugin/plugins/plugin-error-storm/main.go`

Groups error log entries by a **template fingerprint** (numbers and quoted string literals are stripped so that variant messages with different values are counted together as one template) and fires when any template appears `storm-threshold` or more times within `storm-window-mins` minutes.

Both the MariaDB/MySQL error log and the SQL error log are scanned.

**Prerequisites:** none — the error log is always available.

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `storm-threshold` | `10` | Occurrences of the same error template to trigger |
| `storm-window-mins` | `5` | Rolling time window in minutes |

---

## plugin-full-table-scan-spike

**Binary:** `plugin-full-table-scan-spike`  
**Finding:** `WARN0304`  
**Source:** `cluster/logplugin/plugins/plugin-full-table-scan-spike/main.go`

Reads `performance_schema.events_statements_summary_by_digest` and fires when **both** conditions hold simultaneously:

- `full_scan_executions / total_executions ≥ scan-ratio-threshold`
- `full_scan_executions ≥ min-full-scan-count`

Digests with fewer than `min-exec-count` total executions are excluded to filter out one-off or infrequent queries. The finding reports the top three offending query digests.

**Prerequisites:**

| Variable | Required value | Notes |
|---|---|---|
| `performance_schema` | `ON` | Must be enabled at server startup |
| `performance_schema_consumer_events_statements_summary_by_digest` | `ON` | Usually ON by default when performance_schema is enabled |

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `scan-ratio-threshold` | `0.30` | Fraction of full-scan executions / total to trigger |
| `min-full-scan-count` | `10` | Absolute minimum full-scan count required |
| `min-exec-count` | `5` | Minimum digest execution count to include |

---

## plugin-metadata-lock-contention

**Binary:** `plugin-metadata-lock-contention`  
**Finding:** `WARN0305`  
**Source:** `cluster/logplugin/plugins/plugin-metadata-lock-contention/main.go`

Reads `information_schema.METADATA_LOCK_INFO` (requires the MariaDB `METADATA_LOCK_INFO` plugin) and fires when either:

- Any single MDL wait duration ≥ `lock-wait-ms-threshold` milliseconds, **or**
- The total number of concurrent MDL waits ≥ `lock-count-threshold`

Metadata lock waits occur when a DDL statement (`ALTER TABLE`, `DROP TABLE`) holds an exclusive metadata lock, blocking all concurrent DML on the affected table until the DDL completes.

**Prerequisites:**

| Requirement | How to enable |
|---|---|
| MariaDB only | Not available on MySQL (no `METADATA_LOCK_INFO` plugin) |
| `metadata_lock_info` plugin | `INSTALL SONAME 'metadata_lock_info'` on each monitored server |

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `lock-wait-ms-threshold` | `5000` | Single MDL wait duration in ms to trigger |
| `lock-count-threshold` | `3` | Concurrent MDL waits count to trigger |

---

## plugin-replication-lag-predictor

**Binary:** `plugin-replication-lag-predictor`  
**Finding:** `WARN0303`  
**Source:** `cluster/logplugin/plugins/plugin-replication-lag-predictor/main.go`

Detects DML write bursts in the slow log *before* lag appears in `seconds_behind_master`. High DML rates in the slow log predict that the binary log will grow faster than the replica can consume it.

Fires when: `DML_count_in_window / window_mins ≥ write-rate-threshold queries/min`

DML verbs counted: `INSERT`, `UPDATE`, `DELETE`, `REPLACE`, `LOAD DATA`.

**Prerequisites:**

| Variable | Required value | Notes |
|---|---|---|
| `slow_query_log` | `ON` | Enable the slow query log |
| `long_query_time` | e.g. `0` or `1` | Set low enough to capture DML-heavy queries |

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `window-mins` | `5` | Observation window in minutes |
| `write-rate-threshold` | `50` | DML queries per minute to trigger |

---

## plugin-slow-query-regression

**Binary:** `plugin-slow-query-regression`  
**Finding:** `WARN0301`  
**Source:** `cluster/logplugin/plugins/plugin-slow-query-regression/main.go`

Compares the current slow-log average latency against the PFS historical baseline for each query digest. A query is flagged as regressed when:

`current_avg_ms / pfs_avg_ms ≥ regression-factor`

The current window is the last `timeframe-hours` hours of slow log. Only digests with at least `min-executions` PFS executions are included to ensure a meaningful baseline. Up to five regressions are reported per tick.

**Prerequisites:**

| Variable | Required value | Notes |
|---|---|---|
| `slow_query_log` | `ON` | Current latency baseline source |
| `long_query_time` | e.g. `0` or `1` | Set low enough to capture the queries you want to track |
| `performance_schema` | `ON` | Historical baseline source (`events_statements_summary_by_digest`) |

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `timeframe-hours` | `1` | Slow log window to use as current baseline |
| `regression-factor` | `3.0` | Multiplier over PFS average to flag as regression |
| `min-executions` | `5` | Minimum PFS execution count for a valid baseline |

---

## plugin-tmp-table-storm

**Binary:** `plugin-tmp-table-storm`  
**Finding:** `WARN0306`  
**Source:** `cluster/logplugin/plugins/plugin-tmp-table-storm/main.go`

Reads PFS to detect queries creating on-disk temporary tables. Fires when **either**:

- On-disk tmp table count ≥ `disk-tmp-threshold`, **or**
- `disk_tmp / (disk_tmp + mem_tmp) ≥ ratio-threshold`

Only digests with at least `min-exec-count` executions are included. Common causes: missing indexes on `GROUP BY` / `ORDER BY` columns, or `tmp_table_size` / `max_heap_table_size` set too small.

**Prerequisites:**

| Variable | Required value | Notes |
|---|---|---|
| `performance_schema` | `ON` | Must be enabled at server startup |

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `disk-tmp-threshold` | `20` | Absolute on-disk tmp table count to trigger |
| `ratio-threshold` | `0.20` | Disk/total tmp ratio to trigger |
| `min-exec-count` | `3` | Minimum digest execution count to include |

---

## plugin-off-hours-access

**Binary:** `plugin-off-hours-access`  
**Finding:** `WARN0309`  
**Source:** `cluster/logplugin/plugins/plugin-off-hours-access/main.go`

Scans the audit log for connections or DML from non-exempt accounts outside configured business hours. Useful for PCI-DSS and HIPAA compliance auditing.

A finding is raised when all of the following hold:

- Access time is outside `allowed-hours-start`–`allowed-hours-end` (local time)
- Account is not listed in `always-allowed-users`
- Operation type is in `allowed-operations`
- Event falls within the last `timeframe-hours`

**Prerequisites:**

| Requirement | How to enable |
|---|---|
| `server_audit` plugin | `INSTALL SONAME 'server_audit'` |
| `server_audit_logging` | `SET GLOBAL server_audit_logging = ON` |
| `server_audit_events` | Include `CONNECT`, `QUERY`, `QUERY_DML`, `QUERY_DDL` as needed |

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `allowed-hours-start` | `8` | Business hours start, hour of day 0–23 |
| `allowed-hours-end` | `20` | Business hours end, hour of day 1–24 |
| `always-allowed-users` | `root,replication_manager` | Accounts that are always permitted |
| `allowed-operations` | `QUERY,QUERY_DML,QUERY_DDL,CONNECT` | Audit operation types to inspect |
| `timeframe-hours` | `1` | Audit log window to scan |

---

## plugin-privilege-escalation

**Binary:** `plugin-privilege-escalation`  
**Finding:** `WARN0308`  
**Source:** `cluster/logplugin/plugins/plugin-privilege-escalation/main.go`

Watches the audit log for DDL statements that modify user privileges performed by any account not in `allowed-admin-users`.

Watched operations: `GRANT`, `REVOKE`, `CREATE USER`, `ALTER USER`, `DROP USER`, `RENAME USER`, `SET PASSWORD`.

**Prerequisites:**

| Requirement | How to enable |
|---|---|
| `server_audit` plugin | `INSTALL SONAME 'server_audit'` |
| `server_audit_logging` | `SET GLOBAL server_audit_logging = ON` |
| `server_audit_events` | Must include `QUERY_DDL` |

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `allowed-admin-users` | `root,replication_manager` | Accounts permitted to perform privilege DDL |
| `timeframe-hours` | `24` | Audit log window to scan |

---

## plugin-binlog-cleartext-password

**Binary:** `plugin-binlog-cleartext-password`  
**Finding:** `WARN0310`  
**Source:** `cluster/logplugin/plugins/plugin-binlog-cleartext-password/main.go`

Scans binlog QUERY events for SQL statements that contain a cleartext password literal:

- `CREATE USER … IDENTIFIED BY 'pwd'`
- `ALTER USER … IDENTIFIED BY 'pwd'`
- `GRANT … IDENTIFIED BY 'pwd'`
- `SET PASSWORD … = 'pwd'`

Password values are partially redacted in findings (first and last character shown). Findings are capped at `max-findings` per tick to avoid log flooding.

**Prerequisites:**

| Requirement | How to enable |
|---|---|
| Binary logging | `log_bin` enabled (set in `my.cnf`, requires restart) |
| `binlog_format` | `MIXED` or `ROW` — STATEMENT format is also captured via QUERY events |

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `timeframe-hours` | `1` | Binlog event window to scan |
| `max-findings` | `10` | Maximum findings per evaluation tick |

---

## plugin-binlog-creditcard-leak

**Binary:** `plugin-binlog-creditcard-leak`  
**Finding:** `WARN0311`  
**Source:** `cluster/logplugin/plugins/plugin-binlog-creditcard-leak/main.go`

Scans binlog QUERY events for potential credit card Primary Account Numbers (PANs) using two validation layers:

1. **Regex** — matches 13–19 digit sequences with optional space or dash separators (covers Visa, Mastercard, Amex, Discover, UnionPay formats)
2. **Luhn algorithm** — validates candidates to filter phone numbers, order IDs, and timestamps

PANs are masked in findings (last four digits shown). Findings are capped at `max-findings` per tick.

**Prerequisites:**

| Requirement | How to enable |
|---|---|
| Binary logging | `log_bin` enabled (set in `my.cnf`, requires restart) |

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `timeframe-hours` | `1` | Binlog event window to scan |
| `max-findings` | `10` | Maximum findings per evaluation tick |

---

## WARN Code Reference

| Code | Plugin | Condition | Notes |
|---|---|---|---|
| WARN0300 | plugin-innodb-corruption | InnoDB corruption keyword in error log | 24h window |
| WARN0301 | plugin-slow-query-regression | Query latency regressed vs PFS baseline | Up to 5 per tick |
| WARN0302 | plugin-error-storm | Error template count exceeded threshold | Fingerprint-based dedup |
| WARN0303 | plugin-replication-lag-predictor | DML write rate exceeds threshold | Leading indicator for lag |
| WARN0304 | plugin-full-table-scan-spike | Full-scan ratio exceeded threshold | Top 3 digests reported |
| WARN0305 | plugin-metadata-lock-contention | MDL wait duration or count exceeded | Requires MariaDB MDL plugin |
| WARN0306 | plugin-tmp-table-storm | On-disk tmp table count or ratio exceeded | Check tmp_table_size |
| WARN0307 | plugin-connection-storm | Sleep ratio or lock-wait count exceeded | Connection leak indicator |
| WARN0308 | plugin-privilege-escalation | Privilege DDL by non-admin account | Audit log required |
| WARN0309 | plugin-off-hours-access | DB access outside business hours | Audit log required |
| WARN0310 | plugin-binlog-cleartext-password | Cleartext password in binlog | PAN masked in finding |
| WARN0311 | plugin-binlog-creditcard-leak | Credit card PAN detected in binlog | Luhn validated, masked |
