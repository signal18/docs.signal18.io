---
title: Crash Recovery Configuration
---

## Crash recovery

**replication-manager** <1.1 release only rejoin failed master that get equal GTID at election time in such case it can re-attach to the cluster

##### `autorejoin` (0.7)

| Item          | Value |
| ----          | ----- |
| Description   |  Used to turn out all scenarios of crash recovery |
| Type          | boolean |
| Default Value | true |

**replication-manager** 1.1, rejoining of dead master has been improved to cover more scenarios.

MariaDB clients binaries are used to remotely backup binlogs from rejoining node:

##### `autorejoin-backup-binlog` (0.7)

| Item          | Value |
| ----          | ----- |
| Description   |  Used to to fetch and save rejoining master binary logs, only delta binary logs with the last elected master are saved |
| Type          | boolean |
| Default Value | true |

*mysqlbinlog --read-from-remote-server*
they are saved into the monitoring working directory in a crash sub directory for later reuse with flashback or for the DBA eyes in case of point in time recovery.

>Note that the server-id to backup binlog used by **replication-manager** is 1000, don't use it on your database cluster nodes

**replication-manager** track different crash state for rejoin:

GTID of the new leader at time of election is equal to GTID of the joiner, we proceed with rejoin.

GTID is behing on joiner, we backup extra events, if semisync replication was in sync status, we can do flashback to come back to a physical state that client connections have never seen.  

##### `autorejoin-flashback` (1.1)
| Item          | Value |
| ----          | ----- |
| Description   |  Automatically rejoin a failed server to the current master and flashback at the time of election if new master was elected. |
| Type          | boolean |
| Default Value | false |

##### `autorejoin-flashback-on-sync` (2.1)
| Item          | Value |
| ----          | ----- |
| Description   | Use flashback only when replication state was sync on crash. |
| Type          | boolean |
| Default Value | true |

GTID is behind and semi-sync replication status at election was desynced, we can or flashback or restore from a full state transfert (SST )**replication-manager** since 1.1 force SST.

##### `autorejoin-flashback-on-unsync` (2.1)
| Item          | Value |
| ----          | ----- |
| Description   | Use flashback also when replication state was unsync on crash. |
| Type          | boolean |
| Default Value | false |


GTID is ahead but semi-sync replication si not used, status at election is unknown, we restore the joiner via mysqldump from the new leader when replication-manager settings use the rejoin-mysqldump flag.

##### `autorejoin-mysqldump ` (1.1)
| Item          | Value |
| ----          | ----- |
| Description   | Automatically rejoin a failed server to the current master using mysqldump. |
| Type          | boolean |
| Default Value | false |


If none of above method is enable **replication-manager** will call external scripts

##### `autorejoin-script` (1.1)
| Item          | Value |
| ----          | ----- |
| Description   | Path to a rejoin script. |
| Type          | boolean |
| Default Value | false |

Script is passing the server to rejoin as first argument and the new master in current topology.

In some cascading failure scenarios **replication-manager** have not way to found replication position of an election, this will happen every time no slaves is available when the last master crashed. We advise 3 node cluster to limit painful reseeding of big databases.

## All cluster down

The default rejoining method is to not promote a slave as a master when the no crash information state can be found and to wait for the old master to recover.

##### `failover-restart-unsafe` (2.1)
| Item          | Value |
| ----          | ----- |
| Description   | Failover when cluster down if a slave is start first. |
| Type          | boolean |
| Default Value | false |

High availability when false

|  Master/Slave/Kill  | Read/Write/Err |
|---------------------|----------------|
| MS-MK-MS            | RW-RW-RW       |
| MS-MK-KK-KS-MS      | RW-RW-EE-RE-RW |
| MS-MK-KK-MK-MS      | RW-RW-EE-RW-RW |
| MS-KM-SM            | RW-RW-RW       |
| MS-KM-KK-KM-SM      | RW-RW-EE-RW-RW |
| MS-KM-KK-SK-SM      | RW-RW-EE-RE-RW |

Change the `failover-restart-unsafe` to flavor HA against protecting over data lost and do failover on first slave to ping: after a full DC crash or if the master never show up. It was reported to work with PeaceMaker when **replication-manager** is failover and the last node available is a slave. This mode rediscover the master from this slave from the replication information.     

High availability when false
|  Master/Slave/Kill  | Read/Write/Err | Lost |
|---------------------|----------------|------|     
| MS-MK-MS            | RW-RW-RW       |      |
| MS-MK-KK-KM-SM      | RW-RW-EE-RW-RW | L    |
| MS-MK-KK-MK-MS      | RW-RW-EE-RW-RW |      |
| MS-KM-SM            | RW-RW-RW       |      |
| MS-KM-KK-KM-SM      | RW-RW-EE-RW-RW |      |
| MS-KM-KK-MK-MS      | RW-RW-EE-RW-RW | L    |


This setup can possibly elect a very late slave as first leader and when no crash information state is found for rejoining the old master than the replication-manager will provision it using full state transfer via mysqldump or external script
