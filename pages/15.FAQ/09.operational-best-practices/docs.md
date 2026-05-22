---
title: Operational Best Practices
taxonomy:
    category: docs
---

### 15.9.1 What are the recommended parallel replication settings?

**MariaDB 10.1+ optimistic parallel replication:**

```
slave_parallel_mode = optimistic
slave_domain_parallel_threads = 4    # Set to number of CPU cores
slave_parallel_threads = 4           # Set to number of CPU cores
expire_logs_days = 5
sync_binlog = 1
log_slave_updates = ON
```

**Benefits:**
- In-order optimistic parallel replication
- Replication can catch up with master in most scenarios
- Combined with semi-sync for safety

**Why this matters:**
- Keeps slaves synchronized
- Reduces replication lag
- Enables safer failover windows

**Verification:**
```sql
SHOW VARIABLES LIKE 'slave_parallel%';
```

**Reference**: `/pages/07.howto/01.replication-best-practice/docs.md:14`

---

### 15.9.2 What are the recommended semi-sync settings?

**MariaDB semi-sync configuration:**

```
plugin_load = "semisync_master.so;semisync_slave.so"
rpl_semi_sync_master = ON
rpl_semi_sync_slave = ON
loose_rpl_semi_sync_master_enabled = ON
loose_rpl_semi_sync_slave_enabled = ON
rpl_semi_sync_master_timeout = 10
rpl_semi_sync_master_wait_point = AFTER_COMMIT
```

**Important notes:**

**Expected warning on slaves:**
- You will see "SemiSyncMaster Status switched OFF" warnings
- This is normal - slaves don't act as semi-sync masters

**Timeout value (10 seconds):**
- Workload slows to network speed until timeout
- After timeout, switches to async (SYNC status lost)
- Balance between safety and performance

**Wait point:**
- Use `AFTER_COMMIT` (default) for client safety
- Avoid `AFTER_SYNC` despite fewer binlog transactions after crash

**Benefits:**
- Delays commit until one replica acknowledges
- Provides "In Sync" status for SLA calculations
- **replication-manager** uses for safe failover windows

**Reference**: `/pages/07.howto/01.replication-best-practice/docs.md:30`

---

### 15.9.3 Should I enable dynamic best practice enforcement?

**Parameter**: `monitoring-enforce-best-practices`

**When enabled**: **replication-manager** dynamically adjusts database settings to match best practices.

**Warning**: Dynamic changes are **lost on replication-manager restart** unless saved to config.

**Recommendation**:

**DON'T rely on dynamic enforcement** - instead:
1. Configure settings directly in database config files (my.cnf)
2. Use **replication-manager** enforcement as validation only
3. Make permanent changes to database configuration

**Permanent settings example** (my.cnf):
```
[mysqld]
sync_binlog = 1
innodb_flush_log_at_trx_commit = 1
slave_parallel_mode = optimistic
slave_parallel_threads = 4
rpl_semi_sync_master_enabled = ON
rpl_semi_sync_slave_enabled = ON
```

**Use dynamic enforcement for**: Testing and validation, not production operations.

**Reference**: `/pages/07.howto/02.enforce-best-practice/docs.md`

---

### 15.9.4 What backup strategy should I use?

**Backup types available:**

**Logical backups:**
- **mysqldump**: Universal, slow for large databases
- **mydumper**: Parallel logical backup, faster than mysqldump
- Use for: Small/medium databases, cross-version restores

**Physical backups:**
- **mariabackup** (formerly maria-backup): Hot backup for MariaDB
- **xtrabackup**: Hot backup for MySQL/Percona
- **restic**: Filesystem-level incremental backups
- Use for: Large databases, faster restore

**Snapshot backups:**
- **ZFS snapshots**: Instant point-in-time copies
- **LVM snapshots**: Block-level snapshots
- Use for: Very large databases, instant recovery

**Configuration parameters:**

```
# Storage location
backup-logical-type = "mysqldump"  # or "mydumper"
backup-physical-type = "mariabackup"
backup-disk-threshold-warn = 85
backup-disk-threshold-crit = 95

# Restic backups with auto-purge
backup-restic = true
backup-restic-purge-oldest-on-disk-space = true
backup-restic-purge-oldest-on-disk-threshold = 90
```

**Recommendation**:
- Small databases (<100GB): mysqldump/mydumper
- Large databases (100GB-1TB): mariabackup/xtrabackup
- Very large (>1TB): ZFS/LVM snapshots + binlog streaming

**Reference**: `/pages/05.configuration/14.maintenance/02.backups/docs.md`
