---
title: Instance Registration & SSO
taxonomy:
    category: docs
---

## Instance Registration

Community and Enterprise plugins require your replication-manager instance to be **registered** with the Signal18 SSO at [gitlab.signal18.io](https://gitlab.signal18.io).

Registration is free. It ties your running replication-manager instance to a GitLab identity, unlocks the community plugin library, and opens access to the broader Cloud18 ecosystem.

---

## What Registration Provides

### Community Plugin Access

Once registered, replication-manager downloads the community plugin manifest on startup and keeps the plugin library up to date automatically. No manual binary management is required.

---

### Configuration Backup and Restore

Every cluster configuration managed by your replication-manager instance is versioned in a GitLab repository associated with your instance. This provides:

- **Full audit history** of every configuration change, with author, timestamp, and diff
- **One-command restore** — deploy a fresh replication-manager instance, point it at your GitLab namespace, and all cluster definitions are pulled back automatically
- **Disaster recovery** — if the replication-manager host is lost, the entire cluster topology, routing configuration, and provisioning settings can be recovered from GitLab without manual reconstruction

Configuration files stored in GitLab contain no plaintext secrets — all sensitive values (passwords, encryption keys) are stored in encrypted form. See *Secret Storage* below.

---

### Cluster Role Sharing with External Users

Registered instances can grant access to individual clusters to any other user registered on gitlab.signal18.io. Access is controlled through GitLab group membership and maps directly to replication-manager cluster grants:

| GitLab role | replication-manager grant |
|---|---|
| Guest | Read-only — view cluster state and topology |
| Reporter | Operator — trigger switchover, view logs, run checks |
| Developer | Admin — full cluster management, failover, provisioning |
| Maintainer | Owner — manage cluster settings, team, and registration |

This allows DBAs, support engineers, or partners to be given scoped access to specific clusters without sharing credentials or VPN access to the replication-manager host.

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
