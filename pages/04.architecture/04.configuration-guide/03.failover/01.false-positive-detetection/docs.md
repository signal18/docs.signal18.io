---
title: False Positive
taxonomy:
    category: docs
---

##### `failover-falsepositive-ping-counter` (2.0)  `failcount` (0.7)

| Item          | Value |
| ----          | ----- |
| Description   |  Failover after this number of ping failures, interval is driven by `failover-falsepositive-heartbeat`. |
| Type          | integer |
| Default Value | 5 |

##### `failover-falsepositive-heartbeat` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Cancel failover if one slave can still fetch events from the master. |
| Type          | boolean |
| Default Value | true |

The failover is cancelled (ERR00028) while a direct replica of the master received something from it recently: a heartbeat when the master is idle, an event when it is busy. Recently means within the replica's heartbeat period + `monitoring-ticker` + `failover-falsepositive-heartbeat-timeout`. The check is read on the replication channel replication-manager manages (`replication-source-name`), and does not wait:

* MariaDB records no time of the last heartbeat, only a counter per channel (`Slave_received_heartbeats` in `SHOW ALL SLAVES STATUS`): replication-manager notes the time it sees that counter, or the IO position, move at each monitoring tick.
* MySQL and Percona 5.7+ record the time of the last heartbeat (8.0: and of the last event received) in `performance_schema.replication_connection_status`.

While the master is suspect or failed, each replica shows how long ago it last received from the master (CINF0022).

GUI: Settings, Failover, "Failover Cancelled While Replicas Receive" (`failover-falsepositive-heartbeat`) and "Failover Replica Receive Margin" (`failover-falsepositive-heartbeat-timeout`).

A master that is really dead stops sending: once its replicas' last receive is older than that window, the failover goes on. A failover decision therefore waits up to one heartbeat period: a replica whose heartbeat period is above `force-slave-heartbeat-time`, or off, raises WARN0050. Lower its period, or let `force-slave-heartbeat` set it.

Before replication-manager issue 2208 was fixed, this check read the global status `Slave_received_heartbeats`, which counts only MariaDB's default connection and does not exist on MySQL 5.7+: it never cancelled a failover on a named channel or on MySQL.

##### `failover-falsepositive-heartbeat-timeout` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Margin in seconds added to the heartbeat period and `monitoring-ticker` for `failover-falsepositive-heartbeat`: a replica that received from the master within that window cancels the failover. |
| Type          | integer |
| Default Value | 3 |

##### `failover-falsepositive-external` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Cancel failover when http call to host of the master port 80 return header response code 200 OK, some inetd scripts can be googled to make external monitoring. |
| Type          | boolean |
| Default Value | false |


##### `failover-falsepositive-external-port` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Custom TCP port for external check `failover-falsepositive-external`. |
| Type          | integer |
| Default Value | 80 |


##### `failover-falsepositive-maxscale` (1.1, 2.0-osc, 2.0-pro)

| Item          | Value |
| ----          | ----- |
| Description   | Cancel failover when maxscale monitor detect alive master. See the maxscale configuration section for more details  |
| Type          | boolean |
| Default Value | "false"|

##### `failover-falsepositive-maxscale-timeout` (1.1, 2.0-osc, 2.0-pro)

| Item          | Value |
| ----          | ----- |
| Description   | Failover wait this timer in second that MaxScale monitor detect the failed master for `failover-falsepositive-maxscale`.  |
| Type          | integer |
| Default Value | 14 |
