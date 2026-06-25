---
title: Multi node monitoring
taxonomy:
    category: docs
---

### 6.2.5.0.1 Active standby with external arbitrator

When running inside a single datacenter, a single replication-manager instance with keepalived, corosync, openha, or etcd is usually sufficient for failover. When running across two datacenters, you can run two replication-manager instances in an active/standby pair. Both instances exchange heartbeats over HTTP via the replication-manager API, and an external arbitrator service resolves split-brain situations.

**Architecture overview:**

- **Peer heartbeat** — each replication-manager instance polls the other's `/api/heartbeat` endpoint on the standard API port. This traffic stays on the local network or VPN. No authentication is required (the endpoint is unprotected).
- **Arbitrator** — a lightweight external service (`replication-manager-arb`) that both instances contact when a split brain is detected. The arbitrator decides which instance becomes active based on heartbeat reports. The arbitrator can be a shared public service behind a TLS reverse proxy.

Make sure the web server of **replication-manager** is active on both instances — the peer heartbeat uses the API port.

---

### 6.2.5.0.2 Configuration

To configure active standby and arbitration, use the following settings on each **replication-manager**:

##### `arbitration-external` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Enable external arbitration on split brain. |
| Type | Boolean |
| Default Value | false |

##### `arbitration-external-hosts` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Address of the external arbitrator service. Supports `https://` prefix for TLS. |
| Type | String |
| Default Value | "88.191.151.84:80" |

When using a TLS reverse proxy in front of the arbitrator, prefix the address with `https://`:

```toml
arbitration-external-hosts = "https://arbitrator.signal18.io"
```

Without a scheme prefix, `http://` is used by default — no breaking change for existing configurations.

##### `arbitration-external-secret` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Shared secret used by the arbitrator to identify your cluster. Should be unique across all users of the arbitrator. Use your organization name combined with random alphanumeric characters. |
| Type | String |
| Default Value | "" |

##### `arbitration-external-unique-id` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Unique identifier for each replication-manager instance. Each instance in the pair must have a different value. |
| Type | Integer |
| Default Value | 0 |

##### `arbitration-peer-hosts` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Address of the peer replication-manager node. Points to the peer's API port. |
| Type | String |
| Default Value | "127.0.0.1:10001" |

Peer communication typically stays on the local network or VPN, so HTTP is sufficient:

```toml
arbitration-peer-hosts = "192.168.1.20:10005"
```

##### `arbitration-failed-master-script` (2.1)

| Item | Value |
| ---- | ----- |
| Description | External script executed when a master loses arbitration during split brain. |
| Type | String |
| Default Value | "" |

##### `arbitration-read-timeout` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Read timeout for arbitrator responses in milliseconds. Should be shorter than `monitoring-ticker` (in seconds) to avoid overloading the monitoring loop. |
| Type | Integer |
| Default Value | 800 |

---

### 6.2.5.0.3 Setup example

Give each instance a different `arbitration-external-unique-id` and point each to its peer:

**Instance A** (`192.168.1.10:10005`):
```toml
arbitration-external = true
arbitration-external-hosts = "https://arbitrator.signal18.io"
arbitration-external-secret = "myorg-a7x9k2m"
arbitration-external-unique-id = 1
arbitration-peer-hosts = "192.168.1.20:10005"
```

**Instance B** (`192.168.1.20:10005`):
```toml
arbitration-external = true
arbitration-external-hosts = "https://arbitrator.signal18.io"
arbitration-external-secret = "myorg-a7x9k2m"
arbitration-external-unique-id = 2
arbitration-peer-hosts = "192.168.1.10:10005"
```

Both instances must use the same `arbitration-external-secret` and `arbitration-external-hosts`.

---

### 6.2.5.0.4 Startup behavior

When the first instance starts, the peer is not yet reachable. This triggers split-brain detection and the instance contacts the arbitrator:

```
INFO Arbitrator: External check requested
INFO Arbitrator say winner
```

The arbitrator grants active status to the first instance.

When the second instance starts, it detects the peer is already active and enters standby mode:

```
INFO No peer split brain setting status to S
```

If the active instance goes down, the standby detects the peer is unreachable (split brain), contacts the arbitrator, and is elected as the new active instance.

>Failover in a **replication-manager** cluster requires arbitration. If the arbitrator cannot be contacted, you can use the command line or API to failover manually — but make sure all other replication-manager instances are stopped first.

---

### 6.2.5.0.5 Active/standby toggle

The active/standby status can be toggled via the REST API or the dashboard.

**API:**
```
GET /api/clusters/{clusterName}/actions/set-active-status
```

This endpoint requires the `cluster-settings` ACL grant. It toggles the cluster between active and standby mode:

- **Without arbitration** — the toggle is a local-only operation. Useful for testing.
- **With arbitration** — the toggle forces an arbitrator election so the peer instance can take over as active.

**Dashboard:**

The active/standby status is displayed as a clickable pill in the cluster detail header. Clicking it opens a confirmation dialog before toggling. The pill is only clickable for users with the `cluster-settings` grant.

---

### 6.2.5.0.6 Alert suppression on standby

When a replication-manager instance is in standby mode, all alerts are suppressed — no emails, no Slack messages, no alert scripts. This prevents duplicate alerts from both instances in the pair. Alerts resume automatically when the instance becomes active.

---

### 6.2.5.0.7 Run a private arbitrator

The arbitrator is a separate binary (`replication-manager-arb`) that can be deployed independently.

##### `arbitrator-bind-address` (2.0 arb)

| Item | Value |
| ---- | ----- |
| Description | Arbitrator listen address and port. |
| Type | String |
| Default Value | "0.0.0.0:10001" |

##### `arbitrator-driver` (2.0 arb)

| Item | Value |
| ---- | ----- |
| Description | Arbitrator backend storage type. |
| Type | Enum |
| List Values | sqlite, mysql |
| Default Value | "sqlite" |

**SQLite backend** (default, simplest):

```toml
[arbitrator]
title = "arbitrator"
arbitrator-bind-address = "0.0.0.0:10001"
arbitrator-driver = "sqlite"

[default]
monitoring-datadir = "/var/lib/replication-manager"
```

Start:
```bash
replication-manager-arb arbitrator
```

**MySQL backend:**

```toml
[arbitrator]
title = "arbitrator"
arbitrator-bind-address = "0.0.0.0:10001"
arbitrator-driver = "mysql"
db-servers-hosts = "192.168.0.201:3306"
db-servers-credential = "user:password"

[default]
```

##### Health check

The arbitrator exposes a `GET /health` endpoint that returns HTTP 200 with `{"status":"ok"}` when the backend database is reachable, or HTTP 503 with `{"status":"failed"}` otherwise. Use this for load balancer health checks when running behind a reverse proxy.

##### Multi-tenant

A single arbitrator can serve multiple replication-manager pairs. Each pair is identified by its `arbitration-external-secret` and cluster name. There is no configuration needed on the arbitrator side — it stores heartbeats and arbitration decisions keyed by these identifiers.

##### TLS reverse proxy

The arbitrator itself listens on plain HTTP. To expose it securely over the internet, place it behind a TLS reverse proxy (nginx, HAProxy, Caddy, etc.). The replication-manager instances then use `https://` in `arbitration-external-hosts` to reach it.
