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

---

## Version 3.1.41 and 3.1.42 Proxy Addendum

The following notes describe proxy behavior introduced for the 3.1.x
documentation set. The original FAQ entries above remain unchanged.

### 15.6.5 What does HAProxy Runtime API bootstrap do? (3.1.41)

Set `haproxy-mode = "runtimeapi"` and
`haproxy-api-bootstrap-servers = true` to let replication-manager add, drain,
remove, and address-correct read and write backend members without a full
HAProxy reload.
This requires HAProxy 2.6 or newer.

The setting can be changed while the cluster is running, but the running proxy
keeps its last-provisioned behavior until it is reprovisioned. It is disabled
by default. A member without a resolved literal address cannot receive a
dynamic membership update.

**Reference**: `/pages/04.architecture/04.configuration-guide/06.routing/01.haproxy`

### 15.6.6 Which HAProxy mode should I use? (3.1.42)

- Use `runtimeapi` when replication-manager should manage live backend state.
- Use `standby` when HAProxy must run locally beside replication-manager.
- Use `externalcheck` when HAProxy should call replication-manager health
  endpoints and own the health decision.
- Treat `dataplaneapi` as the current external-check-style compatibility mode;
  full Data Plane API management is not implemented yet.

Changing `haproxy-mode` requires the proxy to be unprovisioned first.

### 15.6.7 Why did my external-check proxy keep using `/slave-status`? (3.1.42)

Newly provisioned external-check HAProxy configurations use `/reader-status`
for reader health. An existing proxy keeps its previously generated
`checkslave` script until it is reprovisioned. Reprovision the proxy after
upgrading to receive the current check endpoint.

### 15.6.8 Which MaxScale API and config mode should I use? (3.1.42)

Use the default `maxscale-rest-api = true` for MaxScale 2.2 and newer and set
`maxscale-rest-port` to the REST listener port. Disable REST only for MaxScale
versions older than 2.2; MaxAdmin was removed in MaxScale 2.5.

`maxscale-mode = "auto"` detects legacy or pinloki configuration syntax from
the MaxScale image tag. Use `legacy` or `pinloki` to override detection. These
settings select generated config syntax and are independent of REST versus
MaxAdmin client transport.

If `maxscale-get-info-method = "maxinfo"` is selected for a pinloki release,
replication-manager falls back to the supported method and reports
`WARN0211`.

### 15.6.9 Why does replication-manager not change MaxScale server states? (3.1.42)

With `maxscale-disable-monitor = false`, MaxScale's own monitor owns master,
slave, and running state. replication-manager reads that state and avoids
conflicting manual updates. This is the normal configuration.

Set `maxscale-disable-monitor = true` only when replication-manager must stop
the MaxScale monitor and drive server state itself.

### 15.6.10 Does Kubernetes provision every proxy type? (3.1.42)

No. Native Kubernetes proxy provisioning currently implements HAProxy and
ProxySQL. Other proxy types are not implemented yet.

Kubernetes proxy names must be lowercase RFC 1035 labels because the configured
proxy name becomes the Kubernetes Service name. IP addresses, dotted hostnames,
uppercase names, and names containing colons are rejected.

**Reference**: `/pages/10.provisioning/01.orchestrators/03.kubernetes`
