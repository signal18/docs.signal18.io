---
title: CDC Feeds from PostgreSQL
taxonomy:
    category: docs
---

## 11.12 CDC Feeds from PostgreSQL

> **Available since:** replication-manager **v4.0.1** (enterprise plugin `replplugin-postgresql`, a support seat is needed)

A **CDC feed** carries the changes of chosen tables of a PostgreSQL cluster into a MariaDB cluster, both monitored by the same replication-manager. Use it to give a MariaDB application the data of a PostgreSQL one, or as the first half of a migration from PostgreSQL to MariaDB.

- The changes are read from PostgreSQL's write-ahead log by **logical decoding** (a publication and a replication slot). No trigger is ever created.
- The tables are first copied in full (the **initial load**) from a snapshot taken with the slot, then kept up to date: the copy and the changes join without a gap.
- The feed applies to the MariaDB cluster's master, in InnoDB tables, through the CDC checkpoints of replication-manager (a crash never loses nor applies twice a change).
- The feed shows as a replication link of kind `cdc` of the MariaDB cluster: its position (the PostgreSQL LSN), what is applied, its lag and its last error.

### Requirements on PostgreSQL

These are never changed by replication-manager; they are your choice:

- PostgreSQL 12 or later (13 or later recommended), `wal_level = logical` (a restart of PostgreSQL).
- The monitoring user of the PostgreSQL cluster has the `REPLICATION` attribute (or is superuser) and can read the carried tables.
- Each carried table has a **primary key**, and a replica identity `DEFAULT` or `FULL`.
- `max_slot_wal_keep_size` set: a replication slot keeps WAL on the server's disk while the feed is stopped. replication-manager raises **WARN0360** while it is unset.

### Settings (MariaDB cluster, Settings > High Availability > CDC Feed)

| Setting | Default | Meaning |
|---|---|---|
| `replication-plugin-feed-source-cluster` | (empty) | The PostgreSQL cluster to read. Empty: no feed. |
| `replication-plugin-feed-database` | | The PostgreSQL database (one per feed). |
| `replication-plugin-feed-tables` | | Comma-separated `schema.table` or `schema.*`. |
| `replication-plugin-feed-create-source-objects` | `false` | Allows the feed to create its publication and its replication slot `repman_<cluster>` on PostgreSQL. Without it, the feed tells you the exact statement to run. |
| `replication-plugin-feed-retained-max` | `10240` | MB of WAL the slot may keep. Above it, the feed drops **its own** slot to protect PostgreSQL's disk, stops with **ERR00140**, and needs a new initial load. |
| `replication-plugin-checkpoint-interval` | `10` | Seconds per checkpoint (freshness). |

Every statement the feed runs on PostgreSQL (create or drop of its publication and slot) is written to replication-manager's log as it ran. The feed never changes a publication or a slot it did not create.

### Types

Each PostgreSQL type becomes one MariaDB type: integers as is, `numeric(p,s)` as `DECIMAL(p,s)`, `boolean` as `TINYINT(1)`, `text` and `varchar` as `LONGTEXT` / `VARCHAR`, `bytea` as `LONGBLOB`, `uuid` as `UUID`, `json` / `jsonb` as `JSON`, `timestamptz` as `DATETIME` in UTC, arrays as JSON arrays, enums as `ENUM`. String columns use `utf8mb4_nopad_bin`, so keys distinct on PostgreSQL stay distinct.

A type without a faithful MariaDB equivalent (`interval`, `time with time zone`, `money`, ranges, composites, domains) refuses its table, with the reason in the feed's state. A value MariaDB cannot hold (NaN, infinity, a BC date) stops the feed with the table and column. Nothing is ever converted silently.

### States

| State | Meaning |
|---|---|
| CINF0017 | the feed runs: position captured and applied, lag, counts |
| CINF0040 | the WAL the slot keeps on PostgreSQL and the bound; the initial load's progress |
| WARN0360 | a warning about the source (`max_slot_wal_keep_size` unset, a slot over the bound that the feed did not create) |
| WARN0242 | the feed is stopped, with the reason (a refused table, a schema change, a value that does not fit), restarted with a backoff |
| ERR00140 | the feed waits for a new initial load: its slot was dropped at the bound, lost, or a TRUNCATE ran on the source |

### Schema changes

PostgreSQL's logical decoding carries no DDL. When a carried table changes on PostgreSQL, the feed stops with the change named. Apply the same change on the MariaDB table, and the feed resumes; or start a new initial load.

### Actions

- **New Initial Load**: the rejoin of the feed. Its MariaDB tables are emptied and copied again from a new snapshot, then the changes follow.
- **Drop Slot and Publication on Source**: with the feed switched off (source cluster empty), drops the slot and the publication the feed created, nothing else, and shows the statements run.
