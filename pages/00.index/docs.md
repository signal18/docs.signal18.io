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
      - 2.1.3. [Install from Repository](/installation/setup-instructions/repository)
      - 2.1.4. [Install from Docker](/installation/setup-instructions/docker)
      - 2.1.5. [Install from Source](/installation/setup-instructions/source)
      - 2.1.6. [Install Orchestrators](/installation/setup-instructions/orchestrators)
      - 2.1.7. [Install Dependencies](/installation/setup-instructions/dependencies)
      - 2.1.8. [What Was Installed](/installation/setup-instructions/what-was-installed)
   - 2.2. [First Config](/installation/first-config)
   - 2.3. [First Login](/installation/first-login)
   - 2.4. [Migrating from 2.x to 3.x](/installation/migration)
   - 2.5. [Registration & SSO](/installation/registration)
   - 2.6. [Network Ports](/installation/network-ports)
   - 2.7. [Database Grants](/installation/database-grants)

3. **First Use**
   - 3.1. [Start & Stop Monitoring](/usage/quickstart)
   - 3.2. [Command Line Client](/usage/console)
   - 3.3. [Web Interface](/usage/http)
   - 3.4. **API**
      - 3.4.1. [API Client Usage](/usage/api/overview)
      - 3.4.2. [API Client Usage (v3)](/usage/api/latest)
      - 3.4.3. [API Client Usage (v2 and older)](/usage/api/previous-versions)
   - 3.5. [Provisioning Agent](/usage/pro)
   - 3.6. [Logs](/usage/logs)

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

6. **Monitoring**
   - 6.1. [Overview](/monitoring/overview)
   - 6.2. **Monitoring Configuration Guide**
      - 6.2.1. [Daemon Monitoring](/monitoring/configuration-guide/server)
      - 6.2.2. [Metrics Monitoring](/monitoring/configuration-guide/metrics)
      - 6.2.3. [Processlist & Transaction Monitoring](/monitoring/configuration-guide/processlist-monitoring)
      - 6.2.4. [Performance Schema Monitoring](/monitoring/configuration-guide/performance-schema)
      - 6.2.5. [Multi node monitoring](/monitoring/configuration-guide/clustering)
   - 6.3. [Logs and Troubleshooting](/monitoring/logs-troubleshooting)
   - 6.4. [Cluster Slideshow](/monitoring/slideshow)

7. **Maintenance**
   - 7.1. [Overview](/maintenance/overview)
   - 7.2. **Configuration Guide**
      - 7.2.1. [Scheduler](/maintenance/configuration-guide/scheduler)
      - 7.2.2. [Backups](/maintenance/configuration-guide/backups)
      - 7.2.3. [Schema & Data Checks](/maintenance/configuration-guide/schema-data-checks)
      - 7.2.4. [Reseeding](/maintenance/configuration-guide/reseeding)

8. **Security**
   - 8.1. [Overview](/security/overview)
   - 8.2. [Configuration Guide](/security/configuration-guide)
   - 8.3. [SBOM and CRA Compliance](/security/sbom-cra)
   - 8.4. [HTTPS Bastion and Terminal](/security/https-bastion)

9. **Alerting**
   - 9.1. [Overview](/alerting/overview)
   - 9.2. [Configuration Guide](/alerting/configuration-guide)

10. **Provisioning**
    - 10.1. [Overview](/provisioning/overview)
    - 10.2. **Orchestrators**
       - 10.2.1. **OpenSVC**
          - [Collector API](/provisioning/orchestrators/opensvc/opensvc-collector-api)
          - [Cluster API](/provisioning/orchestrators/opensvc/opensvc-cluster-api)
          - [Advanced Config](/provisioning/orchestrators/opensvc/opensvc-advanced-config)
       - 10.2.2. [SlapOS](/provisioning/orchestrators/slapos)
       - 10.2.3. [Kubernetes](/provisioning/orchestrators/kubernetes)
       - 10.2.4. [Local](/provisioning/orchestrators/local)
       - 10.2.5. [Scripts](/provisioning/orchestrators/scripts)
       - 10.2.6. [OnPremise](/provisioning/orchestrators/onpremise)
    - 10.3. **Software Configurator**
       - 10.3.1. [Overview](/provisioning/configurator/overview)
       - 10.3.2. [Tag Reference](/provisioning/configurator/tags)
       - 10.3.3. [Config Tracking](/provisioning/configurator/config-tracking)
       - 10.3.4. [Configuration Guide](/provisioning/configurator/configuration-guide)
       - 10.3.5. [Distributions & Rolling Upgrade](/provisioning/configurator/distributions)
    - 10.4. [Service Plan](/provisioning/serviceplan)

11. **Plugins**
    - 11.1. [Plugin Architecture](/plugins/architecture)
    - 11.2. [Built-in Log Plugins](/plugins/log-plugins)
    - 11.3. [Workload Plugins](/plugins/workload-plugins)
    - 11.4. [Security Plugins](/plugins/security-plugins)
    - 11.5. [Score Plugins](/plugins/score-plugins)
    - 11.6. [Developing Plugins](/plugins/developer)

12. **Contribute**
    - 12.1. [Build](/contribute/build)
    - 12.2. [Testing](/contribute/testing)
    - 12.3. [Report Bugs](/contribute/bugs)
    - 12.4. [Profiling](/contribute/profiling)

13. **HOWTO**
    - 13.1. [Replication Best Practices](/howto/replication-best-practice)
    - 13.2. [Enforce Best Practices](/howto/enforce-best-practice)
    - 13.3. [Troubleshoot Crashes](/howto/toubleshoot-crashes)
    - 13.4. [Detect Runtime Variable Changes](/howto/detect-variable-changes)

14. **Change Logs**
    - 14.1. [1.0 Features](/change-logs/features10)
    - 14.2. [1.1 Features](/change-logs/features11)
    - 14.3. [2.0 Features](/change-logs/features20)
    - 14.4. [2.1 Features](/change-logs/features21)
    - 14.5. [2.2 Features](/change-logs/features22)
    - 14.6. [2.3 Features](/change-logs/feature23)
    - 14.7. [3.0 Features](/change-logs/features30)
    - 14.8. [3.1 Features](/change-logs/features31)
    - 14.9. [Roadmap](/change-logs/roadmap)

15. **FAQ**
    - 15.1. [Installation & Setup](/faq/installation-setup)
    - 15.2. [Replication & Synchronization](/faq/replication-synchronization)
    - 15.3. [Failover & Switchover](/faq/failover-switchover)
    - 15.4. [Topology & Deployment](/faq/topology-deployment)
    - 15.5. [Security & Configuration](/faq/security-configuration)
    - 15.6. [Proxies & Routing](/faq/proxies-routing)
    - 15.7. [Recovery & Data Loss](/faq/recovery-data-loss)
    - 15.8. [Monitoring & Troubleshooting](/faq/monitoring-troubleshooting)
    - 15.9. [Operational Best Practices](/faq/operational-best-practices)

---

## Download PDF

A PDF version of this documentation is generated automatically on each release.

[Download latest PDF](https://github.com/signal18/docs.signal18.io/raw/master/replication-manager-docs.pdf)
