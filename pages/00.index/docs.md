---
title: Documentation Index
taxonomy:
    category: docs
---

# replication-manager

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
      - 2.1.8. [Install from Source](/installation/setup-instructions/source)
      - 2.1.9. [Install Orchestrators](/installation/setup-instructions/orchestrators)
      - 2.1.10. [Install Dependencies](/installation/setup-instructions/dependencies)
      - 2.1.11. [What Was Installed](/installation/setup-instructions/what-was-installed)
   - 2.2. [First Config](/installation/first-config)
   - 2.3. [Command Line Flags](/installation/command-line-flags)
   - 2.4. [First Login](/installation/first-login)
   - 2.5. [Migrating from 2.x to 3.x](/installation/migration)
   - 2.6. [Registration & SSO](/installation/registration)

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
      - 4.5.5. **Routing**
         - [HAProxy](/architecture/configuration-guide/routing/haproxy)
         - [ProxySQL](/architecture/configuration-guide/routing/proxysql)
         - [MaxScale](/architecture/configuration-guide/routing/maxscale)
         - [Sharding](/architecture/configuration-guide/routing/sharding)
         - [Consul](/architecture/configuration-guide/routing/consul)
         - [External](/architecture/configuration-guide/routing/external)
         - [External Checks](/architecture/configuration-guide/routing/external-checks)
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
   - 9.2. **Orchestrators**
      - 9.2.1. **OpenSVC**
         - [Collector API](/provisioning/orchestrators/opensvc/opensvc-collector-api)
         - [Cluster API](/provisioning/orchestrators/opensvc/opensvc-cluster-api)
         - [Advanced Config](/provisioning/orchestrators/opensvc/opensvc-advanced-config)
      - 9.2.2. [SlapOS](/provisioning/orchestrators/slapos)
      - 9.2.3. [Kubernetes](/provisioning/orchestrators/kubernetes)
      - 9.2.4. [Local](/provisioning/orchestrators/local)
      - 9.2.5. [Scripts](/provisioning/orchestrators/scripts)
      - 9.2.6. [OnPremise](/provisioning/orchestrators/onpremise)
   - 9.3. **Software Configurator**
      - 9.3.1. [Overview](/provisioning/configurator/overview)
      - 9.3.2. [Tag Reference](/provisioning/configurator/tags)
      - 9.3.3. [Config Tracking](/provisioning/configurator/config-tracking)
      - 9.3.4. [Configuration Guide](/provisioning/configurator/configuration-guide)
   - 9.4. [Service Plan](/provisioning/serviceplan)

10. **Contribute**
    - 10.1. [Build](/contribute/build)
    - 10.2. [Testing](/contribute/testing)
    - 10.3. [Report Bugs](/contribute/bugs)
    - 10.4. [Profiling](/contribute/profiling)

11. **Plugins**
    - 11.1. [Plugin Architecture](/plugins/architecture)
    - 11.2. [Built-in Log Plugins](/plugins/log-plugins)
    - 11.3. [Workload Plugins](/plugins/workload-plugins)
    - 11.4. [Security Plugins](/plugins/security-plugins)
    - 11.5. [Score Plugins](/plugins/score-plugins)
    - 11.6. [Developing Plugins](/plugins/developer)
    - 11.7. [Instance Registration & SSO](/plugins/registration)

12. **HOWTO**
    - 12.1. [Replication Best Practices](/howto/replication-best-practice)
    - 12.2. [Enforce Best Practices](/howto/enforce-best-practice)
    - 12.3. [Troubleshoot Crashes](/howto/toubleshoot-crashes)

13. **Change Logs**
    - 13.1. [1.0 Features](/change-logs/Features10)
    - 13.2. [1.1 Features](/change-logs/Features11)
    - 13.3. [2.0 Features](/change-logs/features20)
    - 13.4. [2.1 Features](/change-logs/features21)
    - 13.5. [2.2 Features](/change-logs/features22)
    - 13.6. [2.3 Features](/change-logs/feature23)
    - 13.7. [3.0 Features](/change-logs/features30)
    - 13.8. [3.1 Features](/change-logs/features31)
    - 13.9. [Roadmap](/change-logs/Roadmap)

14. **FAQ**
    - [Frequently Asked Questions](/FAQ)

---

## Download PDF

A PDF version of this documentation is generated automatically on each release.

[Download latest PDF](https://github.com/signal18/replication-manager/releases/latest/download/replication-manager-docs.pdf)
