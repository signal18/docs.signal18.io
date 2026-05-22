---
title: Topology & Deployment
taxonomy:
    category: docs
---

### 15.4.1 Why are two-node clusters not recommended?

**Limitation**: Two-node clusters lack fault tolerance for automatic failover decisions.

**Problems with two nodes:**

**Split-brain risk:**
- Network partition isolates both nodes
- Each node thinks the other is down
- Both could become writable masters

**No quorum:**
- Cannot determine which node should be master
- Requires external arbitrator for reliable decisions

**Single point of failure:**
- If slave fails, no failover targets
- If master fails, only one promotion candidate
- No redundancy for planned maintenance

**Recommendation**: Use minimum three nodes:
- Provides N+2 redundancy
- Allows one node failure with spare
- Better quorum decisions
- Supports maintenance windows

**Alternative**: Two-node with external arbitrator to break ties.

**Reference**: `/pages/05.configuration/05.replication/docs.md`

---

### 15.4.2 What are the limitations of Galera cluster support?

**Supported but with constraints:**

**No serialized isolation:**
- Galera cluster doesn't support serialized transaction isolation
- Increases deadlock probability vs traditional replication

**Deadlock risk:**
- Multi-master writes across nodes can conflict
- Applications must handle deadlock retries
- More common than single-master topologies

**Certification-based replication:**
- Different conflict detection model
- Transactions can fail at commit time
- Requires application-level retry logic

**Recommendations:**
- Use optimistic locking strategies
- Implement transaction retry logic
- Consider single-writer patterns when possible
- Monitor for certification failures

**Reference**: `/pages/04.architecture/03.topologies/06.multi-master-galera/docs.md`

---

### 15.4.3 How does multi-master prevent split-brain?

**Required configuration** for master-master (multi-master) topology:

**Critical setting:**
```
read_only = 1
```

Must be set in **MariaDB configuration file** (my.cnf), not just dynamically.

**How it works:**
1. Both nodes start in `read_only` mode
2. **replication-manager** promotes one node to writable master
3. Sets `read_only = 0` on active master only
4. Keeps `read_only = 1` on standby master

**Without `read_only = 1` in config:**
- Both nodes could accept writes after restart
- Split-brain condition
- Data conflicts and replication breakage

**Additional protection:**
- Routing proxies (HAProxy/ProxySQL) directed to single writer
- Heartbeat table monitoring
- External arbitrator for network partition scenarios

**Reference**: `/pages/05.configuration/05.replication/docs.md`

---

### 15.4.4 What happens if a relay slave crashes in multi-tier topology?

**Problem**: Relay node failures are not automatically managed.

**Multi-tier example:**
```
DC1: Master → Relay1
DC2: Relay1 → Slave1, Slave2
```

**If Relay1 crashes:**
- Slave1 and Slave2 lose replication source
- **replication-manager** does not auto-repoint to Master
- Manual intervention required

**Limitation**: Designed for master failure, not intermediate relay failures.

**Workaround options:**

**Option 1**: Manually repoint slaves to master
```sql
CHANGE MASTER TO MASTER_HOST='master', ...
```

**Option 2**: Use scripts with `failover-post-script` to handle relay failures

**Option 3**: Avoid multi-tier topologies in critical paths

**When multi-tier makes sense:**
- Cross-DC bandwidth optimization
- Network topology constraints
- Acceptable manual intervention for relay failures

**Reference**: `/pages/05.configuration/05.replication/docs.md`
