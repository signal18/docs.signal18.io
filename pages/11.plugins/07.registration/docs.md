---
title: Instance Registration & SSO
published: false
taxonomy:
    category: docs
---

## Instance Registration

Community and Enterprise plugins require your replication-manager instance to be **registered** with the Signal18 SSO at [gitlab.signal18.io](https://gitlab.signal18.io).

Registration is free. It takes less than two minutes and unlocks:

- **Community plugins** — the full library of workload, security, and score plugins, kept up to date automatically
- **Config backup & restore** — all cluster configurations versioned in GitLab, recoverable on any new replication-manager instance in one command
- **Cluster role sharing** — grant scoped access to any cluster to other registered SSO users without sharing credentials or VPN
- **Direct chat** — a built-in chat panel in the replication-manager GUI for real-time conversation with the Signal18 team and marketplace partners
- **Cloud18 marketplace** — consume clusters provided by other participants or publish your own; become an active Signal18 partner or customer

---

## What Registration Provides

### Community Plugin Access

Once registered, replication-manager downloads the community plugin manifest on startup and keeps the plugin library up to date automatically. No manual binary management is required.

---

### Configuration Backup and Restore

Every cluster configuration managed by your replication-manager instance is continuously pushed to a GitLab repository in your SSO namespace. This provides:

- **Full audit history** — every configuration change is a Git commit with author, timestamp, and diff
- **Config restore** — on a fresh host, replication-manager clones the entire working directory from GitLab and reconstructs all cluster definitions automatically
- **Disaster recovery** — if the replication-manager host is lost, the entire cluster topology, routing configuration, and provisioning settings can be recovered from GitLab without manual reconstruction

#### Starting fresh from GitLab

There is no separate restore command. To restore on a new host, start `replication-manager monitor` with `--monitoring-restore-config-on-start` alongside the `cloud18` flags that identify your registered namespace:

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

When `monitoring-restore-config-on-start` is set, replication-manager:

1. Authenticates to gitlab.signal18.io and obtains a personal access token
2. Clones the config repository `gitlab.signal18.io/<domain>/<subdomain>-<zone>.git` into the working directory — **replacing any existing local config**
3. Clones the read-only pull mirror `<subdomain>-<zone>-pull.git` into `<working-dir>/.pull`
4. Clears the flag so the wipe-and-restore does not repeat on the next restart
5. Reads `cloud18.toml` and reconstructs all cluster definitions

From that point on the instance polls GitLab at every `git-monitoring-ticker` interval, pulls changes, and reloads automatically.

Configuration files stored in GitLab contain no plaintext secrets — all sensitive values (passwords, encryption keys) are stored in encrypted form. See *Secret Storage* below.

---

### Cluster Role Sharing with External Users

Registered instances can share cluster access with other registered SSO users — Signal18 partners, support engineers, or trusted operators. Access is managed entirely through the **replication-manager API and GUI**, not through GitLab group membership. The SSO identity from gitlab.signal18.io is used only for authentication; all role and grant decisions are made and stored by replication-manager itself.

#### Roles

replication-manager defines the following built-in roles. Each role carries a default set of fine-grained grants:

| Role | Who it is for | Default grant scope |
|---|---|---|
| `sysops` | Local infrastructure operator (owner of the instance) | All grants — full access to cluster, DB, proxy, provisioning, global settings, and user management |
| `dbops` | Local database operator | DB operations and show grants — no cluster provisioning, no global settings, no user management |
| `extsysops` | External SysOps partner (Cloud18 marketplace) | Same as `sysops` minus sales, global settings, and `extrole` management |
| `extdbops` | External DBOps partner (Cloud18 marketplace) | DB, show, proxy, and grant operations — no `extrole` management |
| `sponsor` | Marketplace subscriber consuming the cluster | DB operations, show, proxy, grant, `extrole`, sales-unsubscribe, and app access |
| `visitor` | Read-only observer | Show grants only |

#### Grant categories

Grants are grouped by resource type. Each role receives a subset:

| Prefix | Examples | Description |
|---|---|---|
| `cluster-` | `cluster-failover`, `cluster-switchover`, `cluster-settings`, `cluster-rolling` | Cluster-level operations and configuration |
| `db-` | `db-start`, `db-stop`, `db-backup`, `db-restore`, `db-logs`, `db-replication` | Per-database server operations |
| `proxy-` | `proxy-start`, `proxy-stop`, `proxy-config-create` | Proxy management |
| `global-` | `global-settings`, `global-grant` | Instance-wide settings |
| `grant-` | `grant-show`, `grant-add`, `grant-drop`, `grant-modify` | User and ACL management |
| `show` | `db-show-variables`, `db-show-status`, `db-show-schema` | Read-only visibility |

#### External partner lifecycle

External users (partners consuming or operating a cluster via the Cloud18 marketplace) go through a managed lifecycle controlled from the replication-manager GUI:

```
Register  →  pending  →  quote  →  active  →  unsubscribed
              (waiting     (price     (full       (access
               approval)   proposed)  access)     revoked)
```

The cluster owner approves or rejects each step. An external partner in `pending` state has no operational grants until explicitly accepted. Cancelling at any stage revokes the partner's grants immediately.

#### ACL storage

User roles and grants are stored in the cluster configuration under `api-credentials-acl-allow-external`. Each entry follows the format:

```
username:grant1 grant2 ...:cluster-name:role1 role2 ...
```

Changes made through the GUI or API are persisted to the cluster TOML file (and therefore versioned in GitLab) immediately.

---

### Direct Chat with Signal18 and Partners

replication-manager integrates a **Chat** tab in the GUI that connects you to the Signal18 team and marketplace partners. The chat is powered by **Mattermost** and hosted at:

> [meet.signal18.io](https://meet.signal18.io)

You can connect in three ways — all using your gitlab.signal18.io SSO identity, no separate Mattermost account or password needed:

- **replication-manager GUI** — the Chat tab embeds Mattermost directly. Clicking it authenticates you automatically via your active GitLab SSO session.
- **Browser** — go to [meet.signal18.io](https://meet.signal18.io) and click **Sign in with GitLab**.
- **Mattermost desktop or mobile client** — download the [Mattermost desktop app](https://mattermost.com/download/) (Windows, macOS, Linux) or mobile app (iOS, Android), add a new server pointing to `https://meet.signal18.io`, and sign in with GitLab OAuth.

The chat provides three channels:

| Channel | Who you reach |
|---|---|
| **Support** | Signal18 engineering and support team — ask questions, report issues, get configuration advice |
| **Community** | Other registered replication-manager operators — share experience, compare configurations, collaborate on plugin development |
| **Partners** | Marketplace partners you are connected to — coordinate cluster handoffs, negotiate SLAs, discuss shared infrastructure |

Conversation history is retained across sessions and accessible from all three connection methods.

---

### Cloud18 Marketplace Participation

Registration makes your instance a participant in the **Cloud18 marketplace** — a community of Signal18 partners, customers, and operators sharing database infrastructure.

**As a consumer:** you can request access to clusters provided by other registered marketplace participants — useful for multi-tenant setups, managed service consumption, or cross-organisation database sharing.

**As a provider:** you can publish one or more of your clusters to the marketplace, making them available for other registered users to consume under the access model you define (read-only replicas, application endpoints, shared analytics clusters, etc.).

**As a partner or customer:** marketplace participation is the path to becoming an active Signal18 partner — your instance activity, plugin findings, and cluster topology contribute to the Signal18 knowledge base that drives product roadmap and support prioritisation.

---

## GitLab Object Mapping

Each registered instance is assigned a unique namespace in GitLab. The mapping is:

```
Instance  →  GitLab Group      gitlab.signal18.io/<instance-subdomain>/
Cluster   →  GitLab Project    gitlab.signal18.io/<instance-subdomain>/<cluster-name>/
Config    →  Git repository    versioned TOML files inside the project
Team      →  Group members     GitLab group membership with role assignments
```

### Subdomain and Namespace

When you register, you choose (or are assigned) a **subdomain** that uniquely identifies your instance:

```
<subdomain>.repman.signal18.io
```

This subdomain maps one-to-one to your GitLab group namespace:

```
gitlab.signal18.io/<subdomain>
```

The subdomain is **unique per registered instance** — no two instances share the same namespace. It is used as the routing key for plugin manifest delivery, configuration sync, and marketplace identity.

### Cluster Projects

Each cluster managed by your replication-manager instance appears as a separate GitLab project inside your group:

```
gitlab.signal18.io/<subdomain>/cluster-production/
gitlab.signal18.io/<subdomain>/cluster-staging/
gitlab.signal18.io/<subdomain>/cluster-analytics/
```

The project repository contains:

| Path | Contents |
|---|---|
| `config.toml` | Cluster TOML configuration (secrets encrypted) |
| `backup/` | Backup manifests and metadata |
| `topology/` | Point-in-time topology snapshots |
| `compliance/` | Applied compliance module tags and `.cnf` fragments |

---

## Secret Storage

Sensitive values — database passwords, replication credentials, backup encryption keys — are **never stored in plaintext** in GitLab.

All secrets are encrypted at the replication-manager instance level using AES before being written to the configuration repository. The encryption key is generated locally on the replication-manager host (`replication-manager keygen`) and **never leaves the host**.

```
replication-manager host
    └─ AES encryption key (local, never synced)
           │
           ▼
     encrypted secret → GitLab repository (ciphertext only)
```

This means:

- **GitLab stores only ciphertext.** Even with full access to the GitLab repository, secrets cannot be recovered without the encryption key on the replication-manager host.
- **Signal18 cannot read your secrets.** The encryption key is generated by you and stays on your infrastructure.
- **Rotating secrets** does not require changing the GitLab repository — only the local encryption key and the replication-manager configuration need updating.

To inspect exactly what is stored in GitLab for any cluster, browse the cluster project at `gitlab.signal18.io/<subdomain>/<cluster-name>/` — every file is readable and auditable directly in the GitLab UI.

---

## Team Management

You have **full control** over who can access your instance and its clusters through the GitLab group interface.

To manage your team:

1. Go to `gitlab.signal18.io/<subdomain>` — your instance group
2. Open **Manage → Members**
3. Add, remove, or change the role of any member

Changes take effect on the next replication-manager authentication check — no restart required. replication-manager polls group membership periodically and syncs grants automatically.

**To share a single cluster** without granting instance-wide access, manage membership at the project level instead of the group level:

```
gitlab.signal18.io/<subdomain>/<cluster-name>/ → Manage → Members
```

A user added at the project level can access only that cluster; they cannot see other clusters in the instance.

**Removing a member** from the group or project immediately revokes their replication-manager access on the next sync cycle.

---

## Registering Your Instance

```bash
# 1. Create an account at gitlab.signal18.io if you don't have one

# 2. Register the instance and obtain your SSO token
replication-manager instance-register \
    --gitlab-token <your-gitlab-personal-access-token> \
    --subdomain <your-chosen-subdomain>

# 3. Store the issued instance token in your configuration
# replication-manager writes it automatically to:
#   monitoring-instance-token = "eyJ..."
```

After registration, restart replication-manager. Community plugins are downloaded on the first startup and updated automatically on subsequent reloads.

---

## Configuration Reference

| Key | Default | Description |
|---|---|---|
| `monitoring-instance-token` | `""` | JWT issued at registration — identifies this instance to the Signal18 SSO |
| `plugin-registry-url` | `https://registry.signal18.io` | Community plugin manifest endpoint |
| `plugin-auto-update` | `true` | Automatically download updated community plugins on reload |
| `gitlab-url` | `https://gitlab.signal18.io` | GitLab instance used for config sync and team management |
