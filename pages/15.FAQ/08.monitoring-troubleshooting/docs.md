---
title: Monitoring & Troubleshooting
taxonomy:
    category: docs
---

### 15.8.1 How do I increase logging for debugging?

**Increase log verbosity dynamically via API:**

```bash
replication-manager-cli api \
  url=https://127.0.0.1:3000/api/clusters/cluster_name/settings/switch/verbosity
```

**Or configure in config file:**

```
[Default]
log-level = 4
```

**Log levels:**
- 1: Errors only
- 2: Warnings
- 3: Info (default)
- 4: Debug
- 5+: Very verbose (trace level)

**Per-module logging (3.1+):**
```
log-level = 2                    # Global default
log-level-backup-stream = 4      # Debug backups
log-level-proxy = 1              # Minimal proxy logs
log-level-heartbeat = 4          # Debug heartbeat
```

**Collecting debug information for support:**

```bash
# Increase verbosity
replication-manager-cli api url=.../verbosity

# Reproduce issue

# Collect internal state
replication-manager-cli show > state.json

# Attach to support ticket:
# - /var/log/replication-manager.log
# - state.json
```

**Reference**: `/pages/07.howto/03.toubleshoot-crashes/docs.md:7`

---

### 15.8.2 Why is performance schema monitoring causing overhead?

**Change in version 3.x**: Performance Schema monitoring enabled by default.

**New defaults in 3.x:**
```
monitoring-performance-schema-mutex = true
monitoring-performance-schema-latch = true
monitoring-performance-schema-memory = true
```

**Impact**: Increased database load from performance schema queries, especially on:
- High-transaction workloads
- Many mutex/latch contentions
- Memory-intensive operations

**Solution if overhead is problematic:**

**Disable specific monitors:**
```
monitoring-performance-schema-mutex = false
monitoring-performance-schema-latch = false
monitoring-performance-schema-memory = false
```

**Or disable performance schema entirely on database:**
```
[mysqld]
performance_schema = OFF
```

**Trade-off**: Disabling reduces monitoring visibility into database internals.

**Recommendation**: Monitor database CPU/load after upgrade; disable only if overhead is measurable.

**Reference**: `/pages/02.installation/04.migration/docs.md:156`

---

### 15.8.3 How do I check internal status for support tickets?

**Collect internal status:**

```bash
replication-manager-cli show
```

Output includes internal state of:
- Settings
- Clusters
- Servers
- Master
- Slaves
- Crashes
- Alerts

**Filter specific class:**
```bash
replication-manager-cli show --get=servers
replication-manager-cli show --get=crashes
```

**For support tickets, attach:**

1. **Output of `show` command** (JSON format)
2. **Log file** with increased verbosity:
   ```bash
   /var/log/replication-manager.log
   ```
3. **Reproduce with debug logging**:
   ```bash
   # Enable verbose logging
   replication-manager-cli api url=.../verbosity
   # Reproduce issue
   # Collect logs
   ```

**Submit to**: https://github.com/signal18/replication-manager/issues

**Reference**: `/pages/07.howto/03.toubleshoot-crashes/docs.md:14`

---

### 15.8.5 How is table fragmentation measured in the Schema tab?

**Short answer**: The Schema tab shows a fragmentation estimate based on `DATA_FREE / (DATA_LENGTH + INDEX_LENGTH)` from `information_schema.TABLES`. This is an indicator, not a precise measurement.

**What `DATA_FREE` tells you**:

`DATA_FREE` reports the number of bytes allocated to the table's tablespace that are not currently used by row data. On InnoDB with `innodb_file_per_table=ON` (the default since MySQL 5.6 / MariaDB 10.0), this value is per-table. With shared tablespace, it reflects the entire shared tablespace — not a specific table.

**How to calculate fragmentation percentage**:

```sql
SELECT
  TABLE_SCHEMA,
  TABLE_NAME,
  DATA_LENGTH,
  INDEX_LENGTH,
  DATA_FREE,
  ROUND(DATA_FREE / (DATA_LENGTH + INDEX_LENGTH) * 100, 1) AS frag_pct
FROM information_schema.TABLES
WHERE ENGINE = 'InnoDB'
  AND DATA_LENGTH + INDEX_LENGTH > 0
ORDER BY DATA_FREE DESC;
```

**Important caveats**:

- **`DATA_FREE` underestimates real fragmentation**: It only counts free *extents* (contiguous 1 MB blocks). Internal page-level fragmentation — partially filled 16 KB pages inside allocated extents — is invisible to this metric. [Jeremy Cole's research](https://blog.jcole.us/2013/01/05/exploring-innodb-page-management-with-innodb_ruby/) with `innodb_ruby` demonstrated that a table can shrink by 75% after `OPTIMIZE TABLE` while `DATA_FREE` showed only 2% reclaimable space.

- **Row-based estimate as alternative**: `(DATA_LENGTH - TABLE_ROWS * AVG_ROW_LENGTH) / DATA_LENGTH * 100` can catch internal bloat but is noisy for variable-length rows (VARCHAR, TEXT, BLOB).

- **Page fill factor**: The only way to measure true page-level fragmentation is to inspect `.ibd` files directly using tools like [innodb_ruby](https://github.com/jeremycole/innodb_ruby). This is not practical from SQL alone.

**When to act**:

- **< 10% fragmentation**: Normal, no action needed
- **10–30%**: Monitor; schedule `OPTIMIZE TABLE` during maintenance windows if the table is write-heavy
- **> 30%**: Consider running `ALTER TABLE t ENGINE=InnoDB` (online DDL rebuild) or `OPTIMIZE TABLE` — both reclaim space and rebuild the B+tree

**How replication-manager uses this**:

The Schema tab in the dashboard computes the fragmentation ratio from cached `information_schema` metadata collected during periodic schema monitoring scans. The value is an approximation useful for identifying tables that may benefit from maintenance — it is not a substitute for deep file-level analysis.

**References**:
- [Jeremy Cole: Exploring InnoDB page management with innodb_ruby](https://blog.jcole.us/2013/01/05/exploring-innodb-page-management-with-innodb_ruby/)
- [Jeremy Cole: InnoDB internals series](https://blog.jcole.us/innodb/)
- [lefred: Overview of fragmented MySQL InnoDB tables](https://lefred.be/content/overview-of-fragmented-mysql-innodb-tables/)
