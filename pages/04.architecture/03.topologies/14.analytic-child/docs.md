---
title: "ColumnStore and Analytic Child Clusters (CDC)"
taxonomy:
    category: docs
---

Some engines cannot be fed by native replication: they take no row UPDATE or DELETE from a
replica's applier. For them, replication-manager carries the parent's tables with a **CDC
plugin**. The plugin reads the parent's binlog, groups the changes into small checkpoints, and
applies them to the copies on a **child cluster**. The parent is never changed. The child is
never in the parent's topology, so it is never a failover candidate.

Two kinds of child use it:

- a **ColumnStore child** (topology `columnstore`): every table of the parent's backup is
  converted to ColumnStore at the seed;
- an **analytic child**: the tables you choose are made into the engine of a **destination
  plugin** (`replplugin-<name>`) at the seed. A table the destination cannot hold stays InnoDB,
  replicated natively, with a schema state giving the reason.

## Creating one

From the cluster menu **Provision > New Child Cluster**, choose the topology:

- **ColumnStore** (needs the parent backed up as a splitdump);
- **Analytic copies**, then give the destination plugin's name and the tables
  (`schema.table` or `schema.*`, comma separated).

The plan is shown first, or why it is refused (no logical backup, the plugin is not installed,
the plugin needs an enterprise plan).

The API is `POST /api/clusters/{parent}/actions/create-child`, with the body
`{"name", "topology": "columnstore", "confirm"}` or `{"name", "destination", "tables", "confirm"}`.
The MCP tool is `cluster-create-child`. The destination plugin's manifest gives the child's
default database image, the configurator tags its servers need, and whether the child has one
database.

## Settings (Setup > High Availability > Analytic Child)

| Setting | Default | What it does |
|---|---|---|
| `replication-plugin-destination` | empty | the destination plugin of an analytic child; empty: none |
| `replication-plugin-tables` | empty | the tables it copies, `schema.table` or `schema.*` |
| `replication-plugin-source` | `master` | the parent server whose binlog the plugin reads: `master` (followed after a failover or a switchover), or `host:port` of a dedicated replica |
| `replication-plugin-checkpoint-interval` | 10 | seconds of binlog per checkpoint |
| `replication-plugin-merge-interval` | 0 | 0: each checkpoint is applied to the copies; more: checkpoints are staged in InnoDB on the child and merged every this many seconds |

The tables and the destination are read at the next seed from the parent.

### Freshness or cost: the merge interval

The merge interval is your choice:

- **0**: the copies follow the parent within seconds.
- **More**: each checkpoint is staged at once in InnoDB on the child (cheap), and the copies
  receive the staged changes in bulk every interval.

On a destination without indexes, applying each checkpoint scans the table for each batch of
keys, so its cost grows with the table's size times the parent's write rate. For tables of a few
million rows, or a busy parent, `3600` (one hour) costs far less; the settings page suggests it
when a copied table passes 3 million rows. A merge interrupted by a crash is run again and gives
the same table.

### A dedicated replica as the source

A replica used as the source must have `binlog_format=ROW`, `binlog_row_image=FULL`,
`log_slave_updates=ON` and GTID. If it is lost, the plugin pauses with a state and never moves
to another server by itself: point the setting to another replica, or back to `master`.

## States

| State | Meaning |
|---|---|
| CINF0017 | the plugin runs: GTID captured and applied, lag, rows |
| CINF0030 | the last merge of the staging, the next one |
| WARN0242 / WARN0243 | the plugin is stopped and will restart / an apply fails and is retried |
| WARN0340 | a chosen table stayed as it was, with the reason (schema state) |
| WARN0341 | a DDL on the parent is not applied to the copy: apply it on the child, or seed again |
| ERR00130 | the source is refused: its binlog cannot carry a full copy (the reason is given) |
| ERR00131 | the source is lost or unknown: the plugin is paused |
| ERR00132 | the plugin is not installed, or the plan does not grant it |

## Requirements and support

- MariaDB parents of 11.4.13, 11.8.9 or later need this release's plugin. Older plugins captured
  nothing on those releases (the binlog positions changed) while reporting no error.
- **ColumnStore** stays open source and is **community-maintained**: one regression test, on
  the MariaDB LTS release we already test; no ColumnStore-specific features; fixes as they come.
  Guaranteed support is available only under a support contract.
- Destination plugins of the enterprise tier run only on a seat whose plan includes the
  enterprise plugins.
