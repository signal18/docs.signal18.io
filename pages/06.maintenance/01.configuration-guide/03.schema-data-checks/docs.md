# Schema and Data Monitoring

Replication Manager provides two complementary monitoring systems for database integrity: **schema monitoring** tracks structural changes across your cluster, and **data checksumming** detects and repairs row-level divergence between the primary and its replicas.

---

## Schema Monitoring

### How Collection Works

Schema monitoring operates in two phases. First, Replication Manager builds a list of all `schema.table` pairs. Then, in a background thread, it collects full metadata for each table — column definitions, index structures, collations, and data types — computing a CRC64 fingerprint over the result.

Several mechanisms prevent concurrent collections from running and enforce a minimum interval between runs, controlled by:

| Parameter | Default | Description |
|---|---|---|
| `monitoring-schema-scan-timeout` | `30` | Maximum seconds for a single schema metadata scan |

### Enabling the Scheduler

All maintenance schedulers are **disabled by default** and must be opted in explicitly.

| Parameter | Default | Description |
|---|---|---|
| `monitoring-scheduler` | `false` | Master switch — enables the internal scheduler engine |
| `monitoring-schema-scheduler` | `true` | Enables the schema monitoring schedule |
| `monitoring-schema-scheduler-cron` | `0 0 1 1 * *` | Cron schedule for schema collection (6-field format) |

The default cron runs once a day at 1 AM. This conservative default is intentional — clusters with large table dictionaries can have expensive metadata scans, and once-a-day collection is sufficient for most environments.

### Collection Scope

Fine-grained control over what gets collected:

| Parameter | Default | Description |
|---|---|---|
| `monitoring-schema-columns` | `true` | Collect column definitions |
| `monitoring-schema-indexes` | `true` | Collect index definitions |
| `monitoring-schema-on-replicas` | `true` | Mirror schema collection on all replicas |
| `monitoring-schema-ignore-tables` | `""` | Comma-separated `schema.table` patterns to exclude |

### Change Detection

Once metadata has been collected on the primary and all replicas, Replication Manager continuously compares CRC64 fingerprints across all cluster nodes to detect structural drift — schema divergence between replicas and their primary, missing or added tables, column type changes, collation changes.

| Parameter | Default | Description |
|---|---|---|
| `monitoring-schema-change` | `true` | Enable schema change detection and alerting |
| `monitoring-schema-change-script` | `""` | Optional external script invoked on schema change event |

### Special Case: Shard Proxy

When a MariaDB Spider shard proxy is in use (`shardproxy = true`), schema changes must be detected and pushed to the Spider proxy node much faster than the default daily schedule allows. In this configuration, schema monitoring runs continuously in the main cluster loop, throttled only by `monitoring-schema-scan-timeout`. If you use sharding, verify this parameter is tuned appropriately for your table count.

### Persisting the Table Dictionary

To avoid expensive cold-start scans after a process restart, the full per-server table dictionary — including schema metadata and checksum state — is persisted to disk after every collection. It is automatically reloaded on startup before the first monitoring cycle runs.

The file is stored per server inside the working directory:

```
{working-dir}/{cluster-name}/{host}_{port}/serverstate.json
```

For example:
```
/var/lib/replication-manager/belair/db1.belair.svc.cloud18_3306/serverstate.json
```

---

## Data Checksumming

Schema monitoring tells you *structure* is consistent. Data checksumming tells you *rows* are consistent.

### Triggering a Checksum

A checksum run can be initiated in three ways:

- **Scheduler** — runs automatically on the configured cron schedule
- **GUI** — from the Shards tab, at the cluster, schema, or individual table level
- **API** — `POST /api/clusters/{cluster}/settings/actions/switch/monitoring-checksum-scheduler`

| Parameter | Default | Description |
|---|---|---|
| `monitoring-checksum-scheduler` | `false` | Enable scheduled automatic checksumming |
| `monitoring-checksum-scheduler-cron` | `0 0 2 * * 5` | Cron schedule — default is every Friday at 2 AM |

### How Checksumming Works

Tables are processed **serially**. For each table, the algorithm:

1. **Splits the table into chunks** of 2,000 rows using range predicates on the primary key, stored in a working table `replication_manager_schema.table_chunk`.
2. **Computes a CRC32 checksum** for each chunk on the primary using `REPEATABLE READ` isolation and `binlog_format = STATEMENT` to ensure the checksum write itself replicates to all replicas identically.
3. **Waits for replica convergence** using GTID sequence numbers — each replica must have applied the primary's transactions up to the point the chunk was written before its result is read.
4. **Compares chunk checksums** between primary and each replica. Any divergent chunk is recorded with its full range predicate so it can be repaired later.

Tables without a `PRIMARY KEY` or `UNIQUE KEY` cannot be chunked and are marked **N/A**.

### Table Sync States

Each table carries one of five sync states visible in the GUI:

| State | Meaning |
|---|---|
| _(blank)_ | Not yet checksummed |
| `WA` | Waiting — queued for a checksum run that is already in progress |
| `PR` | In Progress — currently being checksummed, shows a progress bar |
| `OK` | All chunks match across all replicas |
| `ER` | One or more chunks diverge on at least one replica |
| `NA` | Cannot checksum — no primary/unique key, or a process error occurred |

### Excluding Expected Divergence

Some tables diverge by design. Replication Manager uses the `replication_manager_schema.jobs` table to schedule maintenance tasks on individual nodes, intentionally writing those rows without binary logging so they stay node-local. The `replication_manager_schema.table_checksum` working table is also ephemeral. Both are excluded by default:

| Parameter | Default |
|---|---|
| `monitoring-checksum-ignore-tables` | `replication_manager_schema.jobs,replication_manager_schema.table_checksum` |

Add any additional `schema.table` pairs to this comma-separated list to suppress known-good divergences.

### Supported Primary Key Types

The chunking engine supports the full range of primary key data types and compositions. The regression test suite at `share/sql/checksum-repair-test.sql` covers:

**Single-column keys:** `TINYINT`, `SMALLINT`, `INT`, `BIGINT`, `UNSIGNED BIGINT`, `VARCHAR`, `CHAR`, `UUID CHAR(36)`, `UUID BINARY(16)`, `DATE`, `DATETIME`, `TIMESTAMP`, `DECIMAL`, `ENUM`

**Composite keys:** `INT + VARCHAR`, `BIGINT + DATE`, `UUID CHAR(36) + TINYINT`, `ENUM + DATE`, `ENUM + DATETIME(6)`, `ENUM + TIMESTAMP(3)`, `ENUM + YEAR`

---

## Repairing Divergent Tables

When a table is in `ER` state, Replication Manager stores the exact range predicate for every divergent chunk on every affected replica. Repair can be triggered from the GUI or API at the table, schema, or cluster level.

The repair process per chunk:

```sql
START TRANSACTION;
CREATE OR REPLACE TEMPORARY TABLE tmp_repair
    AS SELECT * FROM schema.table WHERE {chunk_range} FOR UPDATE;
DELETE FROM schema.table WHERE {chunk_range};
INSERT INTO schema.table SELECT * FROM tmp_repair;
COMMIT;
```

This transaction runs on the **primary** with `binlog_format = ROW`, so replicas receive an exact row-level copy of the repaired chunk via standard replication — no direct writes to replicas are required.

After all chunks are repaired, the table is automatically re-checksummed to confirm convergence.

---

## GUI: Shards and Schema Graph

The **Shards tab** provides a table-level view of the entire schema dictionary with:

- **Sync status badges** for each table (`OK`, `ER`, `NA`, `PR` with live progress bar)
- **In-progress tables sorted to the top** automatically during a checksum run
- **Filter pills** to quickly isolate tables by sync state
- **Per-table and per-schema actions** — Checksum, Repair, Analyze

The **Graph view** within the Shards tab visualises relationships between tables using three edge types:

| Edge type | Style | Description |
|---|---|---|
| Foreign key | Solid | Declared `FOREIGN KEY` constraints |
| Name match | Dashed | Columns sharing the same name across tables (implicit join candidates) |
| Workload query | Dotted | Tables co-appearing in captured query plans |

Name-match edges are **off by default** as they can produce a large number of implicit edges in schemas with common column naming conventions such as `id`, `created_at`, or `status`. Toggle each edge type independently using the filter controls in the graph toolbar.

---

## Failover Policy and Data Divergence

### How Divergence Affects Replica Election

When a checksum run completes, each replica that has one or more divergent chunks is marked with `isDataDiverge = true` in the cluster state. This flag is evaluated every time Replication Manager selects a candidate for failover or switchover.

During candidate election, any replica flagged as divergent is excluded and logged with:

```
ERR00103: Skip slave in election {server} data diverge in checksum
```

This exclusion is tracked in the election audit trail under `IgnoredDataDivergence` so operators can see exactly why a replica was bypassed.

If divergence has been detected on **all** replicas, no candidate can be found and the operation is blocked with:

```
ERR00032: No candidates found in slaves list
```

This escalation from a per-server warning to a cluster-level error is intentional: a single divergent replica is a warning that should be investigated and repaired, but a fully divergent replica set is a signal that automatic promotion would risk serving stale or inconsistent data.

### Controlling Failover Behaviour

Replication Manager's core design principle is **availability first**. Blocking all failovers because of data divergence would be the wrong default for most production environments where a brief inconsistency is preferable to an extended outage.

| Parameter | Default | Description |
|---|---|---|
| `failover-divergent-data` | `true` | Allow failover and switchover to replicas with divergent checksum data |

With the default of `true`, divergent replicas participate in candidate election normally. The `ERR00103` warning is still raised and recorded — giving operators full visibility — but it does not block promotion.

Setting this to `false` enforces a **strict consistency policy**: no replica with a known data divergence will ever be promoted. This is appropriate for environments where data correctness takes absolute precedence over availability, such as financial ledgers, compliance-critical datasets, or systems where serving stale data has regulatory consequences.

### Recommended Workflow for Strict Mode

If you run with `failover-divergent-data = false`, make sure your operational process accounts for the fact that an unrepaired divergence will block all automated failover. The recommended cycle is:

1. Run checksums on schedule (`monitoring-checksum-scheduler = true`)
2. Monitor for `ERR00103` alerts — each one identifies a specific replica and set of divergent chunks
3. Trigger repair from the GUI or API to re-sync the affected chunks via the primary
4. After repair the table is automatically re-checksummed; on success `isDataDiverge` is cleared
5. The replica re-enters the candidate pool for the next failover or switchover

### GUI Setting

The `failover-divergent-data` toggle is available in **Settings → Replication → Failover** under the label *"Failover enable on divergent data"*, allowing it to be changed at runtime without a configuration file edit or restart.
