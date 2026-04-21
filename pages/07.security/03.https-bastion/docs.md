---
title: HTTPS Bastion and Terminal
taxonomy:
    category: docs
---

## 7.4.1 HTTPS Bastion and Terminal

replication-manager provides browser-based terminal access to every database node and proxy it manages — without opening SSH ports to browser clients. All terminal traffic is carried over a **WebSocket connection tunnelled through the existing HTTPS API**, so the replication-manager TLS endpoint is the only port that needs to be reachable from the operator's browser.

This makes replication-manager act as a **secure HTTPS bastion**: it authenticates the user, enforces ACL and role checks, and proxies the terminal I/O to the target host over a back-end SSH connection or, for OpenSVC-provisioned services, via the OpenSVC agent's terminal server.

---

## 7.4.2 Terminal Types

Four command types are available for each database server and proxy:

| Command | Description |
|---|---|
| `bash` | SSH shell to the target host (or OpenSVC container shell via gotty/tty-share) |
| `mysql` | Interactive `mysql` client connected to the target database |
| `mytop` | `mytop` live process monitor connected to the target database |
| *(global)* | Bash shell on the replication-manager host itself (admin/cloud18 user only) |

---

## 7.4.3 WebSocket API Endpoints

All terminal connections use the WebSocket protocol (upgraded from HTTPS GET requests). The browser or API client connects over WSS and receives a prompt for a JWT token as the first message.

```
# Global terminal (replication-manager host shell)
GET /api/terminal/connect

# Database server terminal
GET /api/terminal/connect/clusters/{clusterName}/servers/{serverName}
GET /api/terminal/connect/clusters/{clusterName}/servers/{serverName}/{command}

# Proxy terminal
GET /api/terminal/connect/clusters/{clusterName}/proxies/{serverName}
GET /api/terminal/connect/clusters/{clusterName}/proxies/{serverName}/{command}
```

Where `{command}` is one of `bash`, `mysql`, or `mytop`. Omitting `{command}` defaults to `bash`.

### 7.4.3.1 OpenSVC container selector (bash only)

For bash terminals on OpenSVC-provisioned clusters, the `rid` query parameter selects which container to open the shell into:

| `rid` value | Target |
|---|---|
| `container#db` | Database container (default) |
| `container#jobs` | Jobs/maintenance container |

```
GET /api/terminal/connect/clusters/mycluster/servers/db1?rid=container#jobs
```

### 7.4.3.2 List active sessions

```
GET /api/terminals
GET /api/clusters/{clusterName}/terminals
```

Returns the list of terminal sessions currently open for the authenticated user.

---

## 7.4.4 Authentication Flow

1. Browser upgrades the HTTPS connection to a WebSocket
2. replication-manager sends: `Connected. Waiting for token…`
3. Client sends the JWT Bearer token obtained from the normal API login (`/api/login`)
4. replication-manager validates the JWT signature (RSA) and extracts the user identity
5. ACL check: the user's roles are validated against the cluster's `api-credentials-acl-allow`
6. On success, the terminal session starts; on failure the connection is closed with an error message

Because the JWT is sent as the first WebSocket message (not as an HTTP header), the authentication hand-off is fully contained within the encrypted WebSocket channel.

---

## 7.4.5 Role-Based Database Credential Selection

For `mysql` and `mytop` terminals, replication-manager selects which database user to connect with based on the authenticated API user's role:

| Role / Grant | DB user used |
|---|---|
| `sysops` | Cluster root user (`db-servers-credential`) — full access |
| `sponsor` | Sponsor user (`cloud18-sponsor-user-credentials`) — read-only/limited |
| `dbops`, `extsysops`, `extdbops`, or `terminal-db` grant | DBA user (`cloud18-dba-user-credentials`) |

If the sponsor or DBA user does not exist yet on the target server, replication-manager creates it automatically before opening the session.

For `bash` terminals, the connection uses the on-premise SSH credential configured for the cluster (`onpremise-ssh-credential`).

---

## 7.4.6 Back-End Transport

### 7.4.6.1 Non-OpenSVC clusters (on-premise / SSH)

replication-manager opens a direct SSH connection from the replication-manager host to the database or proxy host. The operator's browser never talks to the SSH port — only replication-manager does.

```
Browser ──WSS──► replication-manager ──SSH──► database host
```

Required configuration:

```toml
onpremise-ssh            = true
onpremise-ssh-port       = 22
onpremise-ssh-credential = "root:"          # user:password or user: (key auth)
onpremise-ssh-private-key = "/etc/replication-manager/id_rsa"  # optional
```

### 7.4.6.2 OpenSVC clusters

For services provisioned by OpenSVC, replication-manager queries the OpenSVC API for the terminal URL served by the OpenSVC agent running alongside the database container.

Depending on the OpenSVC agent version:

| Agent version | Protocol | Binary |
|---|---|---|
| v2 | [gotty](https://github.com/sorenisanerd/gotty) | `gotty-client` |
| v1 | [tty-share](https://github.com/elisescu/tty-share) | `tty-share` |

```
Browser ──WSS──► replication-manager ──gotty/tty-share──► OpenSVC agent ──► container
```

Configure the client binary paths if not in the system PATH:

```toml
backup-gotty-client-path = "/usr/local/bin/gotty-client"
tty-share-binary-path    = "/usr/local/bin/tty-share"
```

---

## 7.4.7 Session Resume

When `terminal-session-resume` is enabled, disconnecting from a terminal does not kill the shell process — it is kept alive in a `tmux` or `screen` session on the replication-manager host (for global terminals) or on the remote host. Reconnecting to the same endpoint re-attaches to the existing session.

Session state is persisted across replication-manager restarts in `{monitoring-datadir}/tty.state.json`.

| Setting | Default | Effect |
|---|---|---|
| `terminal-session-resume` | `false` | Keep shell alive after disconnect and allow re-attach |
| `terminal-session-manager` | `"tmux"` | Multiplexer to use: `tmux` or `screen` |

---

## 7.4.8 Configuration Reference

### 7.4.8.1 Enabling the feature

Terminal access is **disabled by default** and must be explicitly enabled. In non-OpenSVC environments it also requires setting the flag at the server (non-cluster) level:

```toml
terminal-session-enabled = true
```

For OpenSVC environments the flag can be set per cluster. For other orchestrators it is forced to `false` unless set at the server level.

### 7.4.8.2 All terminal settings

##### `terminal-session-enabled`

| | |
|---|---|
| Description | Enable the browser terminal bastion. Must be set to `true` to allow WebSocket terminal connections. Defaults to `false` on non-OpenSVC clusters unless set at the global server level. |
| Type | Boolean |
| Default | `false` |

##### `terminal-session-resume`

| | |
|---|---|
| Description | Keep the shell process alive after the WebSocket disconnects and allow re-attach on reconnect. Requires `tmux` or `screen` on the replication-manager host for global terminals. |
| Type | Boolean |
| Default | `false` |

##### `terminal-session-manager`

| | |
|---|---|
| Description | Multiplexer used to host resumable sessions. |
| Type | String (`tmux` \| `screen`) |
| Default | `"tmux"` |

##### `onpremise-ssh`

| | |
|---|---|
| Description | Connect to database and proxy hosts over SSH for `bash` terminal sessions (and for other on-premise operations). |
| Type | Boolean |
| Default | `false` |

##### `onpremise-ssh-port`

| | |
|---|---|
| Description | SSH port on target hosts. |
| Type | Integer |
| Default | `22` |

##### `onpremise-ssh-credential`

| | |
|---|---|
| Description | `user:password` for SSH authentication. If password is empty, key-based authentication is used. |
| Type | String |
| Default | `"root:"` |

##### `onpremise-ssh-private-key`

| | |
|---|---|
| Description | Path to the SSH private key. If empty, replication-manager looks for keys in the running user's `~/.ssh/` directory. |
| Type | String |
| Default | `""` |

##### `backup-gotty-client-path`

| | |
|---|---|
| Description | Path to the `gotty-client` binary used for OpenSVC v2 agent terminal sessions. If empty, the binary is located via `PATH`. |
| Type | String |
| Default | `""` |

##### `tty-share-binary-path`

| | |
|---|---|
| Description | Path to the `tty-share` binary used for OpenSVC v1 agent terminal sessions. If empty, `tty-share` is located via `PATH`. |
| Type | String |
| Default | `""` |

---

## 7.4.9 ACL and Grants

Terminal grants can be added to any API user independently of their base role. Three granular grants are available:

| Grant | Allows |
|---|---|
| `terminal-db` | Open `bash`, `mysql`, and `mytop` terminals to database servers |
| `terminal-proxy` | Open `bash` and `mysql` terminals to proxy servers |
| `terminal-global` | Open a shell on the replication-manager host itself |

Add grants in `api-credentials-acl-allow`:

```toml
api-credentials-acl-allow = "admin:cluster db proxy prov global grant show sale extrole terminal,dba:cluster proxy db terminal-db terminal-proxy,readonly:"
```

The `terminal` keyword in the ACL grants all three terminal grants at once. Individual grants (`terminal-db`, `terminal-proxy`, `terminal-global`) allow finer control.

---

## 7.4.10 Security Notes

- **No SSH port exposure** — the browser only connects to the HTTPS API port. The SSH connection is made server-side by replication-manager.
- **JWT authentication on every connection** — the WebSocket channel is authenticated using the same RSA-signed JWT as the REST API; there is no separate credential.
- **Role-enforced DB credentials** — users never supply a database password in the terminal; replication-manager injects the appropriate credential based on the user's role.
- **Disabled by default** — `terminal-session-enabled = false` until explicitly turned on.
- **Audit trail** — all terminal session starts and ACL rejections are written to the replication-manager daemon log.
