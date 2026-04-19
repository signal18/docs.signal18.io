# docs.signal18.io

This is the documentation repository for **replication-manager**, an open source database cluster orchestrator for MySQL, MariaDB, and Percona. The docs are published at docs.signal18.io.

## Documentation Framework

This repository uses **Grav CMS**, a flat-file content management system:

- **Content Format**: Markdown files with YAML frontmatter
- **File Naming**:
  - `chapter.md` for chapter/section introductions
  - `docs.md` for actual content pages
- **Directory Structure**: Numbered directories (01., 02., etc.) determine page order in Grav
- **Images**: Stored in `/images/` directory, referenced as `/images/filename.png` in markdown
- **No Build Process**: This is a content-only repository deployed directly to Grav CMS

## YAML Frontmatter Format

Every markdown file must include YAML frontmatter:

```yaml
---
title: "Page Title"
taxonomy:
    category: docs
---
```

## Documentation Structure

The documentation follows a sequential chapter-based organization:

1. **01.introduction/** - Project overview and licensing
2. **02.installation/** - Getting started, setup instructions and dependencies
3. **03.usage/** - First use, console, API, and CLI documentation
4. **04.architecture/** - High Availability: switchover/failover workflows and topology patterns
5. **05.configuration/** - Configuration Guide: routing, backups, and general settings
6. **05.monitoring/** - Monitoring configuration and daemon monitoring
7. **06.maintenance/** - Maintenance jobs, scheduling, and operational tasks
8. **07.security/** - Security configuration guide
9. **08.alerting/** - Alerting configuration guide
10. **09.provisioning/** - Provisioning backends (OpenSVC, Kubernetes, SlapOS)
11. **10.plugins/** - Plugin system overview and registration
12. **11.contribute/** - Build instructions and testing guidelines for the main project
13. **12.howto/** - Best practices and troubleshooting
14. **13.change-logs/** - Version history and roadmap
15. **14.FAQ/** - Frequently asked questions

## Working with Documentation

### Adding New Pages

1. Create a numbered subdirectory (e.g., `05.new-section/`) within the appropriate chapter
2. Add either `chapter.md` (for section intro) or `docs.md` (for content)
3. Include proper YAML frontmatter with title and taxonomy
4. Number determines display order - renumber directories to reorder

### Adding Images

1. Place images in `/images/` directory
2. Reference as `/images/filename.png` in markdown
3. Use descriptive filenames (e.g., `architecture-failover-workflow.png`)

### Content Guidelines

- The project is **replication-manager** (not "Replication Manager" or other variations)
- Focus on practical examples and real-world usage patterns
- Include command-line examples where applicable
- Cross-reference related sections when appropriate

## Architecture Notes

**replication-manager** is a comprehensive database HA solution supporting:
- Multiple database backends: MySQL, MariaDB, Percona
- Traffic routing via HAProxy, ProxySQL, MaxScale, Consul
- Provisioning via OpenSVC, Kubernetes, SlapOS
- REST API, CLI, and web UI interfaces
- The project is hosted at github: https://github.com/signal18/replication-manager

When documenting features, consider the interaction between:
- Database topology (master-slave, multi-master, Galera, Group Replication)
- Routing layer (Layer 3/4/7)
- Provisioning backend
- Monitoring and alerting systems
