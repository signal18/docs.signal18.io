---
title: Schema Plugins
taxonomy:
    category: docs
---

## 11.7.1 Schema Plugins

> **Tier:** Community — requires a free registered instance at gitlab.signal18.io
> **Available since:** replication-manager **v3.1.33** (SCH0001, SCH0002), **v3.1.42** (SCH0003, SCH0004)

Schema plugins read the schema dictionary snapshot that the schema monitor already collects (engine, row format, columns, indexes, table statistics) and flag data-model risks before they turn into incidents. They run no query of their own: every fact comes from the snapshot, refreshed with the schema monitor (daily by default, plus boot and on-demand runs) and evaluated on the primary only.

Findings carry `SCH` error keys and a `SCHEMA` severity. They are routed to the **Schema Logs** view of the dashboard and to `schema.log` on disk, never to the main HA log, and they never influence failover. Each plugin emits **one** finding per run that lists every table concerned, so the finding stays open while any table is still affected and resolves when the last one is fixed.

All schema plugins accept `mask-identifiers`: when set, schema, table, column and index names are partially obscured in the finding (`window` → `wi???ow`) and the plugin drops its suggested SQL, so the log entry gives no schema map to someone who only has log access.

| Plugin | Finding | Since | Needs |
|---|---|---|---|
| `plugin-schema-row-size` | SCH0001 | 3.1.33 | `monitoring-schema-columns` |
| `plugin-schema-lob-compression` | SCH0002 | 3.1.33 | `monitoring-schema-columns`, MariaDB |
| `plugin-schema-duplicate-index` | SCH0003 | 3.1.42 | `monitoring-schema-indexes` |
| `plugin-schema-auto-increment-exhaustion` | SCH0004 | 3.1.42 | `monitoring-schema-columns` |

When the monitoring switch a plugin needs is off, the cluster shows an INFO state saying so instead of silently reporting nothing. Both switches are on by default.

---

## 11.7.2 plugin-schema-row-size

**Finding:** `SCH0001`  
**Since:** 3.1.33

Flags InnoDB tables whose short VARCHAR columns, the ones InnoDB always stores inline (declared byte width under 256 bytes, that is the declared length times the charset bytes per character), already sum past the InnoDB inline row budget: about 8126 bytes for the default 16 KB page, with a discount for `ROW_FORMAT=COMPRESSED`. Past that budget an insert fails with "Row size too large" or forces columns off-page, which slows every scan.

| Key | Default | Description |
|---|---|---|
| `inline-varchar-max-bytes` | `256` | VARCHAR columns narrower than this byte width count as always inline |
| `mask-identifiers` | `false` | Obscure names in the finding |

**What to do:** narrow the columns, move the cold ones to a companion table, or switch the table to `ROW_FORMAT=DYNAMIC`.

---

## 11.7.3 plugin-schema-lob-compression

**Finding:** `SCH0002`  
**Since:** 3.1.33 — MariaDB only

Flags `BLOB` and `TEXT` columns whose observed average length, from a bounded sample of 1024 rows, exceeds a threshold and that are not declared `COMPRESSED`. MariaDB per-column compression keeps such data smaller in the buffer pool and on disk with no application change.

| Key | Default | Description |
|---|---|---|
| `avg-length-threshold-bytes` | `8192` | Average observed length above which compression is suggested |
| `mask-identifiers` | `false` | Obscure names and drop the ALTER statement |

**Remediation:** `ALTER TABLE … MODIFY col TEXT COMPRESSED`, proposed per column.

---

## 11.7.4 plugin-schema-duplicate-index

**Finding:** `SCH0003`  
**Since:** 3.1.42

Flags redundant indexes: an index whose column list, in order and with the same prefix lengths, is a leftmost prefix of another index of the same table, or equal to it. Such an index serves no lookup the wider one cannot, while every write maintains it and it occupies buffer pool and disk.

The rule respects what is not redundant:

- A `UNIQUE` index is never reported as covered by a non-unique one: the constraint is the point.
- `PRIMARY` is never the redundant side. A secondary index on the primary key columns is.
- `FULLTEXT` and `SPATIAL` indexes only compare with indexes of the same kind.
- InnoDB appends the primary key to every secondary index, so a non-unique index that explicitly ends with the primary key columns is compared without them: `(a, id)` is redundant with `(a)` when `id` is the primary key.
- Two identical indexes: `PRIMARY` wins, then `UNIQUE`, then the one with the greater name is reported.

The finding lists each redundant index with the index that covers it and an estimate of its size, the table's `INDEX_LENGTH` shared evenly among its secondary indexes.

| Key | Default | Description |
|---|---|---|
| `mask-identifiers` | `false` | Obscure names and drop the DROP INDEX statements |

**Remediation:** one `ALTER TABLE … DROP INDEX` per index, risk moderate. Check first that no query names the index through `USE INDEX`, `FORCE INDEX` or an optimizer hint; the drop itself is online on InnoDB.

---

## 11.7.5 plugin-schema-auto-increment-exhaustion

**Finding:** `SCH0004`  
**Since:** 3.1.42

Flags tables whose `AUTO_INCREMENT` counter has reached a share of the capacity of the integer column that holds it. When the counter reaches the maximum, every insert fails with "Duplicate entry … for key 'PRIMARY'", and it happens without warning. The value read is the next value the counter will hand out, from `information_schema.TABLES`; the schema monitor reads it with fresh statistics on MySQL and Percona as well as MariaDB.

| Type | Signed maximum | Unsigned maximum |
|---|---|---|
| `TINYINT` | 127 | 255 |
| `SMALLINT` | 32 767 | 65 535 |
| `MEDIUMINT` | 8 388 607 | 16 777 215 |
| `INT` | 2 147 483 647 | 4 294 967 295 |
| `BIGINT` | 9 223 372 036 854 775 807 | 18 446 744 073 709 551 615 |

The finding lists the tables past the threshold, highest ratio first, with the remaining headroom.

| Key | Default | Description |
|---|---|---|
| `capacity-threshold-pct` | `95` | Report at or past this share of the maximum (50 to 100) |
| `mask-identifiers` | `false` | Obscure names and drop the ALTER statements |

**Remediation:** for a signed column, `MODIFY … UNSIGNED` first, same storage and twice the range; then `MODIFY … BIGINT UNSIGNED`. Both rebuild the table, the finding shows the size to expect, and every foreign key or application column that references the id must be widened too.

---

## 11.7.6 SCH Code Reference

| Code | Plugin | Since | What it flags |
|---|---|---|---|
| SCH0001 | `plugin-schema-row-size` | 3.1.33 | InnoDB row past the inline budget |
| SCH0002 | `plugin-schema-lob-compression` | 3.1.33 | Large uncompressed BLOB/TEXT (MariaDB) |
| SCH0003 | `plugin-schema-duplicate-index` | 3.1.42 | Redundant / duplicate index |
| SCH0004 | `plugin-schema-auto-increment-exhaustion` | 3.1.42 | AUTO_INCREMENT near the column capacity |
