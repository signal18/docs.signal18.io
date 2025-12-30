---
title: "Processlist & Transaction Monitoring"
taxonomy:
    category: docs
---

**replication-manager** can monitor long-running queries and sleeping transactions using the processlist. This feature captures the longest queries or transactions across all monitored database servers and makes them available for analysis.

## Basic Processlist Monitoring

##### `monitoring-processlist` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Enable capture of longest queries or transactions via processlist |
| Type | boolean |
| Default Value | true |

When enabled, **replication-manager** captures processlist information during each monitoring loop. The number of processes captured is controlled by `monitoring-processlist-limit`.

##### `monitoring-processlist-limit` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Maximum number of processes to capture per server |
| Type | string |
| Default Value | "50" |

Limiting the processlist size reduces monitoring bandwidth and overhead while still capturing the most significant queries and transactions.

##### `monitoring-processlist-information-schema` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Use INFORMATION_SCHEMA.PROCESSLIST instead of SHOW FULL PROCESSLIST |
| Type | boolean |
| Default Value | true |

Using INFORMATION_SCHEMA allows **replication-manager** to join with INNODB_TRX tables to retrieve transaction information. This is required for transaction monitoring features.

## Transaction Monitoring

##### `monitoring-processlist-transactions` (3.1)

| Item | Value |
| ---- | ----- |
| Description | Report transactions in process, including sleeping transactions |
| Type | boolean |
| Default Value | false |

When enabled, **replication-manager** monitors both active queries and long-running transactions. Sleeping transactions are particularly important to identify as they can hold locks and block other operations.

The processlist will be sorted by transaction duration rather than query duration, making it easy to identify transactions that have been open for a long time.

**Key features:**
- Captures sleeping transactions that are holding InnoDB locks
- Shows transaction isolation level, tables locked, rows modified
- Displays transaction start time and duration
- Identifies read-only vs read-write transactions

**Information captured for each transaction:**
- `trx_time`: Transaction duration in seconds
- `trx_isolation_level`: Transaction isolation level (READ-COMMITTED, REPEATABLE-READ, etc.)
- `trx_tables_in_use`: Number of tables currently in use
- `trx_tables_locked`: Number of tables locked
- `trx_lock_structs`: Number of lock structures
- `trx_lock_memory_bytes`: Memory used for locks
- `trx_rows_locked`: Number of rows locked
- `trx_rows_modified`: Number of rows modified
- `trx_is_read_only`: Whether transaction is read-only (1) or read-write (0)

##### `monitoring-processlist-inactive` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Show inactive connections (sleeping queries) in processlist |
| Type | boolean |
| Default Value | false |

When enabled, all connections are shown including those with Command='Sleep'. When combined with `monitoring-processlist-transactions`, this shows sleeping transactions that may be holding locks.

## Query Monitoring

For monitoring slow queries specifically, see the long query monitoring parameters:

- `monitoring-queries`: Enable long query monitoring
- `monitoring-long-query-time`: Threshold in milliseconds
- `monitoring-long-query-with-process`: Use processlist to capture slow queries
- `monitoring-long-query-with-table`: Use slow_log table to capture slow queries

## Usage Example

To monitor sleeping transactions that may be causing blocking:

```toml
monitoring-processlist = true
monitoring-processlist-transactions = true
monitoring-processlist-information-schema = true
monitoring-processlist-limit = "50"
```

This configuration will:
- Capture the 50 longest-running transactions per server
- Include sleeping transactions holding locks
- Join with INNODB_TRX to get detailed transaction metrics
- Sort by transaction duration to highlight long-running transactions

## API Access

Transaction processlist data is available via the REST API at:

```
GET /api/clusters/{cluster}/servers/{server}/processlist
```

The web UI displays this information in the server detail view under "Processlist" tab.

## Performance Considerations

Querying INFORMATION_SCHEMA.PROCESSLIST and INNODB_TRX tables has minimal overhead but can be noticeable on servers with thousands of connections. Adjust `monitoring-processlist-limit` to control the impact.

On MariaDB, the query uses TIME_MS for millisecond precision. On MySQL/Percona 8.0+, TIME_MS is also available. On older versions, TIME (seconds) is used.
