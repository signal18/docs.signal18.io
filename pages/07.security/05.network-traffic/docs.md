---
title: Network Traffic
taxonomy:
    category: docs
---

## 7.5.1 Overview

This page describes the network flows that must be permitted for a replication-manager deployment to function. It covers internal cluster traffic and the outbound connections made to Signal18 cloud services when Cloud18 registration is active.

For the complete list of **inbound ports** that must be opened on each host type see [Network Ports](/installation/network-ports).

---

## 7.5.2 Internal Cluster Traffic

The following flows are required between the components of a self-hosted deployment. All of these are described in detail in [Network Ports](/installation/network-ports).

| Source | Destination | Port | Protocol | Purpose |
|--------|------------|------|----------|---------|
| Operator / browser | replication-manager | `10005` | HTTPS | REST API and web dashboard |
| replication-manager CLI | replication-manager | `10005` | HTTPS | CLI commands |
| Proxy health-check | replication-manager | `10001` | HTTP | HAProxy backend check |
| replication-manager | Database servers | `3306` | TCP/MySQL | Monitoring, failover, replication setup |
| replication-manager | Database servers | `3307` | TCP/MySQL | Extra port — reserved monitoring connection during switchover |
| Database | Database | `3306` | TCP/MySQL | Replication stream (primary → replica) |
| Database | replication-manager | dynamic / `4000–4003` | TCP | Backup stream (SST donor → repman) |
| replication-manager | Database (joiner) | `4444` | TCP | SST restore receiver |
| Database | Database | `4444`, `4567`, `4568` | TCP/UDP | Galera SST/IST/group communication |
| replication-manager | Proxies | `6032` | TCP | ProxySQL admin — routing updates on failover |
| replication-manager | Database servers | `22` | TCP/SSH | On-premise SSH bastion (if enabled) |

> **Firewall recommendation for backup streams:** pin the port pool with `scheduler-db-servers-sender-ports = "4000,4001,4002,4003"` to avoid having to allow all high TCP ports from DB hosts to the repman host.

---

## 7.5.3 Outbound Traffic to Signal18 Cloud Services

When `cloud18 = true`, replication-manager initiates outbound HTTPS connections to several Signal18-operated services. All traffic is **outbound from the repman host** — no inbound ports need to be opened on the repman host for Cloud18 to function.

### 7.5.3.1 CRM API

The CRM (Customer Relationship Manager) API handles registration, subscription management, and URI lifecycle.

| Destination | Port | Protocol | When |
|------------|------|----------|------|
| `api.crm.ovh-fr-2.signal18.cloud18.io` | `443` | HTTPS | Registration, confirm, unregister, subscription plan changes |

**Endpoints called:**

| Endpoint | Trigger |
|----------|---------|
| `POST /api/register` | New instance registration (step 1) |
| `POST /api/register/confirm` | Email confirmation polling (step 2) |
| `POST /api/unregister` | URI unregistration |
| `GET /api/subscription` | Subscription plan query |
| `POST /api/subscription` | Subscription plan change |
| `GET /api/subscription/plans` | Plan catalog fetch |

The CRM API base URL is configurable via `cloud18-crm-api-url` if you are running a private CRM deployment.

### 7.5.3.2 GitLab (Backoffice SSO and Config Sync)

GitLab at `gitlab.signal18.io` is the identity provider and configuration repository backend. replication-manager communicates with it continuously when Cloud18 is active.

| Destination | Port | Protocol | When |
|------------|------|----------|------|
| `gitlab.signal18.io` | `443` | HTTPS | All GitLab API calls and Git operations |

**Traffic patterns:**

| Operation | Frequency | Description |
|-----------|-----------|-------------|
| OAuth token exchange | On connect / token refresh | Exchanges GitLab username + password for an OAuth2 token |
| Personal access token rotation | On connect | Creates or refreshes a PAT named `domain-subdomain-zone` |
| Group and project lookup | On connect | Verifies the domain group and config projects exist; creates them if not |
| Config push (`git push`) | Every monitoring tick | Pushes the current cluster configuration to the main GitLab project |
| Config pull (`git pull`) | Every `git-monitoring-ticker` interval | Pulls configuration updates from the pull-mirror project |
| Plugin manifest fetch | On startup / periodic | Downloads the plugin catalog and updated plugin binaries |

All Git traffic uses HTTPS (port 443) — no SSH git transport is used.

### 7.5.3.3 Signal18 Meet (Alert Forwarding)

Cluster alerts are forwarded to the Signal18 operations team via an incoming webhook on the self-hosted Mattermost instance.

| Destination | Port | Protocol | When |
|------------|------|----------|------|
| `meet.signal18.io` | `443` | HTTPS | ALERT and ALERTOK log-level events (non-free plans only) |

Alert forwarding is gated:

- **Free plan** — no outbound alert traffic to `meet.signal18.io`
- **Support, Support+Services, Partner** — ALERT/ALERTOK events POST to `https://meet.signal18.io/hooks/…`

The webhook URL is configurable via `cloud18-alert-slack-url`. Alert forwarding can be disabled entirely with `cloud18-alert = false`.

---

## 7.5.4 Summary — Outbound Rules for Cloud18

If your repman host runs behind an egress firewall or proxy, allow the following outbound flows when `cloud18 = true`:

| Destination FQDN | Port | Protocol | Required for |
|-----------------|------|----------|-------------|
| `api.crm.ovh-fr-2.signal18.cloud18.io` | `443` | HTTPS | Registration, subscription management |
| `gitlab.signal18.io` | `443` | HTTPS | SSO, config sync, plugin distribution |
| `meet.signal18.io` | `443` | HTTPS | Alert forwarding (non-free plans only) |

No inbound ports need to be opened on the repman host for Cloud18 to function. All communication is initiated outbound by replication-manager.

---

## 7.5.5 DNS Requirements

All Signal18 cloud service FQDNs must be resolvable from the repman host. If the host uses a split-horizon DNS or restricts external resolution, ensure the following names resolve correctly:

- `api.crm.ovh-fr-2.signal18.cloud18.io`
- `gitlab.signal18.io`
- `meet.signal18.io` (non-free plans)
