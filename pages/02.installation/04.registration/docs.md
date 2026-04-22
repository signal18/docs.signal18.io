---
title: Registration & SSO
taxonomy:
    category: docs
---

## 2.5.1 Overview

Registering your replication-manager instance with the Signal18 DBAaS platform links it to a **GitLab identity** at [gitlab.signal18.io](https://gitlab.signal18.io). Registration is required to use:

- **Config backup and restore** — all cluster configurations versioned in a private GitLab repository, recoverable on any new instance in one command
- **Community plugins** — the full library of workload, security, and score plugins, kept up to date automatically
- **Cluster role sharing** — grant scoped access to any cluster to other registered SSO users without sharing credentials or VPN
- **Cloud18 marketplace** — consume clusters provided by other participants or publish your own
- **Direct chat** — real-time messaging with the Signal18 team and partners

---

## 2.5.2 Concepts — Domain, Subdomain, Zone

Every registered instance is identified by three slugs that together form a unique cluster slot:

| Field | Role | Example |
|---|---|---|
| `domain` | Organisation or company namespace — maps to a **GitLab group** | `mycompany` |
| `subdomain` | Datacenter or environment label | `ovh` |
| `zone` | Cluster zone identifier | `fr-1` |

The three fields are combined as `domain.subdomain.zone` (the **URI**) when calling the API.

Inside GitLab the following objects are created:

| Object | Path |
|---|---|
| GitLab group | `gitlab.signal18.io/mycompany/` |
| Main config repository | `gitlab.signal18.io/mycompany/mycompany-ovh-fr-1.git` |
| Peer distribution repository | `gitlab.signal18.io/mycompany/mycompany-ovh-fr-1-pull.git` |

`subdomain.zone` must be unique within the same `domain`. `domain.subdomain.zone` is globally unique across all registered instances.

---

## 2.5.3 Registering via the API

Registration is performed by the **admin user** through the replication-manager REST API:

```bash
# Obtain an admin token first
TOKEN=$(curl -s -X POST https://repman-host:10005/api/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"repman"}' | jq -r .token)

# Register the instance
curl -X POST https://repman-host:10005/api/register \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "email":    "admin@mycompany.com",
    "password": "gitlab_password",
    "uri":      "mycompany.ovh.fr-1"
  }'
```

**Request fields:**

| Field | Required | Description |
|---|---|---|
| `email` | Yes | Email address for the new GitLab account |
| `password` | Yes | Password for the new GitLab account (min 8 chars) |
| `uri` | Yes | `domain.subdomain.zone` — all lowercase, alphanumeric and hyphens |

**Responses:**

| HTTP | Meaning |
|---|---|
| `201 Created` | Registration and connect succeeded — instance is live |
| `201 Created` + `connect_error` field | GitLab account and projects created but the connect step failed — see [2.5.4](#2-5-4-what-happens-on-success) |
| `400 Bad Request` | Invalid input (missing field, bad URI format, weak password) |
| `401 Unauthorized` | No valid JWT provided |
| `403 Forbidden` | Authenticated user is not `admin` |
| `409 Conflict` | Email or zone already registered |
| `502 Bad Gateway` | CRM API unreachable |

---

## 2.5.4 What Happens on Success

When the CRM API returns `201`, replication-manager automatically runs the **connect flow** without requiring a restart:

1. Sets `cloud18-domain`, `cloud18-sub-domain`, `cloud18-sub-domain-zone` from the URI
2. Stores the GitLab credentials (`cloud18-gitlab-user`, `cloud18-gitlab-password`)
3. Authenticates to `gitlab.signal18.io` with basic auth and obtains an OAuth token
4. Creates a **personal access token** named `domain-subdomain-zone` in GitLab
5. Idempotently creates the main and pull Git projects under the domain group
6. Sets `git-url` and `git-url-pull` in the running configuration
7. Clones the config repository into the working directory (if `monitoring-restore-config-on-start` is set)

After this, replication-manager continuously pushes configuration changes to GitLab on every tick — no restart required.

If step 3–7 fail (e.g. GitLab is temporarily unreachable), the response still returns `201` but includes a `connect_error` field. In that case trigger connect manually by setting `cloud18=true` in global settings once GitLab is reachable.

---

## 2.5.5 Registering via the CLI

```bash
replication-manager-cli register \
  --host repman-host \
  --port 10005 \
  --user admin \
  --email admin@mycompany.com \
  --uri mycompany.ovh.fr-1
```

The CLI prompts for the GitLab account password interactively (hidden input), then calls `POST /api/register` on the running replication-manager server. The server handles all GitLab communication — the CLI only needs access to the replication-manager API port.

---

## 2.5.6 Starting Fresh from GitLab (Restore)

To bootstrap a new replication-manager host from an existing GitLab config repository:

```toml
# config.toml
cloud18                          = true
cloud18-domain                   = "mycompany"
cloud18-sub-domain               = "ovh"
cloud18-sub-domain-zone          = "fr-1"
cloud18-gitlab-user              = "admin@mycompany.com"
cloud18-gitlab-password          = "gitlab_password"
monitoring-restore-config-on-start = true
```

Or as command-line flags:

```bash
replication-manager monitor \
  --cloud18 \
  --monitoring-restore-config-on-start \
  --cloud18-domain          mycompany \
  --cloud18-sub-domain      ovh \
  --cloud18-sub-domain-zone fr-1 \
  --cloud18-gitlab-user     admin@mycompany.com \
  --cloud18-gitlab-password gitlab_password
```

When `monitoring-restore-config-on-start` is set, replication-manager:

1. Authenticates to `gitlab.signal18.io` and obtains a personal access token
2. Clones `gitlab.signal18.io/mycompany/mycompany-ovh-fr-1.git` into the working directory — replacing any existing local config
3. Clones the pull mirror `mycompany-ovh-fr-1-pull.git` into `<working-dir>/.pull`
4. Clears the flag so the restore does not repeat on the next restart
5. Reads `cloud18.toml` and reconstructs all cluster definitions

---

## 2.5.7 Configuration Reference

| Parameter | Default | Scope | Description |
|---|---|---|---|
| `cloud18` | `false` | server | Enable GitLab config sync and SSO integration |
| `cloud18-domain` | `""` | server | Organisation namespace (maps to GitLab group) |
| `cloud18-sub-domain` | `""` | server | Datacenter or environment label |
| `cloud18-sub-domain-zone` | `""` | server | Geo-zone identifier |
| `cloud18-gitlab-user` | `""` | server | GitLab username or email used to authenticate |
| `cloud18-gitlab-password` | `""` | server | GitLab password (stored AES-encrypted) |
| `cloud18-crm-api-url` | `https://api.crm.ovh-fr-2.signal18.cloud18.io` | server | CRM API base URL called by `POST /api/register` |
| `monitoring-restore-config-on-start` | `false` | server | Clone config from GitLab on startup and wipe local working directory |

---

## 2.5.8 GitLab Object Mapping

| replication-manager concept | GitLab object |
|---|---|
| Organisation (`domain`) | GitLab group `gitlab.signal18.io/<domain>/` |
| Instance slot (`subdomain-zone`) | GitLab project `<domain>-<subdomain>-<zone>` under the group |
| Peer distribution | GitLab project `<domain>-<subdomain>-<zone>-pull` under the group |
| Config history | Commits in the main project repository |
| Team access | GitLab group and project membership with role assignments |

---

## 2.5.9 Secret Storage

All secrets (passwords, encryption keys) are **AES-encrypted** at the replication-manager host before being written to GitLab. The encryption key is generated locally and never leaves the host.

```
replication-manager host
    └─ AES encryption key  (local only — never synced)
           │
           ▼
     encrypted value  →  GitLab repository  (ciphertext only)
```

See [Security — Configuration Guide](/security/configuration-guide) for key generation and rotation.
