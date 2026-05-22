---
title: Proxies & Routing
taxonomy:
    category: docs
---

### 15.6.1 Why aren't ProxySQL users syncing automatically?

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

### 15.6.2 What's the difference between ProxySQL bootstrap-users and manual configuration?

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

### 15.6.3 Why does MaxScale have a monitoring delay?

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

### 15.6.4 Which proxy should I use?

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
