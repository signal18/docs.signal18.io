---
title: "Performance Schema Monitoring"
taxonomy:
    category: docs
---

**replication-manager** can collect advanced metrics from MySQL/MariaDB Performance Schema, including mutex waits, latch (rwlock) waits, and memory usage. These metrics are collected during each monitoring loop and exported to Graphite for trending analysis.

## Basic Performance Schema Monitoring

##### `monitoring-performance-schema` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Enable Performance Schema monitoring |
| Type | boolean |
| Default Value | true |

When enabled, **replication-manager** queries Performance Schema tables to collect query statistics and performance metrics.

##### `monitoring-performance-schema-instruments` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Monitor Performance Schema instruments configuration |
| Type | boolean |
| Default Value | true |

Monitors the enabled/disabled state of Performance Schema instruments from `setup_instruments` table.

## Mutex Wait Monitoring

##### `monitoring-performance-schema-mutex` (3.1)

| Item | Value |
| ---- | ----- |
| Description | Monitor InnoDB mutex wait events |
| Type | boolean |
| Default Value | true |

When enabled, **replication-manager** collects mutex wait statistics from Performance Schema and includes them in global status metrics sent to Graphite.

**Data collected:**
- Queries `performance_schema.events_waits_summary_global_by_event_name`
- Filters for `EVENT_NAME LIKE 'wait/synch/mutex/innodb%'`
- Collects `COUNT_STAR` for each mutex event
- Metric names formatted as `WAIT_SYNCH_MUTEX_INNODB_*`

**Use cases:**
- Identify InnoDB mutex contention
- Diagnose performance bottlenecks caused by mutex waits
- Track mutex wait trends over time
- Correlate mutex waits with application workload patterns

**Requirements:**
- Performance Schema must be enabled (`performance_schema = ON`)
- Requires MariaDB 10.5+ or MySQL 5.7+

## Latch (RWLock) Wait Monitoring

##### `monitoring-performance-schema-latch` (3.1)

| Item | Value |
| ---- | ----- |
| Description | Monitor InnoDB latch (rwlock) wait events |
| Type | boolean |
| Default Value | true |

When enabled, **replication-manager** collects read-write lock (rwlock) wait statistics from Performance Schema and includes them in global status metrics sent to Graphite.

**Data collected:**
- Queries `performance_schema.events_waits_summary_global_by_event_name`
- Filters for `EVENT_NAME LIKE 'wait/synch/rwlock/innodb%'`
- Collects `COUNT_STAR` for each rwlock event
- Metric names formatted as `WAIT_SYNCH_RWLOCK_INNODB_*`

**Use cases:**
- Identify InnoDB rwlock contention
- Diagnose performance issues with concurrent reads/writes
- Track latch wait trends over time
- Analyze impact of read-heavy vs write-heavy workloads

**Requirements:**
- Performance Schema must be enabled (`performance_schema = ON`)
- Requires MariaDB 10.5+ or MySQL 5.7+

## Performance Schema Memory Monitoring

##### `monitoring-performance-schema-memory` (3.1)

| Item | Value |
| ---- | ----- |
| Description | Monitor Performance Schema memory usage |
| Type | boolean |
| Default Value | true |

When enabled, **replication-manager** collects memory usage statistics from Performance Schema memory instrumentation and includes them in global status metrics sent to Graphite.

**Data collected:**
- Queries `performance_schema.memory_summary_global_by_event_name`
- Collects memory allocation statistics by event type
- Tracks `CURRENT_COUNT_USED`, `CURRENT_NUMBER_OF_BYTES_USED`, `HIGH_COUNT_USED`, `HIGH_NUMBER_OF_BYTES_USED`

**Use cases:**
- Monitor memory consumption by InnoDB subsystems
- Identify memory leaks or excessive allocations
- Track memory usage trends over time
- Diagnose out-of-memory conditions

**Requirements:**
- Performance Schema must be enabled (`performance_schema = ON`)
- Memory instrumentation must be enabled in Performance Schema consumers
- Requires MariaDB 10.5+ or MySQL 5.7+

## Configuration Example

To enable all Performance Schema advanced monitoring features:

```toml
monitoring-performance-schema = true
monitoring-performance-schema-instruments = true
monitoring-performance-schema-mutex = true
monitoring-performance-schema-latch = true
monitoring-performance-schema-memory = true
```

To disable mutex/latch monitoring but keep memory monitoring:

```toml
monitoring-performance-schema = true
monitoring-performance-schema-mutex = false
monitoring-performance-schema-latch = false
monitoring-performance-schema-memory = true
```

## Metrics Export

When these features are enabled, **replication-manager** adds the collected metrics to the global status query result. Metrics are then:

1. Stored in the embedded Graphite database (if enabled)
2. Exported to external Graphite server (if configured)
3. Available via Prometheus metrics endpoint
4. Visible in Grafana dashboards

**Metric naming convention:**
- Mutex waits: `mysql.{server_id}.mysql_global_status_wait_synch_mutex_innodb_{event}`
- Latch waits: `mysql.{server_id}.mysql_global_status_wait_synch_rwlock_innodb_{event}`
- Memory: `mysql.{server_id}.mysql_global_status_memory_{event}`

## Performance Considerations

**Impact:**
- Mutex/latch monitoring adds one UNION clause to the global status query per feature
- Performance Schema queries are fast (indexed) but can add milliseconds to monitoring loop
- Memory monitoring queries are more expensive than mutex/latch queries

**Recommendations:**
- Enable these features in production to identify contention issues
- Monitor the monitoring loop duration to ensure it stays within acceptable limits
- Disable specific features if monitoring overhead becomes a concern
- Use Graphite/Grafana to visualize trends rather than real-time analysis

## Database Configuration

Ensure Performance Schema is properly configured in your database server:

```ini
# Enable Performance Schema
performance_schema = ON

# Enable wait event instruments (for mutex/latch monitoring)
performance-schema-instrument = 'wait/synch/%=ON'

# Enable memory instruments (for memory monitoring)
performance-schema-instrument = 'memory/%=ON'

# Enable consumers
performance-schema-consumer-events-waits-current = ON
performance-schema-consumer-events-waits-history = ON
performance-schema-consumer-events-waits-history-long = ON
```

## Troubleshooting

**Metrics not appearing:**
- Verify Performance Schema is enabled: `SHOW VARIABLES LIKE 'performance_schema'`
- Check instruments are enabled: `SELECT * FROM performance_schema.setup_instruments WHERE NAME LIKE 'wait/synch/%'`
- Ensure `graphite-metrics = true` or Prometheus endpoint is configured
- Check **replication-manager** logs for SQL errors

**High monitoring overhead:**
- Reduce `monitoring-wait-retry` interval
- Disable mutex/latch monitoring if not needed
- Consider enabling only on specific clusters, not all

**No mutex/latch events:**
- If `COUNT_STAR = 0` for all events, there is genuinely no wait contention
- This is expected on idle or low-traffic databases
- Metrics only appear when there is actual mutex/latch activity
