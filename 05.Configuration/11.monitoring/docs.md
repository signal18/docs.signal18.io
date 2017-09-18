---
title: Monitoring Configuration
---

**replication-manager** embed a graphite database to store metrics about databases and proxies status.

##### `graphite-embedded` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Enable Graphite Server. |
| Type          | Boolean |
| Default Value | false |

##### `graphite-metrics` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Collect and send metrics to a Graphite Server. |
| Type          | Boolean |
| Default Value | false |

##### `graphite-carbon-host` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Host of the Graphite server to send metrics. |
| Type          | String |
| Default Value | "127.0.0.1" |

##### `graphite-carbon-port` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | TCP & UDP port of the Graphite server to send metrics. |
| Type          | Integer |
| Default Value | 2003 |

##### `graphite-carbon-api-port` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Graphite Carbon API port. |
| Type          | Integer |
| Default Value | 10002 |

##### `graphite-carbon-link-port` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Graphite Carbon Link port. |
| Type          | Integer |
| Default Value | 7002 |

##### `graphite-carbon-pickle-port` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Graphite Carbon Pickle port. |
| Type          | Integer |
| Default Value | 2004 |

##### `graphite-carbon-pprof-port` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Graphite Carbon pprof port. |
| Type          | Integer |
| Default Value | 7007 |    

##### `graphite-carbon-server-port` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Graphite Carbon HTTP port. |
| Type          | Integer |
| Default Value | 10003 |   


HTTP Graph are display via the giraffe JS library. One can create custom dashboard via Grafana.
Customizing the trends can be done modifying

```
/usr/share/replication-manager/dashboard/static/graph.js
```

Set the replication-manager host or IP address to tell where to found the graphite server

Replication and some databases status metrics are pushed inside carbon, the metrics are pushed with the server-id prefix name. to get unicity against nodes  


![graphs](/images/graphs.png)

We can advise usage of Statd and Collectd on each database node to add extra system metrics to the cluster.
