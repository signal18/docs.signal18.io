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
| **Enterprise** | Support contract customers | Signal18 Support or Partner plan | `enterprise-security`, `enterprise-replication`, `enterprise-workload`, `enterprise-compliance` |

**Community plugins** are the primary tier of external plugins. They are distributed to instances that have registered at gitlab.signal18.io and serve a dual purpose: they bring real monitoring value to users and they help signal18 understand how replication-manager is deployed in the field — workload patterns, security posture, topology shapes — so we can improve the product and focus roadmap investment where it matters most.

Registering your instance is free and takes less than two minutes. Benefits include community plugins, configuration backup and restore, cluster role sharing, direct chat with the Signal18 team and partners, and Cloud18 marketplace access. See [Registration & SSO](/getting-started/registration) in the Getting Started guide for the full details, GitLab object mapping, secret storage model, and team management.

**Enterprise plugins** are built into the binary and run on every instance. On **paid plans** (Support, Partner), the Signal18 back office pushes frequent updates — no new release required. On the **free plan** the embedded defaults are used, refreshed only with each new release.

Enterprise plugins cover four areas:

| Plugin | Scope | What it does |
|---|---|---|
| `enterprise-security` | CVE advisories (~500) | Surfaces all known MariaDB/MySQL CVEs from NIST NVD + GitHub security issues |
| `enterprise-replication` | Replication bugs (~20) | Tracks critical MDEV replication issues (MDEV-20821, MDEV-28310, MDEV-19577) |
| `enterprise-workload` | Crash/performance bugs (~260) | CRITICAL/HIGH severity crash, deadlock, optimizer regression, memory leak bugs |
| `enterprise-compliance` | Configuration compliance | Detects updated compliance modulesets and manages the approval workflow |

Advisory findings auto-resolve when the affected server or tool is upgraded past the fix version.

Enterprise plugins also provide **configurator helpers**:

- **Tag content viewer** — shows the config file content for each tag in the Database and Proxy Configurator, with documentation links to MariaDB KB, MySQL docs, and community blogs (755 variables, 154 blog references from Percona, jfg-mysql, MySQL On ARM, Petrunia, Marko Makela, and 15 other sources)
- **Compliance diff** — shows what changed between the previous and new compliance before or after accepting an update
