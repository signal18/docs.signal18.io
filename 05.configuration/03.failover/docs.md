---
title: Failover Configuration
---

##### `failover-mode` (2.0), `interactive` (0.6)

| Item          | Value |
| ----          | ----- |
| Description   | Turn ON automatic failover.|
| Type          | enum |
| Possible Value | "manual" , "automatic" |
| Default Value | "manual" |

##### `failover-at-sync` (0.7)

| Item          | Value |
| ----          | ----- |
| Description   | Failover only when state semisync is sync for last status. |
| Type          | boolean |
| Default Value | false |


##### `failover-max-slave-delay` (2.0), `maxdelay` (0.6)

| Item          | Value |
| ----          | ----- |
| Description   | Election ignore slave with replication delay over this time in second. |
| Type          | integer |
| Default Value | 30 |

##### `failover-time-limit`(0.7)

| Item          | Value |
| ----          | ----- |
| Description   | Failover is canceled if previous failover took place that number of second before the time now, 0 means unlimited, this is use to prevent hardware network issue this can limit flip flap failover issues. |
| Type          | integer |
| Default Value | 0 |

##### `failover-limit` (1.1), `failcount` (0.7)
| Item          | Value |
| ----          | ----- |
| Description   | Failover is canceled if the number of failover reach that counter. The console, http or api can rest the internal counter, 0 means unlimited.  |
| Type          | integer |
| Default Value | 5 |

##### `failover-readonly-state` (2.0), `readonly` (0.7)

| Item          | Value |
| ----          | ----- |
| Description   | Failover and switchover set slaves to read-only |
| Type          | boolean |
| Default Value | true |

##### `failover-event-scheduler` (1.0)

| Item          | Value |
| ----          | ----- |
| Description   | Failover activate and disable event scheduler. |
| Type          | boolean |
| Default Value | false |

##### `failover-event-status` (1.0)

| Item          | Value |
| ----          | ----- |
| Description   | Failover change the event status ENABLE OR DISABLE ON SLAVE. This is used when all cluster have event scheduler enable. |
| Type          | boolean |
| Default Value | false |

##### `failover-post-script` (2.0),  `post-failover-scripts` (0.7)

| Item          | Value |
| ----          | ----- |
| Description   | Full path of a post failover script. This is call after the new leader has been setup. |
| Type          | string |
| Default Value | "" |

Used to re-enable traffic but before replication-manager take care of repointing other slaves.

Parameters passed to script :

oldMaster.Host, electedMaster.Host, oldMaster.Port, cluster.master.Port
bash shell example : $1,$2,$3,$4

##### `failover-pre-script` (2.0),  `pre-failover-scripts` (0.7)

| Item          | Value |
| ----          | ----- |
| Description   | Full path of a pre failover script. This is call before the new leader has been setup. |
| Type          | string |
| Default Value | "" |

Used to disable traffic on old master before replication-manager continue with failover or switchover. At this  point a flush table with read lock is already issue on the old master.  

Parameters passed to script :

oldMaster.Host, electedMaster.Host, oldMaster.Port, cluster.master.Port
bash shell example : $1,$2,$3,$4


##### `failover-restart-unsafe` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Failover to the first restarted node when full cluster down, can be a slave  |
| Type          | boolean |
| Default Value | false |


### Deprecated

##### `interactive`  (2.0)

| Item          | Value |
| ----          | ----- |
| Description   | Tell to monitor in post 2.0 release |
| Type          | integer |
| Default Value | 1 |
