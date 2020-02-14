---
title: False Positive Configuration
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

##### `failover-falsepositive-heartbeat-timeout` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Failover wait this timer in second that replication detect the failed master for `failover-falsepositive-heartbeat`. |
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
