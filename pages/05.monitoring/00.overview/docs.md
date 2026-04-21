---
title: Overview
taxonomy:
    category: docs
---

## 1. Monitoring Overview

replication-manager runs a continuous monitoring loop that tracks every server in every managed cluster. On each tick (default every 2 seconds, controlled by `monitoring-ticker`) it connects to each database and proxy server, reads state, and updates an in-memory topology model that drives the GUI, API, alerting, and failover decisions.

---

## 2. What the Monitor Tracks

### 2.1 Replication topology

- Server role (primary / replica / candidate / down)
- GTID positions and replication lag
- Semi-sync acknowledgement state
- Slave SQL/IO thread status
- Relay log position and relay log size
- Replication domain IDs (multi-source)

### 2.2 Server health

- Global status variables (connections, InnoDB metrics, query cache, etc.)
- Global variables (for config drift detection)
- InnoDB engine status
- Error log events
- Schema changes (DDL detection — requires `monitoring-schema-change`)

### 2.3 Workload

- Active processlist (up to 50 longest-running queries — `monitoring-processlist`)
- Performance Schema digest statistics (`monitoring-performance-schema`)
- Query routing rules consolidated from all proxies (`monitoring-query-rules`)

### 2.4 Proxies

- ProxySQL, HAProxy, and MaxScale connection counts and routing state
- Backend server weights and online/offline transitions

### 2.5 Metrics

- All collected counters are pushed to the embedded Graphite database and exposed as Prometheus metrics
- See [Metrics](../01.configuration-guide/01.metrics) for Graphite and Prometheus configuration

---

## 3. Monitoring Loop

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

## 4. Accessing Monitoring Data

### 4.1 GUI

The web dashboard shows the live topology, server state, processlist, and alerts. The React dashboard is served at the replication-manager API address (default port 10001).

### 4.2 REST API

```
GET /api/clusters/{clusterName}/topology/servers
GET /api/clusters/{clusterName}/topology/master
GET /api/clusters/{clusterName}/topology/slaves
GET /api/clusters/{clusterName}/topology/alerts
GET /api/clusters/{clusterName}/topology/logs
```

### 4.3 Prometheus

```
GET /api/prometheus
```

Returns all collected metrics in Prometheus text exposition format. Point your Prometheus scraper at this endpoint.

### 4.4 CLI

```bash
replication-manager-cli status --cluster=my-cluster
replication-manager-cli status --cluster=my-cluster --with-errors
```

---

## 5. Configuration

| Area | Where documented |
|---|---|
| Monitoring intervals, directories, capture | [Daemon Monitoring](../01.configuration-guide/00.server) |
| Graphite and Prometheus metrics | [Metrics](../01.configuration-guide/01.metrics) |
| Processlist capture | [Processlist Monitoring](../01.configuration-guide/02.processlist-monitoring) |
| Performance Schema | [Performance Schema](../01.configuration-guide/03.performance-schema) |
| Multi-cluster topology | [Clustering](../01.configuration-guide/04.clustering) |
| Log files and debug tools | [Logs and Troubleshooting](../02.logs-troubleshooting) |
| Alert channels and triggers | [Alerting](../../08.alerting) |
