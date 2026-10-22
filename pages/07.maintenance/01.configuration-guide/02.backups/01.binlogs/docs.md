---
title: Binlogs
taxonomy:
    category: docs
---
### 7.2.2.1 Binlogs

replication-manager reads the binary logs of the servers it monitors in four ways: it watches
their rotation, scans the primary's new events for the security plugins, copies the closed
binlogs into the backup directory, and purges binlogs on the servers. This page shows each path,
what it reads, where the result goes, and the settings that drive it.

#### 7.2.2.1.1 Overview

```
  ┌─────────────────────────────────────────── primary ────────────────────────────────────────────┐
  │                mysql-bin.000041   mysql-bin.000042   mysql-bin.000043 (current)                │
  └───────────────┬───────────────────────────────┬────────────────────────────────┬───────────────┘
                  │                               │                                │
                  │ every tick:                   │ replica stream                 │ on rotation
                  │ file + position               │ (active only)                  │ (backup-binlogs)
                  ▼                               ▼                                ▼
  ┌──────────────────────────────┐ ┌──────────────────────────────┐ ┌──────────────────────────────┐
  │ 1. Rotation watch            │ │ 2. Event scanner             │ │ 3. Binlog copy               │
  │ binlog list, oldest time,    │ │ new QUERY events into an     │ │ mysqlbinlog | ssh | script   │
  │ binary-logs.meta.json        │ │ in-memory ring buffer        │ │ the closed binlog, then      │
  │ (server's repman folder)     │ │ (200 events per server)      │ │ the missing ones (keep)      │
  └──────────────────────────────┘ └──────────────────────────────┘ └──────────────────────────────┘
                  │                               │                                │
                  ▼                               ▼                                ▼
  ┌──────────────────────────────┐ ┌──────────────────────────────┐ ┌──────────────────────────────┐
  │ 4. Purge on servers          │ │ Security plugins             │ │ Backup directory             │
  │ PURGE BINARY LOGS on the     │ │ cleartext password,          │ │ <backups>/<cluster>/<server>/│
  │ primary and the replicas     │ │ credit card leak ...         │ │ binary-logs.meta.json,       │
  │ (force-binlog-purge*)        │ │ (binlog_events input)        │ │ in the next backup snapshot, │
  └──────────────────────────────┘ └──────────────────────────────┘ │ copies beyond keep purged    │
                                                                    └──────────────────────────────┘
```

Every path that streams a binlog presents a replica server id leased from a pool of 11 ids
starting at `check-binlog-server-id` (10000 to 10010 by default), so two consumers of one
replication-manager never kill each other's stream on the primary.

---

#### 7.2.2.1.2 Rotation watch

At every monitoring tick replication-manager reads the current binlog file and position of each
server (`SHOW MASTER STATUS`). When the file changes, the binlog rotated:

- the list of binlogs and their first and last event times are refreshed, parsed with
  `binlog-parse-mode`, and written to `binary-logs.meta.json` in replication-manager's folder of
  the server, `<working-dir>/<cluster>/<host>_<port>/`;
- `binlog-rotation-script` runs on the primary, when set;
- the binlog copy starts (path 3), when `backup-binlogs` is on and no failover runs.

##### `binlog-parse-mode`

| Item | Value |
| ---- | ----- |
| Description | How binlogs are read for their event times: `gomysql` (in process) or `mysqlbinlog` |
| Type | string |
| Default Value | "gomysql" |

##### `binlog-rotation-script`

| Item | Value |
| ---- | ----- |
| Description | Script run on the primary at each binlog rotation |
| Type | string |
| Default Value | "" |

---

#### 7.2.2.1.3 Event scanner (in-memory buffer for the security plugins)

With `monitoring-binlog-events` on, replication-manager keeps a replica stream open on the
**primary only**, and only on the **active** replication-manager (a standby never consumes the
primary's binlog). The stream starts at the primary's current position: only new events are read.

At each tick it reads what arrived, within 50 ms per event, so the monitoring tick never waits,
and keeps the **QUERY events** (statement text, default schema, server id, time; `BEGIN`,
`COMMIT` and `ROLLBACK` skipped) in a ring buffer **in memory, per server, newest first**, of
`monitoring-binlog-event-log-length` events. Nothing is written to disk. With the scanner off,
the buffer takes no memory.

The security plugins (`plugin-binlog-cleartext-password`, `plugin-binlog-creditcard-leak` ...) run
at each tick right after the scan and receive a copy of the buffer in their input
(`binlog_events`). Row events are not decoded: what the plugins see is SQL text, such as
statements in statement-based logging and DDL like `CREATE USER` or `ALTER USER`, which are
always logged as statements.

When the stream is reset again and again (another binlog consumer presenting the same replica
server id: a replica, another replication-manager, a `mysqlbinlog` fetch), the scanner pauses:
30 s after the third reset within 10 minutes, doubling up to 30 minutes, with a state saying why.

##### `monitoring-binlog-events`

| Item | Value |
| ---- | ----- |
| Description | Scan the primary's new binlog QUERY events for the binlog security plugins |
| Type | boolean |
| Default Value | false |

##### `monitoring-binlog-event-log-length`

| Item | Value |
| ---- | ----- |
| Description | Number of binlog QUERY events kept in memory per server for the security plugins |
| Type | integer |
| Default Value | 200 |

##### `check-binlog-server-id`

| Item | Value |
| ---- | ----- |
| Description | First replica server id of the pool the binlog consumers lease from (the pool is 11 ids) |
| Type | integer |
| Default Value | 10000 |

---

#### 7.2.2.1.4 Binlog copy (backups of binlogs)

With `backup-binlogs` on, the **primary's** binlogs are copied into its backup directory,
`<backups>/<cluster>/<server>/`:

1. **At each rotation**, the binlog that just closed is queued for copy; the rotation check never
   waits. One copy worker per server copies the queued binlogs in order, each once, as soon as
   no backup, snapshot of the backup directory or failover runs. While it waits, WARN0257 says
   for what, since when, and how many binlogs are to copy. The queue holds at most
   `backup-binlogs-keep` binlogs.
2. **Right after**, the catch-up: every binlog of the last `backup-binlogs-keep` that is missing
   from the backup directory is copied, as long as the server still has it.
3. **Then** the copies older than the keep window are deleted from the backup directory. With
   an archive attached, a copy is only deleted once a snapshot of the archive holds it, so no
   binlog is ever lost: see below.

How a binlog is copied is `binlog-copy-mode`:

| Mode | How |
| ---- | --- |
| `mysqlbinlog` (default; `client` is the same) | `mysqlbinlog --read-from-remote-server --raw` from the replication-manager host |
| `ssh` (default of the on-premise builds) | the file copied over SSH from the server's binlog directory |
| `script` | `binlog-copy-script <cluster> <host> <port> <ssh port> <binlog dir> <copy dir> <binlog>` |

`gomysql` is accepted by the setting but does not copy binlogs: use one of the three above.

How it ends up in the backup directory:

- Every copy is first written into a staging folder `.binlog.partial/` of the backup directory,
  and only moved into the backup directory once complete: the directory, and so its snapshots,
  only ever holds complete binlogs. A failed copy is removed from the staging folder.
- **Without backup encryption**, the complete copy is moved into the backup directory under its
  own name.
- **With backup encryption**, it is encrypted to `<binlog>.enc` with its integrity sidecar, and
  the plain copy is removed.
- Each copied binlog is listed in `binary-logs.meta.json` of the backup directory.
- A binlog copy and a snapshot of the backup directory never run together: a copy waits for the
  snapshot in progress, a snapshot waits for the copy in progress.
- A binlog copy takes no snapshot of its own. A snapshot is the whole backup directory: the
  one taken after a backup holds the backup with the binlog copies in the directory at that time.

With an archive attached, a binlog copy that leaves the keep window and that no snapshot holds
yet is kept while a **binlog-only snapshot** of the backup directory, tagged `trigger:binlogs`,
is taken; once it succeeds the copies beyond the window are deleted. With no backup in between,
a snapshot is so taken every `backup-binlogs-keep` binlogs at most, and every binlog copied is in
a snapshot of the archive. Each of these snapshots also holds the last backup of the directory:
it restores on its own. After a restart, the snapshots holding the copies are not known: the
first deletion takes a snapshot first. While copies wait for it, WARN0258 says how many, the
oldest and since when.

**Better lose binlogs than backups.** The binlog copy never stops, and the purge policy always
applies. The binlog-only snapshot is not taken when:

- `backup-archive-remove-original` is on: it would hold binlogs without their backup;
- the disk of the backup directory is over `backup-disk-treshold-warn`;
- the last one failed (it is tried again at the next copy beyond the window).

The copies beyond the window are then deleted anyway, and the **binlog continuity** of the server
in the archive is lost: WARN0259 gives the server, the binlogs in no snapshot, why and since when.
A point-in-time restore from a backup taken before them stops there. Binlogs dropped from the
copy queue (more than `backup-binlogs-keep` waiting) are tracked the same way. The state is kept
across restarts and ends with the next backup of the server in the archive: the chain is whole
from that backup on.

The binlog-only snapshots are purged by their own tagged policy, `trigger:binlogs` in
`backup-keep-tag-policies` (default `keep-within=1d`), counted apart from the backups: they never
push a backup out of `backup-keep-last`. Those taken since the last backup are always kept; the
policy sets how long the older ones stay, the point-in-time restore window before the last
backup. See Archiving, tagged purge policies.

Without an archive, the copies beyond the window are deleted at once.

##### `backup-binlogs` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Copy the primary's binlogs into its backup directory |
| Type | boolean |
| Default Value | false |

##### `backup-binlogs-keep` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Number of the primary's last binlogs kept in the backup directory (and caught up when missing) |
| Type | integer |
| Default Value | 10 |

##### `binlog-copy-mode`

| Item | Value |
| ---- | ----- |
| Description | How binlogs are copied: `mysqlbinlog`, `ssh` or `script` |
| Type | string |
| Default Value | "mysqlbinlog" ("ssh" for the on-premise builds) |

##### `binlog-copy-script`

| Item | Value |
| ---- | ----- |
| Description | Script copying a binlog, for `binlog-copy-mode` `script` (arguments above) |
| Type | string |
| Default Value | "" |

##### `backup-mysqlbinlog-path` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Path to the mysqlbinlog binary |
| Type | string |
| Default Value | "" |

When empty, the binary provided in the share directory is used. It can be old: installing the
client package of your database version is advised.

---

#### 7.2.2.1.5 Purge on the servers

replication-manager can purge binlogs on the servers themselves (`PURGE BINARY LOGS`):

- on the **primary**, when its binlogs take more than the **binlog budget**, and only while at
  least `force-binlog-purge-min-replica` replicas are connected (otherwise a warning says why it
  waits);
- on the **replicas**, down to the oldest binlog time still on the primary, keeping at least the
  current and the previous binlog, and only for a connected replica.

**The binlog budget** is `force-binlog-purge-total-size` when set (GB). At `0`, the default, it
follows the disk, from the first of these that is known:

| Known | Budget |
| ---- | ----- |
| the plan's disk (`prov-db-disk-size`, a provisioned cluster) and the database size (the schema monitor) | (plan disk - data) / 2 |
| the data disk's free space: reported by the jobs script (on premise, while SSH is available), else `information_schema.DISKS` | binlogs now + half the free space |
| nothing | 30 GB |

Half, so that the other half stays for the data, temporary files and a restore. The budget is
recomputed as the data grows and after a disk resize, and it never goes below two binlog files
(2 x `max_binlog_size`): WARN0264 says when the data leaves no more room. On MariaDB 11.4 and up
it is set as `max_binlog_total_size`. Under the dynamic configuration the plan sets that variable
instead: `prov-db-binlog-total-size-pct` of the plan's disk, set live on every MariaDB 11.4+ server
whether `force-binlog-purge` is on or not.

**Replicas and the purge, by version**

| Database | Who purges by size | A connected replica (IO thread running), even lagging | A disconnected replica (stopped, down, IO error) |
| ---- | ---- | ---- | ---- |
| MariaDB 11.4 and up | the server itself (`max_binlog_total_size`, set from the budget, or by the plan under the dynamic configuration) | **safe**: the server never purges a binlog a replica's dump thread is reading | **at risk**: the server purges past the budget whatever it still needs; WARN0265 names it, and it needs a reseed unless it reconnects first |
| MariaDB before 11.4, MySQL, Percona | replication-manager (`PURGE BINARY LOGS`) | **safe** | **safe**: the purge stops at the oldest binlog any replica still reads (WARN0107), the primary keeps more than the budget |

On MariaDB 11.4 and up the server also purges by size only while `slave_connections_needed_for_purge`
replicas (1 by default) are connected: with every replica disconnected, nothing is purged by size.
Only the server's own expiry by time (`binlog_expire_logs_seconds`, `expire_logs_days`), not
replication-manager's, can remove a binlog a replica still needs on any version.

So an active replica is never broken by the purge, on any version. On MariaDB 11.4 and up, the one
to watch is a replica left disconnected longer than the budget's worth of binlogs: bring it back,
or let replication-manager reseed it.

A binlog still queued for copy (path 3) is never purged by replication-manager: the purge stops
just before it. The server's own expiry (`binlog_expire_logs_seconds`, `expire_logs_days`) is not
replication-manager's: a binlog it removes before it was copied can no longer be caught up;
WARN0257 says when the oldest binlog to copy is the server's oldest.

##### `force-binlog-purge`

| Item | Value |
| ---- | ----- |
| Description | Purge the primary's binlogs beyond `force-binlog-purge-total-size` |
| Type | boolean |
| Default Value | false |

##### `force-binlog-purge-total-size`

| Item | Value |
| ---- | ----- |
| Description | The binlog budget in GB: the primary's binlogs beyond it are purged; 0 follows the disk (see above) |
| Type | integer |
| Default Value | 0 (30 before 3.1.45) |

##### `force-binlog-purge-min-replica`

| Item | Value |
| ---- | ----- |
| Description | Replicas connected needed to purge the primary's binlogs |
| Type | integer |
| Default Value | 1 |

##### `force-binlog-purge-replicas`

| Item | Value |
| ---- | ----- |
| Description | Purge the replicas' binlogs down to the oldest binlog time on the primary |
| Type | boolean |
| Default Value | false |

##### `force-binlog-purge-on-restore`

| Item | Value |
| ---- | ----- |
| Description | Purge binlogs on restore |
| Type | boolean |
| Default Value | false |
