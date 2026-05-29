---
title: Software Configurator
taxonomy:
    category: docs
---

## Chapter 10.3 — Software Configurator

### Our Goals

**1. Best-practice configuration from real production experience.**
The configurator aggregates configuration knowledge from all Signal18 clients and community users. Every tag represents battle-tested settings — not theoretical defaults, but values proven across thousands of production databases. When you select a tag, you get the collective wisdom of the Signal18 ecosystem.

**2. Nothing missed — MariaDB and MySQL fully covered.**
MariaDB and MySQL diverge in variable names, default values, and feature availability. The configurator tracks both vendors, uses `loose_` prefix for cross-vendor safety, and validates every variable against your actual database binary. If a compliance tag contains a variable your database doesn't recognize, you'll know before it breaks anything.

**3. Every change is visible — nothing happens unnoticed.**
The configurator continuously tracks the delta between what compliance tags define and what your database is actually running. Every difference is surfaced in the GUI with clear Accept/Preserve actions. No silent overwrites, no hidden changes. You see exactly what will change before any restart, and you decide what to apply.

**4. Your configuration is preserved above all else.**
Before any compliance change is applied to your database, your current configuration is protected. The delta file (`02_delta.cnf`) locks your running values in place. The preserved file (`01_preserved.cnf`) permanently overrides any tag. Your database will never start with a configuration you didn't approve. The configurator proposes — you decide.

---

### How It Works

The configurator uses a **tag-based system** where each tag produces a MySQL/MariaDB cnf fragment. The full config is assembled from the selected tags, deployed to the database, and continuously tracked for drift.

### Key Features

- **Tag-based config generation** — select tags (replication, innodb, security, pfs, etc.) to build the server config from best-practice templates
- **Config tracking** — continuous delta detection between compliance tags and running database, with per-variable Accept/Preserve actions
- **Cross-vendor support** — `loose_` prefix handling for MySQL-only and MariaDB-only variables
- **Unknown variable detection** — dbjobs validates the compliance config against the database binary and flags unrecognized variables before deployment
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
