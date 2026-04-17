---
title: Archiving
taxonomy:
    category: docs
---
### Archiving Backups

##### `backup-restic` (2.1)                                            

| Item | Value |
| ---- | ----- |
| Description | Turn on Backup store and restore with restic |
| Type | boolean |
| Default Value | false |

##### `backup-restic-repository` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  Restic AWS or Minio backend repository |
| Type | string |
| Default Value | "s3:https://s3.signal18.io/backups" |


##### `backup-restic-aws-access-key-id` (2.1)    

| Item | Value |
| ---- | ----- |
| Description | AWS or Minio key id |
| Type | string |
| Default Value | "admin" |

##### `backup-restic-aws-access-secret` (2.1)    

| Item | Value |
| ---- | ----- |
| Description | AWS or Minio key secret |
| Type | string |
| Default Value | "secret" |


##### `backup-restic-binary-path` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  Path to restic binary  |
| Type | string |
| Default Value | "/usr/bin/restic" |

##### `backup-restic-password` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  Restic backend password  |
| Type | string |
| Default Value | "secret" |

##### `backup-keep-hourly` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  Keep this number of hourly backup  |
| Type | integer |
| Default Value | 1 |

##### `backup-keep-daily` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  Keep this number of daily backup |
| Type | integer |
| Default Value | 1 |

##### `backup-keep-weekly` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  Keep this number of weekly backup |
| Type | integer |
| Default Value | 4 |

##### `backup-keep-monthly` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  Keep this number of monthly backup |
| Type | integer |
| Default Value | 12 |

##### `backup-keep-yearly` (2.1)

| Item | Value |
| ---- | ----- |
| Description |  Keep this number of yearly backups |
| Type | integer |
| Default Value | 2 |

##### `backup-restic-local-repository` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Restic local repository path. When empty, uses per-cluster repository in datadir/backups/archive/{clustername} |
| Type | string |
| Default Value | "" |

Set this to specify a custom local repository path. Useful for multi-cluster environments where you want centralized backup storage.

##### `backup-restic-aws` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Enable S3 archiving. When false, backups are stored in local repository |
| Type | boolean |
| Default Value | false |

When enabled, restic archives to S3/Minio using the configured `backup-restic-repository`. When disabled, backups are stored locally in `datadir/backups/archive/`.

##### `backup-restic-timeout` (3.1)

| Item | Value |
| ---- | ----- |
| Description | Timeout for restic operations in seconds |
| Type | integer |
| Default Value | 3600 |

Controls how long **replication-manager** will wait for restic backup, restore, and maintenance operations to complete before timing out.

## Disk Space Management

##### `backup-restic-purge-oldest-on-disk-space` (3.1)

| Item | Value |
| ---- | ----- |
| Description | Automatically purge oldest backups when disk space is critically low |
| Type | boolean |
| Default Value | true |

When enabled, **replication-manager** monitors disk space usage and automatically purges the oldest backup snapshots when disk usage exceeds the critical threshold. This prevents backups from filling the disk and causing system-wide issues.

##### `backup-restic-purge-oldest-on-disk-threshold` (3.1)

| Item | Value |
| ---- | ----- |
| Description | Disk usage percentage threshold for automatic purge. 0 uses backup-disk-threshold-crit value |
| Type | integer |
| Default Value | 0 |

When set to 0, the threshold from `backup-disk-threshold-crit` is used. Otherwise, this specific value overrides the general threshold for restic purge operations.

##### `backup-check-size` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Check free space before processing backup |
| Type | boolean |
| Default Value | true |

When enabled, **replication-manager** verifies sufficient disk space is available before starting a backup operation.

##### `backup-disk-threshold-warn` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Warning threshold for disk usage in percentage |
| Type | integer |
| Default Value | 85 |

When disk usage exceeds this percentage, **replication-manager** issues warnings but continues backup operations.

##### `backup-disk-threshold-crit` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Critical threshold for disk usage in percentage. Backups are skipped above this value |
| Type | integer |
| Default Value | 95 |

When disk usage exceeds this percentage, **replication-manager** will:
- Skip new backup operations to prevent disk exhaustion
- Trigger automatic purge if `backup-restic-purge-oldest-on-disk-space` is enabled
- Generate critical alerts

## Configuration Examples

### Local Repository Configuration

```toml
backup-restic = true
backup-restic-binary-path = "/usr/bin/restic"
backup-restic-local-repository = ""
backup-restic-aws = false
backup-restic-password = "changeme"
backup-restic-purge-oldest-on-disk-space = true
backup-disk-threshold-crit = 90
```

This configuration stores backups locally in per-cluster directories and automatically purges old backups when disk usage exceeds 90%.

### S3/Minio Configuration

```toml
backup-restic = true
backup-restic-aws = true
backup-restic-repository = "s3:https://s3.amazonaws.com/my-backups"
backup-restic-aws-access-key-id = "AKIAIOSFODNN7EXAMPLE"
backup-restic-aws-access-secret = "wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY"
backup-restic-password = "changeme"
backup-restic-timeout = 7200
```

This configuration archives backups to S3 with a 2-hour timeout for operations.

### Retention Policy

```toml
backup-keep-hourly = 24
backup-keep-daily = 7
backup-keep-weekly = 4
backup-keep-monthly = 12
backup-keep-yearly = 3
```

This retention policy keeps:
- Last 24 hourly backups
- Last 7 daily backups
- Last 4 weekly backups
- Last 12 monthly backups
- Last 3 yearly backups

## Restic Task Management

**replication-manager 3.1** includes task queue management for restic operations. Long-running backup operations are tracked and can be:

- Paused via API: `PUT /api/clusters/{cluster}/servers/{server}/actions/task-pause`
- Resumed via API: `PUT /api/clusters/{cluster}/servers/{server}/actions/task-resume`
- Cancelled via API: `PUT /api/clusters/{cluster}/servers/{server}/actions/task-cancel`

Task status is visible in the web UI under server details.

## Automatic Cleanup Workflow

When `backup-restic-purge-oldest-on-disk-space` is enabled:

1. **replication-manager** monitors disk usage before each backup
2. If usage exceeds `backup-disk-threshold-crit`:
   - New backups are skipped
   - Oldest snapshot is identified via `restic snapshots`
   - Snapshot is removed via `restic forget`
   - Repository is pruned via `restic prune`
   - Disk space is rechecked
3. Process repeats until disk usage drops below critical threshold
4. Normal backup operations resume

## Performance Considerations

- Restic operations are I/O intensive, especially `prune` and `check`
- Set `backup-restic-timeout` appropriately for repository size
- Network timeouts may occur with S3 for large repositories
- Purge operations can take significant time on full disks
- Monitor restic task status via API to track progress
