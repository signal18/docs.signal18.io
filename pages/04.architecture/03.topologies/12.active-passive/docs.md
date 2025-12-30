---
title: "Active-Passive Topology"
taxonomy:
    category: docs
---

Active-Passive topology is a special mode where **replication-manager** monitors a single active database server without managing replication or performing automatic failover. This topology is useful for scenarios requiring external orchestration or when automatic failover is not desired.

## Configuration

##### `replication-active-passive` (3.1)

| Item | Value |
| ---- | ----- |
| Description | Enable Active-Passive topology |
| Type | boolean |
| Default Value | false |

## Topology Characteristics

### Behavior

In Active-Passive mode:

- **Single active server**: Only one server is designated as "master"
- **No replication management**: **replication-manager** does not configure or manage replication
- **No automatic failover**: Failover and switchover operations are disabled
- **No rejoin operations**: Server rejoin is skipped
- **No privilege checks**: REPLICATION and SUPER privilege checks are bypassed
- **Simplified monitoring**: Focuses on server availability and health metrics

### Automatic Detection

Active-Passive topology is automatically enabled when:
- Configuration explicitly sets `replication-active-passive = true`
- Only one server is defined in the `db-servers-hosts` list

## Use Cases

**1. External Orchestration**

Use Active-Passive mode when failover is managed by external systems:
- Kubernetes operators
- Cloud provider managed databases (RDS, Cloud SQL, Azure Database)
- External clustering solutions (Pacemaker, Corosync)
- Manual failover procedures

**2. Development/Testing**

Monitor a standalone database without replication complexity:
- Local development environments
- Integration testing
- Database migration testing

**3. Single-Server Production**

Monitor production databases that don't require high availability:
- Low-traffic applications
- Non-critical workloads
- Budget-constrained deployments

**4. Monitoring-Only Deployments**

Collect metrics and health information without infrastructure changes:
- Performance monitoring
- Backup scheduling
- Schema change detection
- Query analysis

## Features Available in Active-Passive Mode

Active-Passive mode supports:

- **Monitoring**: Server health, status variables, processlist
- **Metrics collection**: Graphite, Prometheus endpoints
- **Backup management**: Logical and physical backups via restic
- **Schema monitoring**: Track schema changes and differences
- **Query monitoring**: Long queries, Performance Schema analysis
- **API access**: Full REST API for server information
- **Web UI**: Dashboard with server metrics and status

## Features Disabled in Active-Passive Mode

Active-Passive mode disables:

- **Failover**: Manual and automatic failover operations blocked
- **Switchover**: Cannot perform switchover operations
- **Rejoin**: Automatic server rejoin disabled
- **Replication setup**: No CHANGE MASTER commands issued
- **Read-only enforcement**: Does not set `read_only` flag
- **Privilege checks**: Skips replication user privilege validation
- **Maintenance mode**: Maintenance flag not applied to master

## Configuration Example

### Minimal Configuration

```toml
[default]
db-servers-hosts = "192.168.1.100:3306"
db-servers-credential = "root:password"
replication-active-passive = true
monitoring-datadir = "/var/lib/replication-manager"
```

### Full Monitoring Configuration

```toml
[myapp]
db-servers-hosts = "db.example.com:3306"
db-servers-credential = "monitor:password"
replication-credential = "replication:password"
replication-active-passive = true

# Monitoring
monitoring-datadir = "/var/lib/replication-manager/myapp"
monitoring-write-heartbeat = false
graphite-metrics = true
graphite-embedded = true

# Backups
backup-restic = true
backup-restic-repository = "s3:https://s3.amazonaws.com/my-backups"
backup-keep-daily = 7
backup-keep-weekly = 4

# Schema monitoring
monitoring-schema-change = true
monitoring-schema-change-script = "/usr/local/bin/schema-alert.sh"

# Query monitoring
monitoring-queries = true
monitoring-long-query-time = 5000
```

### Multi-Server Active-Passive (No Replication)

```toml
[cluster1]
db-servers-hosts = "server1:3306,server2:3306,server3:3306"
db-servers-credential = "root:password"
replication-active-passive = true
```

When multiple servers are configured with Active-Passive mode:
- First reachable server becomes "master"
- Other servers are marked as "Unconnected"
- No replication is expected or configured
- Useful for monitoring multiple independent databases as a single logical cluster

## Transitioning Between Modes

### From Master-Slave to Active-Passive

To disable automatic failover on an existing replicated cluster:

```toml
replication-active-passive = true
```

**replication-manager** will:
- Stop performing failover operations
- Continue monitoring existing replication status
- Maintain current topology unchanged
- Allow manual intervention for topology changes

### From Active-Passive to Master-Slave

To enable automatic failover:

1. Ensure replication is configured on database servers
2. Update configuration:

```toml
replication-active-passive = false
```

3. Restart **replication-manager** or reload configuration
4. **replication-manager** will detect topology and enable failover

## API Behavior

In Active-Passive mode, API endpoints behave differently:

**Blocked endpoints:**
- `POST /api/clusters/{cluster}/actions/switchover` - Returns error
- `POST /api/clusters/{cluster}/actions/failover` - Returns error
- `POST /api/clusters/{cluster}/servers/{server}/actions/rejoin` - Returns error

**Available endpoints:**
- All `GET` endpoints for monitoring
- Backup management endpoints
- Server maintenance endpoints (start, stop, provision)
- Configuration management endpoints

## Comparison with Other Topologies

| Feature | Active-Passive | Master-Slave | Multi-Master |
| ------- | -------------- | ------------ | ------------ |
| Replication | No | Yes | Yes |
| Auto Failover | No | Yes | Yes |
| Read-only enforcement | No | Yes | Conditional |
| Rejoin | No | Yes | Yes |
| Privilege checks | No | Yes | Yes |
| Server count | 1+ | 2+ | 2+ |

## Troubleshooting

**Problem**: Cannot perform switchover in Active-Passive mode

**Solution**: Active-Passive mode disables failover/switchover by design. Either:
- Disable Active-Passive mode: `replication-active-passive = false`
- Manually promote new master outside **replication-manager**

**Problem**: **replication-manager** not detecting replication

**Solution**: Active-Passive mode ignores replication status. If replication monitoring is needed:
- Switch to appropriate topology (master-slave, multi-master, etc.)
- Remove `replication-active-passive = true` from configuration

**Problem**: Multiple servers all showing as "Unconnected"

**Solution**: In Active-Passive with multiple servers, this is expected. Only the first server is marked as master. To change active server:
- Use API: `PUT /api/clusters/{cluster}/servers/{server}/actions/set-master`
- Or configure `db-servers-prefered-master` to specify desired active server

## Best Practices

**Monitoring without disruption:**
- Use Active-Passive mode when **replication-manager** should observe but not modify infrastructure
- Ideal for migration periods or auditing existing setups

**Backup automation:**
- Active-Passive mode fully supports backup scheduling and management
- Use restic integration for point-in-time recovery

**Schema tracking:**
- Enable `monitoring-schema-change` to detect unplanned schema modifications
- Configure `monitoring-schema-change-script` for alerting

**Metrics collection:**
- Active-Passive mode continues collecting all performance metrics
- Use Graphite or Prometheus endpoints for visualization in Grafana

**Security:**
- Use dedicated monitoring user with minimal privileges
- No REPLICATION privilege required in Active-Passive mode
- Recommended grants: `PROCESS`, `REPLICATION CLIENT`, `SELECT`
