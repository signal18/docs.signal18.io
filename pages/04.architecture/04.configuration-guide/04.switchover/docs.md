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
| Description | A write query or an open InnoDB transaction running on the master for at least this many seconds counts as a long write: the switchover waits for it to complete, up to `switchover-wait-trx`, and is cancelled if it is still there. |
| Type | integer |
| Default Value | 10 |

**What is checked.** First thing in a switchover, before anything is frozen or locked,
**replication-manager** counts on the master the queries other than SELECT running for at
least this many seconds, and the InnoDB transactions open for at least this long.

**What happens.** Nothing is locked at that point, so the application does not see the
wait: the count is repeated every 2 seconds for up to `switchover-wait-trx` seconds, and
the switchover goes on as soon as it reaches zero. Each pass logs one line per session,
with its id, user, host, running time, transaction age, rows modified and rows locked, so
you can see what the switchover is waiting for. Still there at the deadline: the switchover
is cancelled with `Long updates running on master. Cannot switchover`.

**Why it is never killed.** Killing a transaction starts a rollback whose duration nobody
knows: proportional to the rows it modified, not interruptible, and resumed by InnoDB
recovery if the server is restarted. That rollback would run on the server being demoted,
holding its row locks while the new master's writes replicate onto it. Waiting or cancelling
loses nothing; killing can cost an hour.

**How to get past a cancel.**

- Let the transaction finish and run the switchover again. The log lines above and the Top
  page show the session.
- Raise `switchover-wait-trx` so the switchover waits longer. Since **3.1.42** both settings
  are dynamic: **Settings → Replication Failover → Switchover Long Write Threshold** and
  **Switchover Wait Transactions**, or the settings API; before that release they live in
  the cluster configuration file and take a restart of **replication-manager**.
- Kill the session yourself only when the `rows modified` in the log line is small enough
  that its rollback is immediate.

A failover does not run this check: a failed master has no queries left to protect.

**What follows the guard.** The switchover then flushes the master's tables without a lock
(`FLUSH TABLES`, so the later read lock is fast), also bounded by `switchover-wait-trx`, and
freezes the master: read only, a grace period of `switchover-wait-kill` milliseconds for the
writes still in flight, then every remaining client session is killed and the global read
lock (`FLUSH TABLES WITH READ LOCK`) is taken before the candidate is promoted.


##### `switchover-wait-trx` (2.0), `wait-trx` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Seconds the switchover waits, before freezing anything, for the long writes found by `switchover-wait-write-query` to complete, then again for the flush of the master's tables. Cancelled if either is still pending at the deadline. Dynamic since 3.1.42 (Settings → Replication Failover → Switchover Wait Transactions). |
| Type | integer |
| Default Value | 10 |

##### `switchover-slave-wait-catch` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Switchover wait for replication to catch up before switching extra slaves, when using GTID don't wait can speed up switchover but may hide issues liek writing on the old master with super user |
| Type | boolean |
| Default Value | true |
