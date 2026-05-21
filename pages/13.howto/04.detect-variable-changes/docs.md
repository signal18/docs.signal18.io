---
title: Detect Runtime Variable Changes
taxonomy:
    category: docs
---

## How to Detect Runtime Variable Changes in MariaDB and MySQL

Someone changed `max_connections` on your production master at 3am. Was it a DBA? An ORM? InnoDB auto-tuning? Here's how to find out — no AI required.

---

### The Problem

`SHOW GLOBAL VARIABLES` gives you the current state but not the history. You can't tell:
- **What** changed since last check
- **Who** changed it (user vs server auto-tuning)
- **When** it changed

### MariaDB 10.1+: `GLOBAL_VALUE_ORIGIN`

MariaDB tracks where each variable got its value:

```sql
SELECT VARIABLE_NAME, GLOBAL_VALUE, GLOBAL_VALUE_ORIGIN
FROM INFORMATION_SCHEMA.SYSTEM_VARIABLES
WHERE GLOBAL_VALUE_ORIGIN = 'SQL'
ORDER BY VARIABLE_NAME;
```

| Origin | Meaning |
|---|---|
| `COMPILE-TIME` | Built-in default, never overridden |
| `CONFIG` | Set in config file or command line |
| `SQL` | Changed at runtime via `SET GLOBAL` |
| `AUTO` | Auto-configured by the server (e.g. `back_log`, `table_open_cache`) |

**Find what a DBA changed:**
```sql
SELECT VARIABLE_NAME, GLOBAL_VALUE
FROM INFORMATION_SCHEMA.SYSTEM_VARIABLES
WHERE GLOBAL_VALUE_ORIGIN = 'SQL';
```

**Find what the server auto-tuned:**
```sql
SELECT VARIABLE_NAME, GLOBAL_VALUE
FROM INFORMATION_SCHEMA.SYSTEM_VARIABLES
WHERE GLOBAL_VALUE_ORIGIN = 'AUTO';
```

### MySQL 8.0+: `performance_schema.variables_info`

MySQL tracks more detail — who, when, and from where:

```sql
SELECT VARIABLE_NAME, VARIABLE_SOURCE, SET_TIME, SET_USER, SET_HOST
FROM performance_schema.variables_info
WHERE SET_TIME IS NOT NULL
ORDER BY SET_TIME DESC
LIMIT 20;
```

| Column | What it tells you |
|---|---|
| `VARIABLE_SOURCE` | Where the value came from: `DYNAMIC` (SET GLOBAL), `GLOBAL`, `SERVER`, `COMPILED` |
| `SET_TIME` | When it was last changed (NULL = never changed at runtime) |
| `SET_USER` | Who changed it (NULL = system/startup) |
| `SET_HOST` | From which host the SET GLOBAL was issued |

**Find variables changed by users (not the server):**
```sql
SELECT VARIABLE_NAME, VARIABLE_SOURCE, SET_TIME, SET_USER, SET_HOST
FROM performance_schema.variables_info
WHERE SET_USER IS NOT NULL
ORDER BY SET_TIME DESC;
```

**Find system-set variables (auto-tuned at startup):**
```sql
SELECT VARIABLE_NAME, VARIABLE_SOURCE
FROM performance_schema.variables_info
WHERE SET_USER IS NULL AND SET_TIME IS NOT NULL;
```

### Which Variables Auto-Change?

Not all variables in `SHOW GLOBAL VARIABLES` are static. Some change automatically:

**Every transaction** (exclude from monitoring):
- `GTID_BINLOG_POS`, `GTID_CURRENT_POS`, `GTID_BINLOG_STATE`, `GTID_SLAVE_POS`
- `GTID_EXECUTED`, `GTID_PURGED` (MySQL)

**Every statement** (session-level, may leak to global view):
- `TIMESTAMP`, `ERROR_COUNT`, `WARNING_COUNT`, `LAST_INSERT_ID`

**InnoDB adaptive** (auto-tuned based on workload):
- `INNODB_THREAD_SLEEP_DELAY` — adjusted by InnoDB when `innodb_adaptive_max_sleep_delay > 0`
- `INNODB_BUFFER_POOL_SIZE` — MariaDB 10.11+ can auto-shrink under memory pressure

**Auto-sized at startup** (computed from system resources):
- `BACK_LOG` — `MIN(900, 50 + max_connections/5)` since MariaDB 10.1.7
- `TABLE_OPEN_CACHE`, `TABLE_OPEN_CACHE_INSTANCES` — adjusted based on `open_files_limit`
- `THREAD_CACHE_SIZE` — `8 + max_connections/100`
- `HOST_CACHE_SIZE` — based on `max_connections`
- `OPEN_FILES_LIMIT` — clamped to OS `ulimit`

### Practical: Snapshot and Diff

If your version doesn't have origin tracking, snapshot and diff:

```bash
# Save current state
mysql -e "SHOW GLOBAL VARIABLES" | sort > /tmp/vars_before.txt

# ... wait ...

# Compare
mysql -e "SHOW GLOBAL VARIABLES" | sort > /tmp/vars_after.txt
diff /tmp/vars_before.txt /tmp/vars_after.txt
```

Or in SQL (MariaDB/MySQL):

```sql
-- Create a snapshot table
CREATE TABLE IF NOT EXISTS _variable_snapshot (
  name VARCHAR(64) PRIMARY KEY,
  value VARCHAR(2048),
  captured_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Save snapshot
REPLACE INTO _variable_snapshot (name, value)
SELECT VARIABLE_NAME, VARIABLE_VALUE
FROM INFORMATION_SCHEMA.GLOBAL_VARIABLES;

-- Later: find what changed
SELECT s.name, s.value AS old_value, g.VARIABLE_VALUE AS new_value
FROM _variable_snapshot s
JOIN INFORMATION_SCHEMA.GLOBAL_VARIABLES g ON g.VARIABLE_NAME = s.name
WHERE s.value != g.VARIABLE_VALUE;
```

### Automate with replication-manager

replication-manager 3.1 includes built-in temporal variable change detection:

```toml
monitoring-variable-change = true
monitoring-variable-change-script = "/usr/local/bin/on-variable-change.sh"
```

The script receives a unified diff on stdin:
```
--- db1:3306 (before)
+++ db1:3306 (after)
- max_connections = 500
+ max_connections = 1000
```

Auto-changing variables (GTID positions, InnoDB adaptive, auto-sized at startup) are automatically excluded using `GLOBAL_VALUE_ORIGIN` on MariaDB 10.1+ and `performance_schema.variables_info` on MySQL 8.0+. Additional exclusions can be configured via `monitoring-variable-change-ignore`.

The DDL change log viewer and variable change log viewer are available in the dashboard under their respective accordion panels.
