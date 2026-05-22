---
title: Replication & Synchronization
taxonomy:
    category: docs
---

### 15.2.1 Does semi-sync replication guarantee no data loss after a master crash?

**Short answer**: No.

**Detailed explanation**: Semi-sync SYNC status does **not** guarantee the old master is replication-consistent with the cluster after a crash or shutdown.

**Known issues:**
- [MDEV-11855](https://jira.mariadb.org/browse/MDEV-11855): Crash can leave extra transactions in binary log
- [MDEV-11853](https://jira.mariadb.org/browse/MDEV-11853): Shutdown can leave uncommitted transactions

**What semi-sync guarantees**: No client applications have seen transactions that didn't reach a replica, but the master's binary log may contain additional events not yet replicated.

**Impact**: In heavy write scenarios, crashed masters often require re-provisioning from another node rather than rejoining the cluster.

**Recommendation**: Use `rpl_semi_sync_master_wait_point = AFTER_COMMIT` (default) to ensure client-visible transactions are safer, even though it may leave more transactions in the binary log after a crash.

**Reference**: `/pages/07.howto/01.replication-best-practice/docs.md:44`

---

### 15.2.2 Why does my switchover fail after long write inactivity?

**Problem**: Rejoining slaves during switchover fails when using `expire_logs_days` after extended periods without writes.

**Cause**: Binary logs are automatically purged based on `expire_logs_days`, which may remove logs needed for slave rejoin after the cluster has been idle.

**Related bug**: [MDEV-10869](https://jira.mariadb.org/browse/MDEV-10869)

**Solution**:
- Increase `expire_logs_days` value to retain logs longer
- Use binary log retention based on space rather than time
- Monitor binary log disk usage
- Consider using `binlog_expire_logs_seconds` (MariaDB 10.6+) for finer control

**Workaround**: If switchover fails, you may need to re-provision affected slaves from the new master.

**Reference**: Current FAQ

---

### 15.2.3 What's the difference between AFTER_SYNC and AFTER_COMMIT?

**Parameter**: `rpl_semi_sync_master_wait_point`

**AFTER_COMMIT** (recommended):
- Master commits transaction to storage
- Master waits for slave acknowledgment
- Master returns success to client
- **Advantage**: Client-visible transactions are guaranteed replicated
- **Disadvantage**: Slightly more transactions in binary log after crash

**AFTER_SYNC**:
- Master syncs binary log to disk
- Master waits for slave acknowledgment
- Master commits to storage
- Master returns success to client
- **Advantage**: Fewer extra transactions in binary log after crash
- **Disadvantage**: Clients may have seen transactions that are lost during failover

**Recommendation**: Use `AFTER_COMMIT` for safer client experience.

**Reference**: `/pages/07.howto/01.replication-best-practice/docs.md:50`

---

### 15.2.4 Can SUPER privileged users write during switchover on MariaDB?

**Problem**: Applications with SUPER privileges can write to a read-only master during switchover.

**Cause**: MariaDB does not have MySQL's `super_read_only` protection. The `READ_ONLY` flag does not block SUPER users from writing.

**Related bug**: [MDEV-9458](https://jira.mariadb.org/browse/MDEV-9458)

**Impact**: During switchover:
- Regular users are blocked by `READ_ONLY`
- SUPER users can still write
- Writes with SUPER privileges may pile up during `FLUSH TABLES WITH READ LOCK`
- These writes de-queue after lock release, potentially causing inconsistency

**Mitigation**:
- Delegate write protection to routing proxies (ProxySQL, MaxScale)
- These proxies will not enable writes on a `READ_ONLY` slave
- External scripts can manage routing changes under protection of FTWRL
- **replication-manager** decreases `max_connections` during switchover to limit queued connections

**Best practice**: Don't grant SUPER privileges to application users.

**Reference**: Current FAQ

---

### 15.2.5 Why does MySQL hang during shutdown with GTID enabled?

**Problem**: MySQL server hangs during shutdown when using GTID with `autocommit=0` and `super_read_only=ON`.

**Affected versions:**
- MySQL 5.7.0 to 5.7.24
- MySQL 8.0.0 to 8.0.13

**Fixed in:**
- MySQL 5.7.25+ ([Release Notes](https://dev.mysql.com/doc/relnotes/mysql/5.7/en/news-5-7-25.html#mysqld-5-7-25-bug))
- MySQL 8.0.14+ ([Release Notes](https://dev.mysql.com/doc/relnotes/mysql/8.0/en/news-8-0-14.html#mysqld-8-0-14-bug))

**Cause**: Transaction attempting to save GTIDs to `mysql.gtid_executed` table fails because `super_read_only=ON` prevents the update. With `autocommit=0`, the transaction never completes, blocking shutdown.

**Solution**: Upgrade to MySQL 5.7.25/8.0.14 or later.

**Workaround** (if upgrade not possible): Set `autocommit=1` or avoid `super_read_only` on slaves.

**Bug reference**: Bug #28183718

**Reference**: Current FAQ

---

### 15.2.6 What happens when semi-sync reaches timeout?

**Problem**: Semi-sync timeout causes workload changes and increased failover risk.

**Behavior**: When `rpl_semi_sync_master_timeout` (default: 10 seconds) is reached:
1. Master stops waiting for slave acknowledgment
2. Master switches to asynchronous replication
3. Workload is no longer constrained by network speed
4. **Sync status is lost** - failover becomes risky

**Impact before timeout**: Semi-sync slows workload to network replication speed, creating backpressure on writes.

**Impact after timeout**:
- Crash or shutdown leads to potential data loss
- Old master may require re-provisioning
- Failover risk increases significantly

**Monitoring**: **replication-manager** tracks "In Sync" status and SLA metrics to determine when safe failover windows exist.

**Reference**: `/pages/07.howto/01.replication-best-practice/docs.md:46`

---

### 15.2.7 Why can't relay slaves reconnect after their master dies in multi-tier topology?

**Problem**: Relay slaves cannot automatically reconnect in multi-tier replication when their intermediate master fails.

**Cause**: **replication-manager** does not automatically manage relay node failures in multi-tier topologies.

**Limitation**: If you have:
```
Master → Relay → Slave
```

And the Relay node dies, the Slave cannot automatically reconnect to Master.

**Workaround 1**: Manually repoint slaves to the new topology after relay node failure.

**Workaround 2 (recommended)**: Use **multi-domain child clusters** instead of multi-tier replication. replication-manager supports defining each level of the tree as its own master-slave cluster, where a slave of the parent cluster is the master of the child cluster. Each cluster has independent failover management, so a failure at any level is handled automatically within that cluster.

```
[parent-cluster]                [child-cluster]
Master → Slave1 (= child Master) → ChildSlave1
         Slave2                     ChildSlave2
```

**Limitation**: Child cluster replication does not support replication filtering (`replicate-do-db`, `replicate-ignore-table`, etc.) — all databases and tables are replicated to the child level.

**Design consideration**: Multi-domain child clusters provide fully automated HA at every level of the tree, at the cost of no filtering. Multi-tier relay topologies allow filtering but require manual intervention when relay nodes fail.

**Reference**: `/pages/05.configuration/05.replication/docs.md`

---

### 15.2.8 What does "server-id 1000" reserved mean?

**Restriction**: Do not use `server-id = 1000` on any database node in your cluster.

**Reason**: **replication-manager** reserves `server-id = 1000` for binlog server operations during crash recovery.

**Impact**: Using server-id 1000 in your cluster will cause:
- Replication errors during backup operations
- Binlog server conflicts
- Crash recovery failures

**Solution**: Use any server-id except 1000. Common practice is sequential IDs: 3306, 3307, 3308, etc.

**Reference**: `/pages/05.configuration/03.failover/02.crash-recovery/docs.md`
