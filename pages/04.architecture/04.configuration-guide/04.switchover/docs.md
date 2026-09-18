---
title: Switchover
taxonomy:
    category: docs
---
## 4.5.3.1 Switchover Configuration

##### `switchover-at-equal-gtid` (1.1), `gtidcheck` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Switchover only when slaves are fully in sync. |
| Type | boolean |
| Default Value | false |   

##### `switchover-at-sync` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Switchover Only when state semisync is sync for last status. |
| Type | boolean |
| Default Value | false |

##### `switchover-wait-kill` (1.1), `wait-kill` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Switchover wait this many milliseconds before killing threads on demoted master. |
| Type | integer |
| Default Value | 5000 |


##### `switchover-wait-write-query` (1.1), `wait-write-query` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Switchover is cancelled when a write query or an open InnoDB transaction has been running on the master for at least this many seconds. |
| Type | integer |
| Default Value | 10 |

**What is checked.** Just before demoting the master, **replication-manager** counts on it
the queries other than SELECT running for at least this many seconds, and the InnoDB
transactions open for at least this long. If the count is not zero the switchover stops
there, before anything has changed, with the log line `Long updates running on master.
Cannot switchover`.

**Why.** The next step of a switchover takes a global read lock on the master
(`FLUSH TABLES WITH READ LOCK`), waits `switchover-wait-trx` seconds for it, then kills the
client threads still running after `switchover-wait-kill` milliseconds. A long write or an
open transaction either holds that lock and stalls every session queued behind it, or gets
killed and rolled back. Refusing is the choice that loses nothing.

**How to get past it.** There is no force option on the switchover itself.

- Let the transaction finish or kill it yourself, then switch over. The Top page shows the
  long and sleeping transactions with their session id.
- Raise the value, for example to 3600. Since **3.1.42** it is a dynamic setting: in the
  dashboard under **Settings → Replication Failover → Switchover Cancel on Long Write**, or
  through the settings API; before that release it lives in the cluster configuration file
  and takes a restart of **replication-manager**. Once raised, the switchover proceeds and
  the threads still running under the read lock are killed after `switchover-wait-kill`:
  the long transaction is rolled back. This disables the guard for every switchover, not
  just one; put the value back afterwards.

A failover does not run this check: a failed master has no queries left to protect.


##### `switchover-wait-trx` (2.0), `wait-trx` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Switchover is cancel after this timeout in second if can't return from FTWRL. |
| Type | integer |
| Default Value | 10 |

##### `switchover-slave-wait-catch` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Switchover wait for replication to catch up before switching extra slaves, when using GTID don't wait can speed up switchover but may hide issues liek writing on the old master with super user |
| Type | boolean |
| Default Value | true |
