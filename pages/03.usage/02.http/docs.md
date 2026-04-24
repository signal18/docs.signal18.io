---
title: Web Interface
taxonomy:
    category: docs
---

## 3.3.1 Web Interface

Once replication-manager is started in monitor mode it exposes **two HTTP listeners** that serve the dashboard and REST API. The one you use depends on your deployment topology.

| Listener | Default port | Protocol | Default bind | Use case |
|----------|-------------|----------|--------------|----------|
| **API server** (`api-port`) | `10005` | HTTP or HTTPS | `0.0.0.0` | Direct browser and CLI access. Switches to HTTPS automatically when `monitoring-ssl-cert` is set. |
| **HTTP server** (`http-port`) | `10001` | HTTP only | `localhost` | Reverse-proxy deployments — TLS is terminated at the proxy (nginx, HAProxy, Traefik). Serves the full dashboard and API over plain HTTP on the local interface. |

### Which URL to open

| Deployment | URL |
|------------|-----|
| Default (no certificate) | `http://<host>:10005` |
| With TLS certificate | `https://<host>:10005` |
| Behind a reverse proxy | `https://<proxy-host>/` → proxy forwards to `http://localhost:10001` |

It looks like this:

![mrmdash](/images/http.png)

---

## 3.3.2 Direct Access — API Server (port 10005)

The API server serves both the web dashboard and the REST API. Protocol depends on configuration:

- **HTTP** (default) — when no TLS certificate is configured
- **HTTPS** — when `monitoring-ssl-cert` and `monitoring-ssl-key` are set

When `monitoring-ssl-cert` is empty, replication-manager auto-generates a self-signed certificate. Browsers will warn on first visit — accept the certificate or install it in your trust store.

```toml
# Enable HTTPS on port 10005 with a real certificate
monitoring-ssl-cert = "/etc/ssl/repman/server.crt"
monitoring-ssl-key  = "/etc/ssl/repman/server.key"
api-bind            = "0.0.0.0"
api-port            = "10005"
```

---

## 3.3.3 Reverse-Proxy Access — HTTP Server (port 10001)

Port 10001 serves the full dashboard and API over plain HTTP. It is bound to `localhost` by default so it is not reachable from outside the host. A reverse proxy (nginx, HAProxy, Traefik) sits in front, terminates TLS, and forwards traffic to this port.

When `api-https-bind = true` the protected API endpoints (`/api/monitor`, cluster operations, alerts, Swagger) are **removed** from port 10001 — only unauthenticated endpoints remain (health, proxy checks, login). This is the recommended setting when running behind a reverse proxy so that bypassing the proxy does not expose the full API.

| `monitoring-ssl-cert` | `api-https-bind` | Port 10005 | Port 10001 protected endpoints |
|-----------------------|-----------------|------------|-------------------------------|
| Not set (default) | `false` (default) | HTTP, full API | Yes |
| Set | `false` | HTTPS, full API | Yes |
| Set | `true` | HTTPS only | No — unauthenticated only |

---

## 3.3.4 Binding Configuration Reference

| Parameter | Default | Description |
|-----------|---------|-------------|
| `api-port` | `10005` | Port for the API server (HTTP or HTTPS) |
| `api-bind` | `0.0.0.0` | IP address the API server binds to |
| `monitoring-ssl-cert` | `""` | Path to TLS certificate — activates HTTPS on `api-port` when set |
| `monitoring-ssl-key` | `""` | Path to TLS private key |
| `api-https-bind` | `false` | When `true`, drops protected endpoints from the HTTP server |
| `http-port` | `10001` | Port for the plain-HTTP server (reverse-proxy use) |
| `http-bind-address` | `localhost` | IP the plain-HTTP server binds to |

---

## 3.3.5 Reverse-Proxy Examples

### nginx — TLS termination with full API on port 10001

```nginx
server {
    listen 443 ssl;
    server_name repman.example.com;

    ssl_certificate     /etc/ssl/repman.crt;
    ssl_certificate_key /etc/ssl/repman.key;

    location / {
        proxy_pass         http://127.0.0.1:10001;
        proxy_set_header   Host $host;
        proxy_set_header   X-Real-IP $remote_addr;
        proxy_set_header   Upgrade $http_upgrade;
        proxy_set_header   Connection "upgrade";
    }
}
```

```toml
# replication-manager.toml
http-bind-address = "127.0.0.1"
http-port         = "10001"
api-https-bind    = true
```

### nginx — basic auth protecting the dashboard (legacy)

```nginx
server {
    server_name repman.dashboard;
    location / {
        auth_basic           "Login required";
        auth_basic_user_file conf/htpasswd;
        proxy_pass           http://localhost:10001;
    }
}
```

> This pattern predates the JWT-based authentication introduced in replication-manager 2.1. The built-in JWT login at `https://<host>:10005` is now preferred for direct access. Basic-auth nginx proxying is still valid when you need an additional authentication layer in front of the HTTP server.
