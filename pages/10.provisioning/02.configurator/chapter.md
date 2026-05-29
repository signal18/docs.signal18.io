---
title: Software Configurator
taxonomy:
    category: docs
---

## Chapter 10.3 — Software Configurator

The configurator generates, deploys, and tracks database configuration across your cluster. It uses a **tag-based system** where each tag produces a MySQL/MariaDB cnf fragment, and the full config is assembled from the selected tags.

### Key Features

- **Tag-based config generation** — select tags (replication, innodb, security, pfs, etc.) to build the server config from best-practice templates
- **Config tracking** — continuous delta detection between compliance tags and running database, with per-variable Accept/Preserve actions
- **Cross-vendor support** — `loose_` prefix handling for MySQL-only and MariaDB-only variables
- **Unknown variable detection** — dbjobs validates the compliance config against the database binary and flags unrecognized variables
- **Rolling upgrade** — version-aware config deployment with distribution package management
- **Direct file visualization** — view deployed config files per server from the Configurator tab, no SSH needed

### Sections

| Section | Description |
|---|---|
| [Overview](overview) | How the configurator works, config archive structure |
| [Tag Reference](tags) | Available tags and what each generates |
| [Config Tracking](config-tracking) | Delta detection, preserved/accepted/agreed flow, dbjobs dependency |
| [Configuration Guide](configuration-guide) | Settings reference for the configurator |
| [Distributions & Rolling Upgrade](distributions) | Package management and version upgrades |
