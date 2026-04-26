---
title: Plugins
taxonomy:
    category: docs
---
### Chapter 10

# Log Plugins

> **Available since:** replication-manager **v3.1.24**

replication-manager ships three tiers of plugins:

| Tier | Who can use it | Requires | Examples |
|---|---|---|---|
| **Static** | Everyone | Nothing — bundled in the binary | `errorlog`, `slowlog`, `auditlog` |
| **Community** | Registered instances | Free account at gitlab.signal18.io | All workload, security and score plugins |
| **Enterprise** | Support contract customers | Signal18 Support or Partner plan | `enterprise-security`, `enterprise-replication`, `enterprise-workload` |

**Community plugins** are the primary tier of external plugins. They are distributed to instances that have registered at gitlab.signal18.io and serve a dual purpose: they bring real monitoring value to users and they help signal18 understand how replication-manager is deployed in the field — workload patterns, security posture, topology shapes — so we can improve the product and focus roadmap investment where it matters most.

Registering your instance is free and takes less than two minutes. Benefits include community plugins, configuration backup and restore, cluster role sharing, direct chat with the Signal18 team and partners, and Cloud18 marketplace access. See [Registration & SSO](/getting-started/registration) in the Getting Started guide for the full details, GitLab object mapping, secret storage model, and team management.

**Enterprise plugins** are built into the binary and run on every instance. They match your server and tool versions against a curated advisory database of CVEs, replication bugs, and crash/performance issues sourced from the NIST NVD, MariaDB MDEV tracker, and Signal18 GitHub.

On **paid plans** (Support, Partner) the advisory database is refreshed daily by the Signal18 back office and pushed to your instance via the git pull repository. On the **free plan** the embedded default shipped with the binary is used — a persistent security alert (`ENTERR001`, `RPLERR001`, `WRKERR001`) warns that coverage is frozen at the build version.

| Plugin | Scope | Advisory count |
|---|---|---|
| `enterprise-security` | All MariaDB/MySQL CVEs + GitHub security issues | ~500 |
| `enterprise-replication` | Replication bugs (MDEV-20821, MDEV-28310, MDEV-19577) + replication CVEs | ~20 |
| `enterprise-workload` | CRITICAL/HIGH crash, deadlock, memory leak bugs (dedup vs above) | ~260 |

Findings auto-resolve when the affected server or tool is upgraded past the fix version.
