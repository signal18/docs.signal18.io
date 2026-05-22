---
title: Recovery & Data Loss
taxonomy:
    category: docs
---

### 15.7.1 How do I recover a failed master?

**Recovery methods depend on scenario:**

**Scenario 1: Master recoverable with GTID consistency**
- Old master GTID is subset of new master
- **replication-manager** auto-rejoins as slave
- No data loss, automatic process

**Scenario 2: Master has extra transactions (diverged)**

**Method A: Flashback** (MariaDB with flashback enabled)
```
autorejoin-flashback = true
```
- Rolls back diverged transactions
- Re-syncs with new master
- Diverged data is saved

**Method B: mysqldump**
```
autorejoin-mysqldump = true
```
- Dumps database from new master
- Restores to old master
- Slower but reliable

**Method C: Physical backup**
```
autorejoin-backup-binlog = true
autorejoin-semisync = false
```
- Uses physical backups (ZFS, LVM snapshots)
- Fastest for large databases

**Method D: Manual re-provision**
- Provision old master from new master
- Most reliable for complex scenarios

**Crash information saved**: Check `/var/lib/replication-manager/crash*Unixtime*/` for binary logs and election details.

**Reference**: `/pages/05.configuration/03.failover/02.crash-recovery/docs.md`

---

### 15.7.2 When should I use flashback vs mysqldump recovery?

**Flashback recovery:**

**Requirements:**
- MariaDB (flashback not available in MySQL)
- Binary logs in ROW format
- `binlog_format = ROW`
- Flashback enabled: `autorejoin-flashback = true`

**Advantages:**
- Very fast (seconds to minutes)
- No full data copy needed
- Reverses diverged transactions

**Disadvantages:**
- Only works for small divergence
- Requires MariaDB specific features
- May fail with DDL changes

**Use when**: Small transaction divergence, MariaDB environment, fast recovery priority

---

**mysqldump recovery:**

**Requirements:**
- Any MySQL/MariaDB/Percona version
- `autorejoin-mysqldump = true`

**Advantages:**
- Works on any database version
- Reliable and predictable
- No special prerequisites

**Disadvantages:**
- Slow for large databases (hours for TB+ databases)
- Requires full data dump/restore
- Locks tables during dump

**Use when**: Large divergence, MySQL (not MariaDB), guarantee consistency

---

**Physical backup recovery:**

**Requirements:**
- ZFS, LVM, or storage snapshots
- `autorejoin-backup-binlog = true`

**Advantages:**
- Fastest for large databases
- Minimal overhead
- Block-level copy

**Disadvantages:**
- Requires storage infrastructure
- More complex setup

**Use when**: Very large databases, storage supports snapshots, fastest recovery critical

**Reference**: `/pages/05.configuration/03.failover/02.crash-recovery/docs.md`

---

### 15.7.3 Where can I find crash information after failover?

**replication-manager** records crash details in multiple locations:

**Location 1: Crash directory**
```
/var/lib/replication-manager/crash*Unixtime*/
```

Contains:
- Binary logs from elected master at time of election
- Replication state when node was still master
- Useful for manual recovery and auditing

**Location 2: Cluster state file**
```
/var/lib/replication-manager/cluster_name.json
```

Contains:
```json
{
  "crashes": [
    {
      "URL": "127.0.0.1:3310",
      "FailoverMasterLogFile": "bin.000001",
      "FailoverMasterLogPos": "459",
      "FailoverSemiSyncSlaveStatus": true,
      "FailoverIOGtid": [{"DomainID": 0, "ServerID": 3310, "SeqNo": 1}],
      "ElectedMasterURL": "127.0.0.1:3311"
    }
  ]
}
```

**Location 3: API endpoint**
```
GET /api/clusters/cluster_name/crashes
```

**When crashes are cleared**: When cluster topology returns to no ERROR state.

**Reference**: `/pages/07.howto/03.toubleshoot-crashes/docs.md:26`
