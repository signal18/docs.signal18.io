---
title: Network Ports
taxonomy:
    category: docs
---

## 2.7.1 Network Ports

This page lists all TCP/UDP ports that must be reachable between the components of a replication-manager deployment. Use it to configure firewall rules, security groups, and network policies.

---

## 2.7.2 replication-manager API and Dashboard

These ports are opened by the replication-manager process itself.

| Port | Protocol | Direction | Description |
|---|---|---|---|
| `10005` | TCP | Operator → repman | HTTPS REST API and web dashboard. Primary port for `replication-manager-cli`, Grafana, and browser access. Configured by `api-port`. |
| `10001` | TCP | Proxy → repman | HTTP endpoint used by HAProxy and external check scripts to query master/slave status. Configured by `http-port`. |

> For deployments where replication-manager binds port 443 directly, the `--user` privilege-drop option is required (see [Security Configuration Guide](/security/configuration-guide)).

---

## 2.7.3 Database Servers

| Port | Protocol | Direction | Description |
|---|---|---|---|
| `3306` | TCP | repman → DB | MySQL/MariaDB client port. Used for monitoring queries, failover, and replication setup. Configured by `db-servers-hosts`. |
| `3306` | TCP | DB → DB | MySQL/MariaDB replication stream (primary → replica). |
| `3307` | TCP | repman → DB | MariaDB/MySQL **extra port** (`extra_port` in `my.cnf`). **Strongly recommended** — replication-manager reduces `max_connections` to `1` during switchover to drain traffic; the extra port guarantees replication-manager retains its own monitoring connection throughout that critical window. Without it, a crash between the connection drain and the reconnect leaves the server unreachable. |
| `4444` | TCP | DB → DB | Galera SST (State Snapshot Transfer) — full node resync via XtraBackup/mariabackup. |
| `4567` | TCP/UDP | DB → DB | Galera replication traffic (group communication). |
| `4568` | TCP | DB → DB | Galera IST (Incremental State Transfer) — incremental resync between nodes. |

Add this to `my.cnf` on every monitored server to enable the extra port:

```ini
[mysqld]
extra_port = 3307
extra_max_connections = 10
```

Then set the corresponding parameter in `replication-manager.toml`:

```toml
db-servers-connect-timeout = 5
# replication-manager will try the extra port automatically when the main port is saturated
```

---

## 2.7.4 replication-manager SST / Backup Streaming

replication-manager implements its own **State Snapshot Transfer (SST)** mechanism for reseeding crashed or new replica nodes — separate from Galera SST. This uses a `socat`-based streaming pipeline:

1. replication-manager writes a job into the `replication_manager_schema.jobs` table on the donor node
2. The donor cron script streams the backup (mariabackup/xtrabackup xbstream) via `socat` back to replication-manager
3. replication-manager opens a TCP listener on the repman host to receive the stream
4. The joiner node (receiver) listens on **port 4444** by default

| Port | Protocol | Direction | Description |
|---|---|---|---|
| `4444` | TCP | repman → DB (joiner) | replication-manager SST receiver port on the joiner node. Fixed default; replication-manager connects here to push the restored data. |
| *dynamic* | TCP | DB (donor) → repman | Backup stream from donor to repman. replication-manager opens a listener on a random available port unless `scheduler-db-servers-sender-ports` is configured. |

> **Firewall recommendation:** If you cannot allow all ephemeral TCP ports from DB hosts to the replication-manager host, pin the receiver port pool with:
>
> ```toml
> scheduler-db-servers-sender-ports = "4000,4001,4002,4003"
> ```
>
> This restricts the ports replication-manager opens for incoming backup streams to the declared list, allowing precise firewall rules. Without this parameter, replication-manager picks any available port on the host — you must then allow all high TCP ports (1024–65535) from DB hosts to the repman host, or use a permissive security group between repman and DB hosts.

---

## 2.7.5 Proxies

### HAProxy

| Port | Protocol | Direction | Description |
|---|---|---|---|
| `3306` | TCP | Client → HAProxy | Write (master) frontend. Configured by `haproxy-write-port`. |
| `3307` | TCP | Client → HAProxy | Read (replica) frontend. Configured by `haproxy-read-port`. |

### ProxySQL

| Port | Protocol | Direction | Description |
|---|---|---|---|
| `6033` | TCP | Client → ProxySQL | MySQL protocol multiplexer port. Configured by `proxysql-port`. |
| `6032` | TCP | repman → ProxySQL | ProxySQL admin interface. Used by replication-manager to reconfigure routing on failover. Configured by `proxysql-admin-port`. |

### MaxScale

| Port | Protocol | Direction | Description |
|---|---|---|---|
| `3306` | TCP | Client → MaxScale | MySQL protocol listener (default). Configured per router in MaxScale config. |
| `3307` | TCP | Client → MaxScale | Optional read-only listener. |

---

## 2.7.6 Embedded Graphite Metrics

replication-manager ships an embedded Graphite/Carbon store. Since **v3.1.1** the render API is proxied through the HTTPS API (port `10005`) so **none of these ports need to be opened externally** unless you are connecting a Grafana instance directly to the Carbon API.

| Port | Protocol | Direction | Description |
|---|---|---|---|
| `2003` | TCP/UDP | repman (loopback) | Carbon plaintext receiver — metrics are written here by the monitoring loop. Configured by `graphite-carbon-port`. |
| `2004` | TCP | repman (loopback) | Carbon pickle receiver. Configured by `graphite-carbon-pickle-port`. |
| `7002` | TCP | repman (loopback) | Carbon link port. Configured by `graphite-carbon-link-port`. |
| `7007` | TCP | repman (loopback) | Carbon pprof port. Configured by `graphite-carbon-pprof-port`. |
| `10002` | TCP | Grafana → repman | Carbon render API — direct Grafana data source. Can be replaced by the proxied `/graphite/render` endpoint on port `10005`. Configured by `graphite-carbon-api-port`. |
| `10003` | TCP | repman (loopback) | Carbon HTTP server port. Configured by `graphite-carbon-server-port`. |

---

## 2.7.7 Multi-Node replication-manager (Arbitration)

When running multiple replication-manager instances for arbitration or HA:

| Port | Protocol | Direction | Description |
|---|---|---|---|
| `10001` | TCP | repman → repman | Heartbeat and arbitration peer communication. Configured by `arbitration-peer-hosts`. |

---

## 2.7.8 SSH Bastion (On-Premise)

When the HTTPS terminal bastion feature is enabled (`terminal-session-enabled = true`) and `onpremise-ssh = true`, replication-manager opens back-end SSH connections from the repman host to each database/proxy host. The operator's browser only needs access to the HTTPS API port.

| Port | Protocol | Direction | Description |
|---|---|---|---|
| `22` | TCP | repman → DB/Proxy | SSH connection for `bash` terminal sessions and on-premise operations. Configured by `onpremise-ssh-port`. |

---

## 2.7.9 Summary — Inbound Rules per Host

### replication-manager host

| Port | Open to |
|---|---|
| `10005` (HTTPS API) | Operator browsers, CLI clients, Grafana, external alert receivers |
| `10001` (HTTP) | Proxy health-check scripts (HAProxy etc.) |
| `10002` (Carbon API) | Grafana (only if not using the `/graphite/render` proxy) |
| `4000–4003` (or dynamic) | DB hosts — backup stream receivers. Pin range with `scheduler-db-servers-sender-ports`. |

### Database hosts

| Port | Open to |
|---|---|
| `3306` | replication-manager host, proxy hosts, other DB hosts (replication) |
| `3307` (extra port) | replication-manager host — reserved monitoring connection during switchover |
| `4444` | replication-manager host (SST joiner receiver) |
| `4444`, `4567`, `4568` | Other DB hosts (Galera clusters only) |
| `22` | replication-manager host (on-premise SSH bastion) |

### Proxy hosts (HAProxy / ProxySQL / MaxScale)

| Port | Open to |
|---|---|
| `3306` / `6033` | Application servers |
| `6032` | replication-manager host (ProxySQL admin) |
