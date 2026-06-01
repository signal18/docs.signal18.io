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

### 15.4.2 Why is my 2-node cluster stuck after restarting replication-manager?

**Symptom**: After a failover in a 2-node cluster, restarting replication-manager leaves both servers stuck — one as Failed, the other as Suspect. No master is discovered, and the cluster doesn't recover.

**Cause**: On restart, all servers start in Suspect state. Without a quorum (minimum 3 nodes or an arbitrator), replication-manager cannot safely determine which server should be master. It refuses to guess because promoting the wrong server could cause data loss or split-brain.

**Why it can't auto-recover**: Replication-manager intentionally avoids promoting a Suspect server to master to prevent a dangerous scenario during network glitches — a server briefly going Suspect could be wrongly treated as standalone, triggering an unwanted rejoin that breaks a working cluster.

**Solutions**:

1. **Use an arbitrator** (recommended for 2-node production):
   ```toml
   arbitration-external = true
   arbitration-external-hosts = "arbitrator.example.com:10001"
   ```
   The arbitrator remembers who was master and provides the quorum needed for safe election after restart.

2. **Use 3+ nodes**: Natural quorum — replication-manager can safely elect a master by majority.

3. **Manual recovery after restart**: If stuck, manually bootstrap replication from the GUI (Actions → Bootstrap Master-Slave) or CLI:
   ```bash
   replication-manager-cli bootstrap --cluster=mycluster --topology=master-slave
   ```

**Prevention**: For production 2-node clusters, always configure an arbitrator. Without one, any replication-manager restart after a failover requires manual intervention.

---

### 15.4.3 What are the limitations of Galera cluster support?

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

### 15.4.4 How does multi-master prevent split-brain?

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

### 15.4.5 What happens if a relay slave crashes in multi-tier topology?

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
