---
title: Installation & Setup
taxonomy:
    category: docs
---

### 15.1.1 Why does connection fail when using "localhost" as the database host?

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

### 15.1.2 What are the minimum database versions required?

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

### 15.1.3 Why isn't HAProxy showing statistics correctly?

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

### 15.1.4 Why are my configuration changes not persisting after restart?

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

### 15.1.5 How do I migrate from version 2.x to 3.x?

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

### 15.1.6 What's the minimum configuration needed to start monitoring?

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
