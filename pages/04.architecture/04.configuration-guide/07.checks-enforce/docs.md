---
title: Checks & Enforce
taxonomy:
    category: docs
---

**replication-manager** enforces best replication and database configuration practice using the following parameters. Every possible enforcement is producing warnings to let you know about what can be improved in your database setup.  

## 4.5.6.1 Checks

##### `check-binlog-filters` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Check that all possible masters have equal binlog filters. |
| Type          | Boolean |
| Default Value | true |

##### `check-replication-filters` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Check that all possible slaves have equal replication filters. |
| Type          | Boolean |
| Default Value | true |


##### `check-replication-state` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Check that all replications are running fine. |
| Type          | Boolean |
| Default Value | true |

## 4.5.6.2 Enforce

##### `force-slave-readonly` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Automatically force READ ONLY on slaves. |
| Type          | Boolean |
| Default Value | true |


##### `force-sync-binlog` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Automatically force master crash safe. |
| Type          | Boolean |
| Default Value | false |

##### `force-sync-innodb` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Automatically force master innodb crash safe. |
| Type          | Boolean |
| Default Value | false |

##### `force-slave-semisync` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Automatically force semi-sync usage. |
| Type          | Boolean |
| Default Value | false |

##### `force-binlog-annotate-embedded` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Automatically activate annotate event allow to print SQL statement on slave using SHOW PROCESSLIST . |
| Type          | Boolean |
| Default Value | false |

##### `force-binlog-checksum` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Automatically force binlog checksum, miss functioning network or inter continental networking can corrupt replication protocol. Checksum detect and repair the protocol by re-transmitting.  |
| Type          | Boolean |
| Default Value | false |

##### `force-binlog-compress` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Automatically force on disk binlog compression on MariaDB 10.2. |
| Type          | Boolean |
| Default Value | false |

##### `force-binlog-row` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Automatically activate binlog row format on master. |
| Type          | Boolean |
| Default Value | false |

##### `force-binlog-slowqueries` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Automatically activate logging of slow query run from replication in slow log. |
| Type          | Boolean |
| Default Value | false |

##### `force-slave-gtid-mode` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Automatically activate gtid mode on slave. |
| Type          | Boolean |
| Default Value | false |

##### `force-slave-gtid-mode-strict` (2.1)

| Item          | Value |
| ----          | ----- |
| Description   | Enforce activate gtid strict mode on slave. |
| Type          | Boolean |
| Default Value | false |

It was found that it can be used  keeping default non strict mode when slave restart, when using Memory table on slave it would break strict mode replication such parameter enable replication manager to set back strict mode after the restart 

##### `force-slave-no-gtid-mode` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Automatically disable gtid mode on slave. |
| Type          | Boolean |
| Default Value | false |

##### `force-slave-heartbeat` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Set the heartbeat period of each replica's managed channel to `force-slave-heartbeat-time` when it is longer or off. The change stops and starts the channel once (`STOP SLAVE`, `CHANGE MASTER ... MASTER_HEARTBEAT_PERIOD`, `START SLAVE`), only while both its threads run, and opens CINF0023 naming the server, the channel and both periods; a failure raises WARN0268 and is not retried while the period stays the same. Without it, a longer period raises WARN0050 while `failover-falsepositive-heartbeat` is on. GUI: Settings, Replication, Enforce Replication Heartbeat. |
| Type          | Boolean |
| Default Value | false |

##### `force-slave-heartbeat-retry` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Replication heartbeat retry on slave. |
| Type          | Integer |
| Default Value | 5 |    

##### `force-slave-heartbeat-time` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Heartbeat period, in seconds, that `force-slave-heartbeat` sets and that replication-manager uses in its `CHANGE MASTER`. A replica with a longer period raises WARN0050 while `failover-falsepositive-heartbeat` is on, as a failover decision waits up to one period. GUI: Settings, Replication, Replication Heartbeat Period. |
| Type          | Integer |
| Default Value | 3 |   


##### `force-noslave-behind` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Automatically force no slave behing. |
| Type          | Boolean |
| Default Value | true |
