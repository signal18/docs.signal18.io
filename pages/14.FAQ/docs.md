---
title: FAQ
taxonomy:
    category: docs
---

## Installation & Setup

### Why does connection fail when using "localhost" as the database host?

**Problem**: Connection failures or socket errors when using `localhost` in `db-servers-hosts`.

**Cause**: On Unix systems, MySQL treats "localhost" specially and attempts a Unix socket connection rather than a TCP connection. This differs from network-based connections.

**Solution**: Use the IP address `127.0.0.1` instead of `localhost` in your configuration:

```
db-servers-hosts = "127.0.0.1:3306,127.0.0.1:3307"
db-servers-credential = "root:password"
```

Ensure database user privileges are granted for `127.0.0.1` rather than `localhost`:

```sql
GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' IDENTIFIED BY 'password';
```

**Reference**: `/pages/05.configuration/02.databases/docs.md:25`

---

### What are the minimum database versions required?

**Requirement**: **replication-manager** requires GTID-enabled replication.

**Supported versions:**
- MariaDB 10.0 or later
- MySQL 5.6 or later (with GTID enabled)
- Percona Server 5.6 or later (with GTID enabled)

**Configuration**: Ensure GTID is enabled on all database nodes:

```
gtid_mode = ON                    # MySQL
gtid_domain_id = 1               # MariaDB
enforce_gtid_consistency = ON    # MySQL
```

**Reference**: Installation documentation

---

### Why isn't HAProxy showing statistics correctly?

**Problem**: HAProxy statistics not appearing in **replication-manager** dashboard or incorrect connection counts.

**Cause**: HAProxy versions earlier than 1.7 do not provide complete statistics information.

**Solution**: Upgrade to HAProxy 1.7 or later. Verify HAProxy statistics socket is accessible:

```
haproxy-servers = "127.0.0.1:3310"
haproxy-write-port = 3306
haproxy-read-port = 3307
```

**Reference**: `/pages/05.configuration/06.routing/01.haproxy/docs.md`

---

### Why are my configuration changes not persisting after restart?

**Problem**: Changes made via the web UI or API are lost when **replication-manager** restarts.

**Cause**: Dynamic configuration saving is disabled by default.

**Solution**: Enable configuration persistence in the `[Default]` section of your config file:

```
[Default]
monitoring-save-config = true
```

With this enabled, changes are saved to `/var/lib/replication-manager/cluster_name/config.toml` (2.x) or `/root/.config/replication-manager/cluster_name/config.toml` (3.x).

**Reference**: `/pages/02.installation/02.configuration/docs.md:21`

---

### How do I migrate from version 2.x to 3.x?

**Major changes in 3.x:**

**Configuration location changes:**
- 2.x: `/etc/replication-manager/config.toml`
- 3.x: `/root/.config/replication-manager/config.toml` (active config)

**Docker volume requirements:**
```bash
# Add third volume mount for 3.x
docker run -v /home/repman/etc:/etc/replication-manager:rw \
           -v /home/repman/data:/var/lib/replication-manager:rw \
           -v /home/repman/config:/root/.config/replication-manager:rw
```

**Parameter renames** (partial list):
- `monitoring-config-rewrite` → `monitoring-save-config`
- `hosts` → `db-servers-hosts`
- `rpluser` → `replication-credential`
- `prefmaster` → `db-servers-prefered-master`

**Migration steps:**
1. Backup configuration and data directories
2. Run parameter rename script on config files
3. Update Docker volume mounts if using containers
4. Install 3.x and verify configuration syntax
5. Test in non-production environment first

**Reference**: `/pages/02.installation/04.migration/docs.md`

---

### What's the minimum configuration needed to start monitoring?

**Version 3.x** uses a two-part structure: `[Default]` section for global settings and separate cluster sections.

**Minimal configuration for single cluster:**

```
[Default]
monitoring-save-config = true
include = "/etc/replication-manager/cluster.d"

[cluster1]
title = "cluster1"
prov-orchestrator = "onpremise"
db-servers-hosts = "127.0.0.1:3306,127.0.0.1:3307"
db-servers-prefered-master = "127.0.0.1:3306"
db-servers-credential = "root:password"
replication-credential = "repl_user:repl_password"
```

**Required cluster parameters:**
- `title`: Cluster name
- `prov-orchestrator`: Provisioning orchestrator (typically "onpremise")
- `db-servers-hosts`: Comma-separated database server list
- `db-servers-prefered-master`: Preferred master for elections
- `db-servers-credential`: Database admin credentials (user:password)
- `replication-credential`: Replication user credentials (user:password)

**Alternative (legacy style)**: For simple deployments, cluster parameters can be in `[Default]` section:

```
[Default]
title = "ClusterTest"
db-servers-hosts = "127.0.0.1:3306,127.0.0.1:3307"
db-servers-credential = "root:password"
replication-credential = "repl_user:repl_password"
failover-mode = "manual"
```

**Reference**: `/pages/02.installation/02.configuration/docs.md:27`

---

## Replication & Synchronization

### Does semi-sync replication guarantee no data loss after a master crash?

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

### Why does my switchover fail after long write inactivity?

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

### What's the difference between AFTER_SYNC and AFTER_COMMIT?

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

### Can SUPER privileged users write during switchover on MariaDB?

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

### Why does MySQL hang during shutdown with GTID enabled?

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

### What happens when semi-sync reaches timeout?

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

### Why can't relay slaves reconnect after their master dies in multi-tier topology?

**Problem**: Relay slaves cannot automatically reconnect in multi-tier replication when their intermediate master fails.

**Cause**: **replication-manager** does not automatically manage relay node failures in multi-tier topologies.

**Limitation**: If you have:
```
Master → Relay → Slave
```

And the Relay node dies, the Slave cannot automatically reconnect to Master.

**Workaround**: Manually repoint slaves to the new topology after relay node failure.

**Design consideration**: Multi-tier topologies require additional operational procedures for relay node failures.

**Reference**: `/pages/05.configuration/05.replication/docs.md`

---

### What does "server-id 1000" reserved mean?

**Restriction**: Do not use `server-id = 1000` on any database node in your cluster.

**Reason**: **replication-manager** reserves `server-id = 1000` for binlog server operations during crash recovery.

**Impact**: Using server-id 1000 in your cluster will cause:
- Replication errors during backup operations
- Binlog server conflicts
- Crash recovery failures

**Solution**: Use any server-id except 1000. Common practice is sequential IDs: 3306, 3307, 3308, etc.

**Reference**: `/pages/05.configuration/03.failover/02.crash-recovery/docs.md`

---

## Failover & Switchover

### How does replication-manager prevent false positive failovers?

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

### What happens when the entire cluster goes down?

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

### Why did my failover get rejected?

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

### How long should I wait between failover attempts?

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

### What does "failover-at-sync" mean for data safety?

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

### Why is my switchover stuck or timing out?

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

### When will replication-manager NOT perform automatic failover?

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

## Topology & Deployment

### Why are two-node clusters not recommended?

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

### What are the limitations of Galera cluster support?

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

### How does multi-master prevent split-brain?

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

### What happens if a relay slave crashes in multi-tier topology?

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

---

## Security & Configuration

### How do I change the default API credentials?

**Default credentials**: `admin:repman` (INSECURE)

**Security risk**: Default credentials must be changed before production use.

**Configuration parameter:**

```
[Default]
api-credentials = "myuser:mypassword"
```

**Effect**:
- All API and CLI access requires new credentials
- Web UI login uses new credentials
- CLI clients will prompt for password unless specified

**CLI usage with custom credentials:**
```
replication-manager-cli --user=myuser --password=mypassword status
```

**Best practice**: Use encrypted passwords (see password encryption question below).

**Reference**: `/pages/05.configuration/07.security/docs.md:31`

---

### How do I encrypt passwords in configuration files?

**Three-step process:**

**Step 1: Generate encryption key** (as root)
```
replication-manager keygen
```

This creates an encryption key accessible only to root.

**Step 2: Encrypt your password**
```
replication-manager password secretpass
```

Output:
```
Encrypted password hash: 50711adb2ef2a959577edbda5cbe3d2ace844e750b20629a9bcb
```

**Step 3: Use encrypted password in config**
```
db-servers-credential = "root:50711adb2ef2a959577edbda5cbe3d2ace844e750b20629a9bcb"
replication-credential = "repl:50711adb2ef2a959577edbda5cbe3d2ace844e750b20629a9bcb"
```

**Automatic decryption**: When **replication-manager** starts and detects the encryption key, passwords are automatically decrypted.

**Security**: Encryption key is only readable by root, providing basic password obfuscation.

**Reference**: `/pages/05.configuration/07.security/docs.md:6`

---

### How do I integrate with HashiCorp Vault?

**Two modes available:**

**Mode 1: config_store_v2** (store credentials in Vault secret)

```
[mycluster]
vault-server-addr = "http://vault.example.com:8200"
vault-auth = "approle"
vault-role-id = "your-role-id"
vault-secret-id = "your-secret-id"
vault-mode = "config_store_v2"
vault-mount = "kv"
db-servers-credential = "applications/repman"
replication-credential = "applications/repman"
```

Create Vault secret:
```
vault kv put kv/applications/repman \
  db-servers-credential=root:password \
  replication-credential=repl:password
```

**Mode 2: database_engine** (automatic password rotation)

```
[mycluster]
vault-mode = "database_engine"
db-servers-credential = "database/static-creds/repman-monitor"
replication-credential = "database/static-creds/repman-replication"
```

Configure Vault database role:
```
vault write database/config/my-mysql-database \
    plugin_name=mysql-database-plugin \
    connection_url="{{username}}:{{password}}@tcp(127.0.0.1:3306)/" \
    allowed_roles="repman-monitor,repman-replication" \
    username="vaultuser" \
    password="vaultpass"

vault write database/static-roles/repman-monitor \
    db_name=my-mysql-database \
    username="repman" \
    rotation_period=600
```

**Automatic rotation**: Vault rotates passwords at specified interval; **replication-manager** fetches new passwords on authentication errors.

**Reference**: `/pages/05.configuration/07.security/docs.md:123`

---

### Do I need to replace the self-signed certificates?

**Yes, for production deployments.**

**Default behavior**: **replication-manager** ships with self-signed certificates for HTTPS and API access.

**Security risk**: Self-signed certificates:
- Can be MitM attacked
- Generate browser warnings
- Don't validate server identity

**Configuration parameters:**

```
[Default]
monitoring-ssl-cert = "/path/to/your/server.crt"
monitoring-ssl-key = "/path/to/your/server.key"
```

**Generate proper certificates:**

```bash
# RSA certificate
openssl genrsa -out server.key 2048
openssl req -new -x509 -sha256 -key server.key -out server.crt -days 3650

# ECDSA certificate (recommended)
openssl ecparam -genkey -name secp384r1 -out server.key
openssl req -new -x509 -sha256 -key server.key -out server.crt -days 3650
```

**Best practice**: Use certificates from your organization's PKI or a trusted CA like Let's Encrypt.

**Reference**: `/pages/05.configuration/07.security/docs.md:40`

---

## Proxies & Routing

### Why aren't ProxySQL users syncing automatically?

**Problem**: Database users not appearing in ProxySQL configuration.

**Cause**: User bootstrap feature not enabled or configured incorrectly.

**Configuration parameter:**

```
proxysql-bootstrap-users = true
```

**Behavior when enabled:**
- **replication-manager** discovers users from database
- Automatically pushes users to ProxySQL
- Syncs on topology changes

**Manual mode** (`proxysql-bootstrap-users = false`):
- You manage ProxySQL users manually
- **replication-manager** doesn't modify user configuration
- More control, more operational overhead

**Troubleshooting**:
- Verify ProxySQL admin credentials configured correctly
- Check `proxysql-servers` parameter points to admin interface
- Review ProxySQL logs for connection errors

**Reference**: `/pages/05.configuration/06.routing/01.proxysql/docs.md`

---

### What's the difference between ProxySQL bootstrap-users and manual configuration?

**Bootstrap mode** (`proxysql-bootstrap-users = true`):
- **replication-manager** discovers database users automatically
- Pushes users to ProxySQL configuration
- Updates users on topology changes
- Simplifies user management
- Good for dynamic environments

**Manual mode** (`proxysql-bootstrap-users = false`):
- You configure ProxySQL users yourself
- **replication-manager** only manages host groups and routing
- Full control over user attributes
- Prevents accidental user changes
- Better for static, audited environments

**Recommendation**:
- Use bootstrap for development/testing
- Use manual for production with strict change control

**Reference**: `/pages/05.configuration/06.routing/01.proxysql/docs.md`

---

### Why does MaxScale have a monitoring delay?

**Problem**: MaxScale's internal monitor has built-in delays before detecting topology changes.

**Cause**: MaxScale uses polling intervals to detect master failures and slave status changes, typically 1-2 seconds.

**Impact during failover:**
- MaxScale may route traffic to failed master briefly
- Delay between **replication-manager** promotion and MaxScale awareness
- Client connection errors during transition

**Solution**: MaxScale integration with shortcutting:

**replication-manager** can update MaxScale directly via its API, bypassing monitor delays.

**Configuration:**
```
maxscale-servers = "127.0.0.1:3306"
maxscale-write-port = 3306
maxscale-read-port = 3307
```

**Benefit**: Near-instant routing updates during switchover/failover.

**Reference**: `/pages/05.configuration/06.routing/02.maxscale/docs.md`

---

### Which proxy should I use?

**Decision matrix:**

**HAProxy:**
- Layer 4 TCP routing
- Very fast, low overhead
- No query inspection
- No connection pooling
- Best for: Simple routing, high performance

**ProxySQL:**
- Layer 7 MySQL protocol
- Query caching and rewriting
- Connection pooling
- Query routing based on patterns
- Best for: Complex routing, read/write split, query optimization

**MaxScale:**
- Layer 7 MySQL protocol
- Advanced routing rules
- Query firewall
- Connection pooling
- Best for: Enterprise features, fine-grained control

**Consul:**
- Service discovery
- DNS-based routing
- Application-level failover
- Best for: Microservices, dynamic environments

**Recommendation**: Start with HAProxy for simplicity, move to ProxySQL for advanced features.

**Reference**: Configuration documentation for each proxy type

---

## Recovery & Data Loss

### How do I recover a failed master?

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

### When should I use flashback vs mysqldump recovery?

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

### Where can I find crash information after failover?

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

---

## Monitoring & Troubleshooting

### How do I increase logging for debugging?

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

### Why is performance schema monitoring causing overhead?

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

### How do I check internal status for support tickets?

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

## Operational Best Practices

### What are the recommended parallel replication settings?

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

### What are the recommended semi-sync settings?

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

### Should I enable dynamic best practice enforcement?

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

### What backup strategy should I use?

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
