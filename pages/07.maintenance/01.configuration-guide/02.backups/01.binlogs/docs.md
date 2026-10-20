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
  │ (force-binlog-purge*)        │ │ (binlog_events input)        │ │ snapshot to the archive,     │
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

1. **At each rotation**, the binlog that just closed is copied.
2. **Right after**, the catch-up: every binlog of the last `backup-binlogs-keep` that is missing
   from the backup directory is copied, as long as the server still has it. Not while a backup
   runs.
3. **Then** the copies older than the keep window are deleted from the backup directory.

How a binlog is copied is `binlog-copy-mode`:

| Mode | How |
| ---- | --- |
| `mysqlbinlog` (default; `client` is the same) | `mysqlbinlog --read-from-remote-server --raw` from the replication-manager host |
| `ssh` (default of the on-premise builds) | the file copied over SSH from the server's binlog directory |
| `script` | `binlog-copy-script <cluster> <host> <port> <ssh port> <binlog dir> <copy dir> <binlog>` |

`gomysql` is accepted by the setting but does not copy binlogs: use one of the three above.

How it ends up in the backup directory:

- **Without backup encryption**, the binlog is written straight into the backup directory under its
  own name.
- **With backup encryption**, it is copied into a staging folder `.binlog.partial/` of the backup
  directory, encrypted to `<binlog>.enc` with its integrity sidecar, and the plain copy is removed.
  A failed copy is removed from the staging folder.
- Each copied binlog is listed in `binary-logs.meta.json` of the backup directory.
- After a rotation copy, the backup directory is snapshotted into the attached archive (the
  catch-up copies are not, to avoid one snapshot per missing binlog).

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

- on the **primary**, when its binlogs take more than `force-binlog-purge-total-size` GB, and only
  while at least `force-binlog-purge-min-replica` replicas are connected (otherwise a warning says
  why it waits);
- on the **replicas**, down to the oldest binlog time still on the primary, keeping at least the
  current and the previous binlog, and only for a connected replica.

A binlog purged on the primary before it was copied can no longer be caught up (path 3).

##### `force-binlog-purge`

| Item | Value |
| ---- | ----- |
| Description | Purge the primary's binlogs beyond `force-binlog-purge-total-size` |
| Type | boolean |
| Default Value | false |

##### `force-binlog-purge-total-size`

| Item | Value |
| ---- | ----- |
| Description | Size of the primary's binlogs, in GB, above which they are purged |
| Type | integer |
| Default Value | 30 |

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
