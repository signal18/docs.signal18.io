---
title: Failover & Switchover
taxonomy:
    category: docs
---

### 15.3.1 How does replication-manager prevent false positive failovers?

**Multi-layer protection:**

**1. Multiple checks** - Master failure is verified N times before acting

**2. False positive detection:**
- Network flap detection
- Heartbeat verification
- Multiple monitoring endpoints
- Quorum requirements with external arbitrator

**3. Time-based protection:**
- `failover-time-limit`: Prevents repeated failovers within specified timeframe
- Protects against flip-flop failures from same root cause

**4. Default manual mode:**
- `failover-mode = "manual"` (default)
- Sends alerts and waits for human intervention
- User confirms failover via console, API, or CLI

**5. Replication checks:**
- Valid slave must be available
- Cluster configuration must be valid
- Replication state checks (unless `check-replication-state = false`)

**Best practice configuration:**
```
failover-mode = "automatic"
failover-limit = 3
failover-time-limit = 10
failover-at-sync = false
failover-max-slave-delay = 30
```

**Reference**: `/pages/05.configuration/03.failover/01.false-positive-detetection/docs.md`

---

### 15.3.2 What happens when the entire cluster goes down?

**Default behavior**: **replication-manager** waits for the old master to recover.

**Reason**: First node to restart after total cluster failure could be a delayed slave, leading to significant data loss if promoted.

**Configuration parameter**: `failover-restart-unsafe`

**When `failover-restart-unsafe = false` (default):**
- Prevents failover to first restarted slave
- Waits for old master to show up
- Prioritizes data safety over availability

**When `failover-restart-unsafe = true`:**
- Allows failover to first restarted node
- Favors availability when master cannot recover
- Assumes DC crash brought down all nodes simultaneously (minimizing data skew)

**Recommendation**:
- Use default (`false`) unless availability is critical
- If using `true`, ensure automated slave startup procedures
- In DC crash scenarios, start old master first when possible

**Reference**: `/pages/04.architecture/02.failover-workflow/docs.md:57`

---

### 15.3.3 Why did my failover get rejected?

**Failover can be rejected for multiple reasons:**

**No valid slave available:**
- All slaves exceed `failover-max-slave-delay` (default: 30 seconds)
- Highest-positioned slave doesn't match preferred master constraints
- No slaves meet `db-servers-prefered-master` requirements
- Candidate is in `db-servers-ignored-hosts` list

**Failover limits reached:**
- `failover-limit` counter exceeded (default: 5)
- Reset counter via console, HTTP, or API to re-enable

**Time limit not met:**
- Previous failover within `failover-time-limit` window
- Prevents flip-flop failures from same issue
- Set to 0 for unlimited (not recommended)

**Sync status requirement:**
- `failover-at-sync = true` but no slaves in SYNC status
- Protects old master recovery at cost of availability

**Cluster state invalid:**
- Replication configuration errors
- Network partition detected
- Arbitrator unreachable in multi-DC setup

**Check status**: Review logs and cluster state for specific rejection reason.

**Reference**: `/pages/04.architecture/02.failover-workflow/docs.md:20`

---

### 15.3.4 How long should I wait between failover attempts?

**Parameter**: `failover-time-limit`

**Purpose**: Prevents repeated failovers from the same root cause (flip-flop protection).

**Recommended value**: 10 seconds

```
failover-time-limit = 10
```

**Behavior**: If previous failover occurred within this time window, new failover is canceled.

**Why this matters:**
- Hardware failure may affect multiple nodes sequentially
- Network issues can cause cascading failures
- Prevents promoting slaves that will immediately fail

**Set to 0**: Unlimited failovers (not recommended for production)

**Reset counter**: Use console/API to manually reset if legitimate failover needed within time window.

**Reference**: `/pages/05.configuration/03.failover/docs.md:33`

---

### 15.3.5 What does "failover-at-sync" mean for data safety?

**Parameter**: `failover-at-sync`

**Purpose**: Controls whether failover requires semi-sync SYNC status.

**When `failover-at-sync = false` (default):**
- Failover proceeds even if semi-sync is not synchronized
- Prioritizes availability over zero data loss
- Some transactions may be lost

**When `failover-at-sync = true`:**
- Failover only when at least one slave is in SYNC status
- Prioritizes data consistency over availability
- May prevent failover when most needed
- Protects old master recovery

**Recommendation for automatic failover:**
- **Balanced**: `failover-at-sync = false` with `failover-max-slave-delay = 30`
- **Minimize data loss**: `failover-at-sync = true` with `failover-max-slave-delay = 0`

**Trade-off**: Strict sync requirements reduce availability during network issues or high load.

**Reference**: `/pages/04.architecture/02.failover-workflow/docs.md:39`

---

### 15.3.6 Why is my switchover stuck or timing out?

**Problem**: Switchover hangs or takes longer than expected.

**Common causes:**

**1. FLUSH TABLES WITH READ LOCK timeout:**
- Parameter: `switchover-wait-kill` (default timeout)
- Long-running queries prevent FTWRL from acquiring lock
- Switchover waits for queries to complete

**Solution**:
- Kill long-running queries manually
- Adjust `switchover-wait-kill` timeout
- Pre-check for long queries before switchover:

```sql
SELECT * FROM information_schema.processlist
WHERE command != 'Sleep' AND time > 10;
```

**2. Slave lag:**
- Switchover waits for slaves to catch up
- Check `SHOW SLAVE STATUS` lag on all slaves
- Large transactions can delay catchup

**3. Semi-sync timeout:**
- If semi-sync is slow or timing out
- Network issues between master and slaves

**4. Proxy reconfiguration delays:**
- HAProxy, ProxySQL, or MaxScale taking time to update
- Check proxy logs and status

**Monitoring**: Enable higher log verbosity to debug:

```
log-level = 4
```

**Reference**: `/pages/05.configuration/04.switchover/docs.md`

---

### 15.3.7 When will replication-manager NOT perform automatic failover?

**Failover is prevented when:**

**Cluster state issues:**
- [ ] No valid slave available
- [ ] All slaves exceed `failover-max-slave-delay`
- [ ] Cluster configuration is invalid
- [ ] Network partition without arbitrator quorum

**Configuration limits:**
- [ ] `failover-mode = "manual"` (default)
- [ ] `failover-limit` reached (default: 5 failovers)
- [ ] Previous failover within `failover-time-limit` window
- [ ] `failover-at-sync = true` but no slave in SYNC status

**Replication constraints:**
- [ ] All slaves with highest GTID position are in `db-servers-ignored-hosts`
- [ ] No slaves match `db-servers-prefered-master` at highest position
- [ ] `check-replication-state = true` and replication is broken

**All cluster down:**
- [ ] `failover-restart-unsafe = false` (default) and old master not recovered yet

**Manual override available**: User can force failover via console/API by temporarily disabling checks.

**Reference**: `/pages/04.architecture/02.failover-workflow/docs.md`

---

### 15.3.8 How do I keep the MySQL/MariaDB Event Scheduler enabled on the master after failover?

**Problem**: When `read_only` is set on slaves, MySQL/MariaDB automatically disables the Event Scheduler. After a failover or switchover, the new master needs the Event Scheduler re-enabled, but this doesn't happen automatically without configuration.

**Solution**: Set the following in your replication-manager configuration:

```toml
failover-event-scheduler = true
```

**What this does**:

When enabled, replication-manager automatically manages the Event Scheduler across topology changes:

1. **On failover/switchover**: Enables `SET GLOBAL event_scheduler=1` on the newly promoted master
2. **On demotion**: Disables `SET GLOBAL event_scheduler=0` on the old master when it becomes a slave and is set to read-only
3. **Continuous monitoring**: If the master doesn't have the Event Scheduler running and the flag is set, replication-manager auto-enables it during the next monitoring tick — so even a manual restart of the master will get the scheduler re-enabled

No post-failover script is needed. The toggle is fully automated on both promotion and demotion.

**GUI**: The setting can also be toggled from the dashboard under **Settings → Failover → Event Scheduler**.

**API**: `PUT /api/clusters/{name}/settings/actions/switch/failover-event-scheduler`
