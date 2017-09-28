---
title: Topologies
---

### GTID Replication

**replication-manager**  do not support MariaDB version lower than 10.0

For MariaDB Server 10.0 and upper **replication-manager** always use GTID POS for failover or switchover regardless of using GTID in replication stream definition. This is because GTID are always present in the binary logs of MariaDB and could be use to point a slave to his master.

>For MariaDB Server 10.0 and upper **replication-manager** can force positional replication back after a failover or a switchover but this will disable crash safe slave and usage of concurrency in replication streams.    

##### `force-slave-no-gtid-mode` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Automatically disable gtid mode on slave. |
| Type          | Boolean |
| Default Value | false |

### Positional Replication

Positional replication will be used for pointing replication on MySQL or Percona Servers < 5.7 and >= 5.7 if no GTID replication are set.

In this scenario **replication-manager** is working in best effort mode, it was design to work agent less. **replication-manager** do not to parse binary logs to try to match correct position based on the content of replication EVENTS. GTID are here for fixing this requirement and we state that using them is the correct way to go.  

**replication-manager** would not introduce extra complexity to correct a database lack of unique EVENT identifier. If you feel that this have some importance we can introduce PSEUDO GTID on demand like it is done in Orchestrator to enable founding position by reversing binary log via SQL command.

**replication-manager** design choice have implications:

On MariaDB 10.0 and upper, positional replication will be lost after first switchover in flavor of using GTID POS.

For other non GTID databases extra slaves are staying under the old master. They are later move on the new master by fixing non valid relay architecture. That means that the old master need to rejoin the cluster first and we can't give any insurance this will succeed.
