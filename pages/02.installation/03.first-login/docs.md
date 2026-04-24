---
title: First Login
taxonomy:
    category: docs
---

## 2.5.1 First Login

replication-manager exposes **two HTTP listeners** that serve the dashboard and REST API. The one you use depends on your deployment topology:

| Listener | Default port | Protocol | Bind | Use case |
|----------|-------------|----------|------|----------|
| **API server** (`api-port`) | `10005` | HTTP or HTTPS | `0.0.0.0` | Direct browser and CLI access. Switches to HTTPS automatically when `monitoring-ssl-cert` is set. |
| **HTTP server** (`http-port`) | `10001` | HTTP only | `localhost` | Reverse-proxy deployments — TLS is terminated at the proxy (nginx, HAProxy, Traefik). Serves the full dashboard and API over plain HTTP on a local interface. |

### Which URL to open

| Deployment | URL |
|------------|-----|
| Default (no certificate) | `http://<host>:10005` |
| With TLS certificate | `https://<host>:10005` |
| Behind a reverse proxy | `https://<proxy-host>/` → proxy forwards to `http://localhost:10001` |

### API server modes

When `api-https-bind = true` the API server enforces HTTPS — protected endpoints (`/api/monitor`, Swagger, cluster operations, alerts) are **not** served on the HTTP server (port 10001). Only unauthenticated endpoints (health, proxy checks, login) remain available on port 10001 in that mode.

| `monitoring-ssl-cert` | `api-https-bind` | Port 10005 protocol | Port 10001 protected endpoints |
|-----------------------|-----------------|---------------------|-------------------------------|
| Not set (default) | `false` (default) | HTTP | Yes — full API |
| Set | `false` | HTTPS | Yes — full API |
| Set | `true` | HTTPS only | No — unauthenticated only |

### Binding configuration

| Parameter | Default | Description |
|-----------|---------|-------------|
| `api-port` | `10005` | Port for the API server (HTTP or HTTPS) |
| `api-bind` | `0.0.0.0` | IP address the API server binds to |
| `monitoring-ssl-cert` | `""` | Path to TLS certificate — activates HTTPS on port 10005 when set |
| `monitoring-ssl-key` | `""` | Path to TLS private key |
| `api-https-bind` | `false` | When `true`, drops protected endpoints from the HTTP server |
| `http-port` | `10001` | Port for the plain-HTTP server (reverse-proxy use) |
| `http-bind-address` | `localhost` | IP the plain-HTTP server binds to (loopback by default — not exposed externally) |

**Reverse-proxy example (nginx):**

```nginx
server {
    listen 443 ssl;
    server_name repman.example.com;

    ssl_certificate     /etc/ssl/repman.crt;
    ssl_certificate_key /etc/ssl/repman.key;

    location / {
        proxy_pass http://127.0.0.1:10001;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }
}
```

```toml
# replication-manager.toml
http-bind-address = "127.0.0.1"
http-port         = "10001"
api-https-bind    = true   # keep protected endpoints off the plain-HTTP port
```

---

## 2.5.2 Local Users

replication-manager ships with two built-in local accounts:

| Username | Default password | Default role |
|---|---|---|
| `admin` | `repman` | Full access — all cluster, proxy, provisioning, and global settings |
| `dba` | `repman` | Database operations — no provisioning, no global settings, no user management |

These accounts are defined by the `api-credentials` configuration key:

```toml
api-credentials = "admin:repman,dba:repman"
```

or via command-line flag:

```bash
replication-manager monitor --api-credentials "admin:repman,dba:repman"
```

**Change the default passwords immediately after first login.** Accounts using the default password `repman` trigger a cluster warning (see below).

---

## 2.5.3 Changing Passwords

Passwords can be changed from the **Settings → Users** panel in the GUI, or by updating `api-credentials` in the cluster TOML file and reloading.

Each entry in `api-credentials` follows the format `username:password`. Multiple accounts are separated by commas.

---

## 2.5.4 Default Password Warning — WARN0108

replication-manager continuously monitors whether the built-in accounts still use their default passwords. When either `admin` or `dba` retains the password `repman`, the cluster enters state:

```
WARN0108: Default users still use default password.
          Please change the credentials for users: (admin,dba)
```

This warning appears in the cluster dashboard and in the main HA log. It clears automatically once the passwords are updated.

The check is performed by `CheckDefaultUser()` on every monitoring tick — no restart is required after changing passwords.

---

## 2.5.5 SSO Login via gitlab.signal18.io

Once your instance is [registered](../registration), users can log in using their **gitlab.signal18.io** identity instead of a local password. SSO login does not require a local `api-credentials` entry — authentication is handled by the Signal18 GitLab OAuth flow.

To log in with SSO:

1. Click **Sign in with GitLab** on the replication-manager login page
2. Authenticate at gitlab.signal18.io (your Signal18 SSO account)
3. replication-manager validates your identity against the GitLab group membership for your registered instance namespace

The role and grants assigned to an SSO user are managed entirely through the replication-manager GUI and API — not through GitLab group roles. See [Cluster Role Sharing](../registration#cluster-role-sharing-with-external-users) for the full role and grant model.

---

## 2.5.6 Summary

| Step | Action |
|---|---|
| 1 | Open `https://<host>:10005` |
| 2 | Log in as `admin` / `repman` |
| 3 | Change the `admin` and `dba` passwords — clears WARN0108 |
| 4 | (Optional) Register the instance at gitlab.signal18.io to enable SSO and community plugins |
