---
title: Overview
taxonomy:
    category: docs
---

## 5.1.1 Monitoring Overview

replication-manager runs a continuous monitoring loop that tracks every server in every managed cluster. On each tick (default every 2 seconds, controlled by `monitoring-ticker`) it connects to each database and proxy server, reads state, and updates an in-memory topology model that drives the GUI, API, alerting, and failover decisions.

---

## 5.1.2 What the Monitor Tracks

### 5.1.2.1 Replication topology

- Server role (primary / replica / candidate / down)
- GTID positions and replication lag
- Semi-sync acknowledgement state
- Slave SQL/IO thread status
- Relay log position and relay log size
- Replication domain IDs (multi-source)

### 5.1.2.2 Server health

- Global status variables (connections, InnoDB metrics, query cache, etc.)
- Global variables (for config drift detection)
- InnoDB engine status
- Error log events

### 5.1.2.3 Schema and data consistency

- **Schema monitoring** — detects structural drift between primary and replicas: missing/added tables, column type changes, collation changes, index differences. A CRC64 fingerprint is computed per table and compared across all nodes. Runs on a daily scheduler by default. See [Schema and Data Checks](/maintenance/configuration-guide/schema-data-checks#6-2-3-1-schema-monitoring)
- **Data checksumming** — detects row-level divergence between primary and replicas using chunk-based CRC32 checksums with GTID-aware replica convergence. Divergent replicas are flagged and can be excluded from failover election. See [Data Checksumming](/maintenance/configuration-guide/schema-data-checks#6-2-3-2-data-checksumming)
- **Checksum repair** — divergent chunks can be repaired from the GUI or API, re-syncing affected rows via the primary using row-based replication. See [Repairing Divergent Tables](/maintenance/configuration-guide/schema-data-checks#6-2-3-3-repairing-divergent-tables)
- **Schema graph** — table relationships visualized via foreign keys, name matches, and workload query co-occurrence. See [Shards and Schema Graph](/maintenance/configuration-guide/schema-data-checks#6-2-3-4-gui-shards-and-schema-graph)

### 5.1.2.4 Security and compliance

- **Security plugins** — continuous auditing of database configuration, user accounts, and activity logs for CIS Benchmark controls (SEC01xx). Findings feed the remediation engine with auto-fix capabilities. See [Database Compliance](/security/database-compliance)
- **User and privilege monitoring** — detects no-password accounts, weak authentication plugins, wildcard-host grants, anonymous users, and privilege escalation events
- **Enterprise advisory plugins** — match running database/tool versions against CVE and bug advisory databases (security, replication, workload). See [Plugins Overview](/installation/registration/plugins)
- **Tools version tracking** — monitors versions of client tools (mysqldump, mydumper, myloader, mariabackup, xtrabackup, restic, mysql client, sysbench) and detects version mismatches between tools and database servers

### 5.1.2.5 Workload

- Active processlist (up to 50 longest-running queries — `monitoring-processlist`)
- Performance Schema digest statistics (`monitoring-performance-schema`)
- Query routing rules consolidated from all proxies (`monitoring-query-rules`)

### 5.1.2.6 Proxies

- ProxySQL, HAProxy, and MaxScale connection counts and routing state
- Backend server weights and online/offline transitions

### 5.1.2.7 Metrics

- All collected counters are pushed to the embedded Graphite database and exposed as Prometheus metrics
- See [Metrics](../01.configuration-guide/01.metrics) for Graphite and Prometheus configuration

---

## 5.1.3 Monitoring Loop

```
Every monitoring-ticker seconds:
  for each cluster:
    for each server (parallel goroutines):
      connect → read status → update topology model
    evaluate topology → compute failover eligibility
    fire alerts if state changed (see Alerting chapter)
    push metrics to Graphite
    write API state
```

When `monitoring-capture` is enabled, replication-manager automatically saves a diagnostic snapshot (SHOW SLAVE STATUS, SHOW PROCESSLIST, SHOW GLOBAL STATUS, SHOW ENGINE INNODB STATUS) for a configurable number of ticks whenever a monitored error code appears. See [Logs and Troubleshooting](../02.logs-troubleshooting) for details.

---

## 5.1.4 Accessing Monitoring Data

### 5.1.4.1 GUI

The web dashboard shows the live topology, server state, processlist, and alerts. The React dashboard is served at the replication-manager API address (default port 10001).

### 5.1.4.2 REST API

```
GET /api/clusters/{clusterName}/topology/servers
GET /api/clusters/{clusterName}/topology/master
GET /api/clusters/{clusterName}/topology/slaves
GET /api/clusters/{clusterName}/topology/alerts
GET /api/clusters/{clusterName}/topology/logs
```

### 5.1.4.3 Prometheus

```
GET /api/prometheus
```

Returns all collected metrics in Prometheus text exposition format. Point your Prometheus scraper at this endpoint.

### 5.1.4.4 CLI

```bash
replication-manager-cli status --cluster=my-cluster
replication-manager-cli status --cluster=my-cluster --with-errors
```

---

## 5.1.5 Configuration

| Area | Where documented |
|---|---|
| Monitoring intervals, directories, capture | [Daemon Monitoring](../01.configuration-guide/00.server) |
| Graphite and Prometheus metrics | [Metrics](../01.configuration-guide/01.metrics) |
| Processlist capture | [Processlist Monitoring](../01.configuration-guide/02.processlist-monitoring) |
| Performance Schema | [Performance Schema](../01.configuration-guide/03.performance-schema) |
| Multi-cluster topology | [Clustering](../01.configuration-guide/04.clustering) |
| Log files and debug tools | [Logs and Troubleshooting](../02.logs-troubleshooting) |
| Schema monitoring and data checksumming | [Schema and Data Checks](../../06.maintenance/01.configuration-guide/03.schema-data-checks) |
| Alert channels and triggers | [Alerting](../../08.alerting) |
