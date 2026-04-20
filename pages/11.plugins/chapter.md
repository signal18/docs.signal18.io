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
| **Enterprise** | Support contract customers | Signal18 Support Contract | `plugin-critical-alerts` |

**Community plugins** are the primary tier of external plugins. They are distributed to instances that have registered at gitlab.signal18.io and serve a dual purpose: they bring real monitoring value to users and they help signal18 understand how replication-manager is deployed in the field — workload patterns, security posture, topology shapes — so we can improve the product and focus roadmap investment where it matters most.

Registering your instance is free and takes less than two minutes. Benefits include community plugins, configuration backup and restore, cluster role sharing, direct chat with the Signal18 team and partners, and Cloud18 marketplace access. See [Registration & SSO](/getting-started/registration) in the Getting Started guide for the full details, GitLab object mapping, secret storage model, and team management.

**Enterprise plugins** are reserved for customers with an active Signal18 Support Contract. They provide advanced detection capabilities built from Signal18's field experience supporting production MariaDB and MySQL deployments.
