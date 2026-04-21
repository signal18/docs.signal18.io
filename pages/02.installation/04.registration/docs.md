---
title: Registration & SSO
taxonomy:
    category: docs
---

## 1. Instance Registration & SSO

Community and Enterprise plugins require your replication-manager instance to be **registered** with the Signal18 SSO at [gitlab.signal18.io](https://gitlab.signal18.io).

Registration is free. It takes less than two minutes and unlocks:

- **Community plugins** — the full library of workload, security, and score plugins, kept up to date automatically
- **Config backup & restore** — all cluster configurations versioned in GitLab, recoverable on any new replication-manager instance in one command
- **Cluster role sharing** — grant scoped access to any cluster to other registered SSO users without sharing credentials or VPN
- **Direct chat** — a built-in chat panel in the replication-manager GUI for real-time conversation with the Signal18 team and marketplace partners
- **Cloud18 marketplace** — consume clusters provided by other participants or publish your own; become an active Signal18 partner or customer

---

## 2. Registering Your Instance

```bash
# 1. Create an account at gitlab.signal18.io if you don't have one

# 2. Register the instance and obtain your SSO token
replication-manager instance-register \
    --gitlab-token <your-gitlab-personal-access-token> \
    --subdomain <your-chosen-subdomain>

# 3. replication-manager writes the issued token automatically to:
#   monitoring-instance-token = "eyJ..."
```

After registration, restart replication-manager. Community plugins are downloaded on the first startup and updated automatically on subsequent reloads.

---

## 3. Community Plugin Access

Once registered, replication-manager downloads the community plugin manifest on startup and keeps the plugin library up to date automatically. No manual binary management is required.

See [Plugins](../../../plugins) for the full plugin reference — architecture, workload plugins, security plugins, score plugins, and how to write your own.

---

## 4. Configuration Backup and Restore

Every cluster configuration is continuously pushed to a GitLab repository in your SSO namespace:

- **Full audit history** — every configuration change is a Git commit with author, timestamp, and diff
- **Config restore** — on a fresh host, replication-manager clones the entire working directory from GitLab and reconstructs all cluster definitions automatically
- **Disaster recovery** — topology, routing configuration, and provisioning settings all recovered from GitLab

#### 4.0.1 Starting fresh from GitLab

```bash
replication-manager monitor \
  --cloud18 \
  --monitoring-restore-config-on-start \
  --cloud18-domain          <your-organisation> \
  --cloud18-sub-domain      <your-instance-subdomain> \
  --cloud18-sub-domain-zone <your-geo-zone> \
  --cloud18-gitlab-user     <your-gitlab-user> \
  --cloud18-gitlab-password <your-gitlab-password>
```

When `--monitoring-restore-config-on-start` is set, replication-manager:

1. Authenticates to gitlab.signal18.io and obtains a personal access token
2. Clones `gitlab.signal18.io/<domain>/<subdomain>-<zone>.git` into the working directory — replacing any existing local config
3. Clones the read-only pull mirror `<subdomain>-<zone>-pull.git` into `<working-dir>/.pull`
4. Clears the flag so the restore does not repeat on the next restart
5. Reads `cloud18.toml` and reconstructs all cluster definitions

Configuration files stored in GitLab contain no plaintext secrets — all sensitive values are stored in AES-encrypted form. The encryption key is generated locally and **never leaves the host**.

---

## 5. Cluster Role Sharing with External Users

Registered instances can share cluster access with other registered SSO users — Signal18 partners, support engineers, or trusted operators. Access is managed entirely through the **replication-manager API and GUI**.

### 5.1 Roles

| Role | Who it is for | Default grant scope |
|---|---|---|
| `sysops` | Local infrastructure operator (owner) | All grants — full access |
| `dbops` | Local database operator | DB operations — no provisioning, no global settings |
| `extsysops` | External SysOps partner (Cloud18) | Same as `sysops` minus sales and global settings |
| `extdbops` | External DBOps partner (Cloud18) | DB, show, proxy, grant operations |
| `sponsor` | Marketplace subscriber | DB operations, show, proxy, grant, app access |
| `visitor` | Read-only observer | Show grants only |

### 5.2 External Partner Lifecycle

```
Register  →  pending  →  quote  →  active  →  unsubscribed
              (waiting     (price     (full       (access
               approval)   proposed)  access)     revoked)
```

---

## 6. Direct Chat with Signal18 and Partners

replication-manager integrates a **Chat** tab in the GUI powered by **Mattermost** at [meet.signal18.io](https://meet.signal18.io). All three connection methods use your gitlab.signal18.io SSO identity — no separate Mattermost account needed:

- **replication-manager GUI** — the Chat tab authenticates automatically via your active GitLab SSO session
- **Browser** — go to [meet.signal18.io](https://meet.signal18.io) and click **Sign in with GitLab**
- **Desktop / mobile client** — add `https://meet.signal18.io` as a server in the [Mattermost app](https://mattermost.com/download/) and sign in with GitLab OAuth

| Channel | Who you reach |
|---|---|
| **Support** | Signal18 engineering and support team |
| **Community** | Other registered replication-manager operators |
| **Partners** | Marketplace partners you are connected to |

---

## 7. GitLab Object Mapping

Each registered instance is assigned a unique namespace:

```
Instance  →  GitLab Group      gitlab.signal18.io/<subdomain>/
Cluster   →  GitLab Project    gitlab.signal18.io/<subdomain>/<cluster-name>/
Config    →  Git repository    versioned TOML files inside the project
Team      →  Group members     GitLab group membership with role assignments
```

Your subdomain maps one-to-one to your GitLab group and is the routing key for plugin delivery, configuration sync, and marketplace identity.

---

## 8. Secret Storage

All secrets (passwords, encryption keys) are **AES-encrypted** at the replication-manager host before being written to GitLab. The encryption key is generated locally (`replication-manager keygen`) and never leaves the host.

```
replication-manager host
    └─ AES encryption key (local, never synced)
           │
           ▼
     encrypted secret → GitLab repository (ciphertext only)
```

---

## 9. Team Management

To manage who can access your instance and its clusters:

1. Go to `gitlab.signal18.io/<subdomain>` — your instance group
2. Open **Manage → Members**
3. Add, remove, or change the role of any member

Changes take effect on the next replication-manager authentication sync — no restart required.

To share a **single cluster** without instance-wide access, manage membership at the project level:

```
gitlab.signal18.io/<subdomain>/<cluster-name>/ → Manage → Members
```

---

## 10. Configuration Reference

| Key | Default | Description |
|---|---|---|
| `monitoring-instance-token` | `""` | JWT issued at registration — identifies this instance to the Signal18 SSO |
| `plugin-registry-url` | `https://registry.signal18.io` | Community plugin manifest endpoint |
| `plugin-auto-update` | `true` | Automatically download updated community plugins on reload |
| `gitlab-url` | `https://gitlab.signal18.io` | GitLab instance used for config sync and team management |
