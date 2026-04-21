---
title: Documentation Index
taxonomy:
    category: docs
---

# replication-manager Documentation

**replication-manager** is an open-source, high-availability replication manager for MariaDB and MySQL. This documentation covers installation, configuration, monitoring, security, alerting, provisioning, and more.

---

## Table of Contents

1. **Introduction**
   - 1.1. [Overview](/introduction/overview)
   - 1.2. [About Leader Election](/introduction/about)
   - 1.3. [License](/introduction/license)

2. **Getting Started**
   - 2.1. [Installation](/installation/setup-instructions)
      - 2.1.1. [Overview](/installation/setup-instructions/overview)
      - 2.1.2. [Install from Embedded Binary](/installation/setup-instructions/embedded-binary)
      - 2.1.3. [Extra Dependencies](/installation/setup-instructions/setup-dependencies)
      - 2.1.4. [Install from Repository](/installation/setup-instructions/repository)
      - 2.1.5. [Provisioning](/installation/setup-instructions/setup-opensvc)
      - 2.1.6. [Install from Docker](/installation/setup-instructions/docker)
      - 2.1.7. [Filesystem Hierarchy](/installation/setup-instructions/filesystem-organisation)
      - 2.1.9. [Install from Source](/installation/setup-instructions/source)
      - 2.1.10. [Install Orchestrators](/installation/setup-instructions/orchestrators)
      - 2.1.11. [Install Dependencies](/installation/setup-instructions/dependencies)
      - 2.1.12. [What Was Installed](/installation/setup-instructions/what-was-installed)
      - 2.1.13. [First Config](/installation/setup-instructions/first-config)
      - 2.1.14. [Migrating from 2.x to 3.x](/installation/setup-instructions/migration)
   - 2.2. [Configuration](/installation/configuration)
   - 2.3. [First Config](/installation/first-config)
   - 2.4. [Command Line Flags](/installation/command-line-flags)
   - 2.5. [First Login](/installation/first-login)
   - 2.6. [Migrating from 2.x to 3.x](/installation/migration)
   - 2.7. [Registration & SSO](/installation/registration)
   - 2.8. [Migrating from 2.x to 3.x](/installation/migration)

3. **First Use**
   - 3.1. [Start & Stop Monitoring](/usage/quickstart)
   - 3.2. [Command Line Client](/usage/console)
   - 3.3. [Web Interface](/usage/http)
   - 3.4. **API**
      - 3.4.1. [API Client Usage](/usage/api/overview)
      - 3.4.2. [API Client Usage (v3)](/usage/api/latest)
      - 3.4.3. [API Client Usage (v2 and older)](/usage/api/previous-versions)
   - 3.5. [CMD Client Usage](/usage/cmd)
   - 3.6. [Provisioning Agent](/usage/pro)
   - 3.7. [Logs](/usage/logs)

4. **High Availability**
   - 4.1. [Overview](/architecture/overview)
   - 4.2. [Switchover Workflow](/architecture/switchover-workflow)
   - 4.3. [Failover WorkFlow](/architecture/failover-workflow)
   - 4.4. **Topologies**
      - 4.4.1. [Positional vs GTID](/architecture/topologies/positional-gtid)
      - 4.4.2. [Master Slave](/architecture/topologies/master-slave)
      - 4.4.3. [Master Master](/architecture/topologies/master-master)
      - 4.4.4. [Multi Tier Slaves](/architecture/topologies/multi-tier-slaves)
      - 4.4.5. [Multi Master Ring](/architecture/topologies/multi-master-ring)
      - 4.4.6. [Multi Master Galera](/architecture/topologies/multi-master-galera)
      - 4.4.7. [Multi Master Group Replication](/architecture/topologies/multi-master-group-replication)
      - 4.4.8. [Multi Domains](/architecture/topologies/multi-domains)
      - 4.4.9. [Binlog Server](/architecture/topologies/binlog-server)
      - 4.4.10. [Multi Source](/architecture/topologies/multi-source)
      - 4.4.11. [Sharding Cluster](/architecture/topologies/sharding)
      - 4.4.12. [Staging Cluster](/architecture/topologies/staging)
      - 4.4.13. [Active-Passive Topology](/architecture/topologies/active-passive)
   - 4.5. **Configuration Guide**
      - 4.5.1. [Databases](/architecture/configuration-guide/databases)
      - 4.5.2. [Failover](/architecture/configuration-guide/failover)
      - 4.5.3. [Switchover](/architecture/configuration-guide/switchover)
      - 4.5.4. [Replication](/architecture/configuration-guide/replication)
      - 4.5.5. [Routing](/architecture/configuration-guide/routing)
      - 4.5.6. [Checks & Enforce](/architecture/configuration-guide/checks-enforce)
   - 4.6. [Troubleshooting](/architecture/troubleshooting)

5. **Monitoring**
   - 5.1. [Overview](/monitoring/overview)
   - 5.2. **Monitoring Configuration Guide**
      - 5.2.1. [Daemon Monitoring](/monitoring/configuration-guide/server)
      - 5.2.2. [Metrics Monitoring](/monitoring/configuration-guide/metrics)
      - 5.2.3. [Processlist & Transaction Monitoring](/monitoring/configuration-guide/processlist-monitoring)
      - 5.2.4. [Performance Schema Monitoring](/monitoring/configuration-guide/performance-schema)
      - 5.2.5. [Multi node monitoring](/monitoring/configuration-guide/clustering)
   - 5.3. [Logs and Troubleshooting](/monitoring/logs-troubleshooting)

6. **Maintenance**
   - 6.1. [Overview](/maintenance/overview)
   - 6.2. **Configuration Guide**
      - 6.2.1. [Scheduler](/maintenance/configuration-guide/scheduler)
      - 6.2.2. [Backups](/maintenance/configuration-guide/backups)
      - 6.2.3. [Schema & Data Checks](/maintenance/configuration-guide/schema-data-checks)
      - 6.2.4. [Reseeding](/maintenance/configuration-guide/reseeding)

7. **Security**
   - 7.1. [Overview](/security/overview)
   - 7.2. [Configuration Guide](/security/configuration-guide)
   - 7.3. [SBOM and CRA Compliance](/security/sbom-cra)
   - 7.4. [HTTPS Bastion and Terminal](/security/https-bastion)

8. **Alerting**
   - 8.1. [Overview](/alerting/overview)
   - 8.2. [Configuration Guide](/alerting/configuration-guide)

9. **Provisioning**
   - 9.1. [Overview](/provisioning/overview)
   - 9.2. [Software Configurator](/provisioning/configurator)
   - 9.3. **Orchestrators**
      - 9.3.1. [OpenSVC](/provisioning/orchestrators/opensvc)
      - 9.3.2. [SlapOS](/provisioning/orchestrators/slapos)
      - 9.3.3. [Kubernetes](/provisioning/orchestrators/kubernetes)
      - 9.3.4. [Local](/provisioning/orchestrators/local)
      - 9.3.5. [Scripts](/provisioning/orchestrators/scripts)
      - 9.3.6. [OnPremise](/provisioning/orchestrators/onpremise)
   - 9.4. [Service Plan](/provisioning/serviceplan)

10. **Database Configurator**
    - 10.1. [Overview](/configurator/overview)
    - 10.2. [Tag Reference](/configurator/tags)
    - 10.3. [Config Tracking](/configurator/config-tracking)
    - 10.4. [Configuration Guide](/configurator/configuration-guide)

11. **Contribute**
    - 11.1. [Build](/contribute/build)
    - 11.2. [Testing](/contribute/testing)
    - 11.3. [Report Bugs](/contribute/bugs)
    - 11.4. [Profiling](/contribute/profiling)

12. **Plugins**
    - 12.1. [Plugin Architecture](/plugins/architecture)
    - 12.2. [Built-in Log Plugins](/plugins/log-plugins)
    - 12.3. [Workload Plugins](/plugins/workload-plugins)
    - 12.4. [Security Plugins](/plugins/security-plugins)
    - 12.5. [Score Plugins](/plugins/score-plugins)
    - 12.6. [Developing Plugins](/plugins/developer)
    - 12.7. [Instance Registration & SSO](/plugins/registration)

13. **HOWTO**
    - 13.1. [Replication Best Practices](/howto/replication-best-practice)
    - 13.2. [Enforce Best Practices](/howto/enforce-best-practice)
    - 13.3. [Troubleshoot Crashes](/howto/toubleshoot-crashes)

14. **Change Logs**
    - 14.1. [1.0 Features](/change-logs/Features10)
    - 14.2. [1.1 Features](/change-logs/Features11)
    - 14.3. [2.0 Features](/change-logs/features20)
    - 14.4. [2.1 Features](/change-logs/features21)
    - 14.5. [2.2 Features](/change-logs/features22)
    - 14.6. [2.3 Features](/change-logs/feature23)
    - 14.7. [3.0 Features](/change-logs/features30)
    - 14.8. [3.1 Features](/change-logs/features31)
    - 14.9. [Roadmap](/change-logs/Roadmap)

15. **FAQ**
    - [Frequently Asked Questions](/FAQ)

---

## Download PDF

A PDF version of this documentation is generated automatically on each release.

[Download latest PDF](https://github.com/signal18/replication-manager/releases/latest/download/replication-manager-docs.pdf)
