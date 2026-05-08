---
title: Backups
taxonomy:
    category: docs
---

## 6.2.2 Backups

replication-manager supports logical and physical backups with optional compression, streaming to S3, and long-term archiving via Restic.

### 6.2.2.0 Feature Comparison

replication-manager orchestrates backup tools and adds features that standalone tools don't provide: scheduling, REST API, replication-aware restore, parallel compression, S3 archiving, and automatic reseed on failure.

| Feature | mariadb-dump / mysqldump | mydumper / myloader | mariadb-backup / xtrabackup | replication-manager |
|---|:---:|:---:|:---:|:---:|
| **Backup** | | | | |
| Non-blocking consistent backup | &#10004; | &#10004; | &#10004; | &#10004; |
| No copy of disk corruption | &#10004; | &#10004; | &#10008; | &#10004; |
| Multi-threaded backup | &#10008; | &#10004; | &#10008; | &#10004; |
| Multi-threaded compression | &#10008; | &#10008; | &#10008; | &#10004; |
| Partial backup (per-table) | &#10004; | &#10004; | &#10004; | &#10004; |
| Backup scheduler | &#10008; | &#10008; | &#10008; | &#10004; |
| REST API | &#10008; | &#10008; | &#10008; | &#10004; |
| Custom backup/restore scripts | &#10008; | &#10008; | &#10008; | &#10004; |
| Backup binlogs | &#10008; | &#10008; | &#10008; | &#10004; |
| Backup encrypted at rest | &#10008; | &#10008; | &#10008; | &#10004; |
| Parallel compression (pgzip) | &#10008; | &#10008; | &#10008; | &#10004; |
| Check local disk size before backup | &#10008; | &#10008; | &#10008; | &#10004; |
| Backup database config | &#10008; | &#10008; | &#10008; | &#10008; |
| Config change history | &#10008; | &#10008; | &#10008; | &#10008; |
| **Restore** | | | | |
| Multi-threaded reload | &#10008;* | &#10004; | &#10008; | &#10004; |
| Partial reload (per-table) | &#10008; | &#10004; | &#10004; | &#10004; |
| Replication-aware reload (GTID) | &#10008; | &#10008; | &#10008; | &#10004; |
| Reload without server restart | &#10004; | &#10004; | &#10008; | &#10004; |
| Reload on rejoin after failure | &#10008; | &#10008; | &#10008; | &#10004; |
| Reload for seeding new replicas | &#10008; | &#10008; | &#10008; | &#10004; |
| **Archiving** | | | | |
| Backup history | &#10008; | &#10008; | &#10008; | &#10004; |
| Incremental backup history | &#10008; | &#10008; | &#10004; | &#10004; |
| Purge policy (keep last/daily/weekly) | &#10008; | &#10008; | &#10008; | &#10004; |
| Disk limit for backup history | &#10008; | &#10008; | &#10008; | &#10004; |
| Stream backup to S3 | &#10008; | &#10008; | &#10008; | &#10004; |
| Restore from S3 | &#10008; | &#10008; | &#10008; | &#10004; |
| Stream backup to SFTP | &#10008; | &#10008; | &#10008; | &#10004; |
| Restore from SFTP | &#10008; | &#10008; | &#10008; | &#10004; |

> \* replication-manager adds multi-threaded reload to mysqldump via its built-in **splitdump** feature: mysqldump output is split into per-table files during backup, then restored in parallel using multiple mysql client sessions.

Backups are stored under:
```
<data_directory>/backups/<cluster_name>/<server_name>_<server_port>/
```

They are used for provisioning new nodes, reseeding broken replicas, and point-in-time recovery.

---

### 6.2.2.1 Backup Types

##### `backup-logical-type` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Type of logical backup |
| Type | string |
| Default Value | "mysqldump" |

Supported values: `mysqldump`, `mydumper`, `dumpling`

##### `backup-physical-type` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Type of physical backup |
| Type | string |
| Default Value | "xtrabackup" |

Supported values: `xtrabackup`, `mariabackup`. For MariaDB 10.1+, replication-manager automatically switches from xtrabackup to mariabackup.

##### `db-servers-backup-hosts` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Database hosts to backup (when set, can backup a replica instead of the master) |
| Type | list |
| Default Value | "" |

Use the same format as `db-servers-hosts`. When empty, backups run on the master.

---

### 6.2.2.2 mysqldump Options

##### `backup-mysqldump-path` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Path to mysqldump binary |
| Type | string |
| Default Value | "" (uses bundled binary) |

It is recommended to install a mysqldump version matching your database server version to avoid compatibility issues.

##### `backup-mysqldump-options` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Extra mysqldump options |
| Type | string |
| Default Value | "--hex-blob --single-transaction --verbose --all-databases --routines=true --triggers=true --system=all" |

> For MySQL or older MariaDB versions, remove `--system=all` which is MariaDB 10.5+ specific.

##### `backup-mysqldump-splitdump` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Split mysqldump output into per-table files using splitdump |
| Type | boolean |
| Default Value | false |

##### `backup-splitdump-file-size` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Max file size before sharding splitdump output |
| Type | string |
| Default Value | "1G" |

Examples: `16MiB`, `1G`. Set to `0` to disable sharding.

##### `backup-splitdump-create-databases` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Auto-create databases before splitdump restore |
| Type | boolean |
| Default Value | true |

#### How Splitdump Works

The problem with mysqldump is that it produces a single monolithic SQL file that can only be restored sequentially through one mysql client — this makes restore very slow on large databases.

**splitdump** solves this by taking a **regular mysqldump** output and splitting it into per-table files organized by schema, in a layout compatible with **myloader**. This means:

- **Backup uses standard mysqldump** — no special tools needed on the database host, no version mismatch risk, proven and trusted backup format
- **Restore is parallelized** — replication-manager restores the split files using multiple concurrent mysql client sessions, similar to how myloader restores mydumper output
- **Partial restore is possible** — since each table is a separate file, you can restore individual tables or schemas without replaying the entire dump. A regular monolithic mysqldump does not allow partial restore
- **The output is myloader-compatible** — you can also restore it manually with myloader if needed

When `backup-mysqldump-splitdump` is enabled, replication-manager pipes the mysqldump output through the built-in **splitdump** processor:

```
backups/<cluster>/<host_port>/splitdump/
├── metadata.json           # GTID position, binlog file/pos, source info
├── schema/
│   ├── mydb-schema.sql     # CREATE TABLE statements
│   └── otherdb-schema.sql
├── data/
│   ├── mydb.users.sql      # INSERT data per table
│   ├── mydb.orders.sql
│   └── otherdb.items.sql
└── post/
    ├── mydb-post.sql        # Triggers, routines, events
    └── otherdb-post.sql
```

Files are sharded at `backup-splitdump-file-size` boundaries (default 1G) to keep individual files manageable.

#### Parallel Restore

During reseed, replication-manager detects splitdump directories and restores them using **parallel mysql client sessions** instead of piping a single stream:

1. **Schema phase** — `CREATE DATABASE` + schema SQL files are restored sequentially
2. **Data phase** — data files are restored in parallel using `backup-logical-load-threads` concurrent mysql clients. Tables within the same schema are grouped to avoid lock contention
3. **Post phase** — triggers, routines, and events are restored sequentially

Each restore session:
- Sets `sql_log_bin=0` to avoid binlog pollution during reseed
- Applies GTID position from `metadata.json` after restore completes
- Handles DEFINER clause incompatibilities (configurable via `backup-restore-definer-strict`)

This is significantly faster than piping a single mysqldump stream through one mysql client, especially for large databases with many tables.

```toml
## Enable splitdump backup + parallel restore with 4 threads
backup-mysqldump-splitdump = true
backup-splitdump-file-size = "1G"
backup-logical-load-threads = 4
```

##### `backup-split-mysql-user` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Split mysql user table into a separate dump file |
| Type | boolean |
| Default Value | false |

##### `backup-restore-mysql-user` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Restore mysql user alongside with backup |
| Type | boolean |
| Default Value | true |

---

### 6.2.2.3 mydumper / myloader Options

##### `backup-mydumper-path` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Path to mydumper binary |
| Type | string |
| Default Value | "" |

##### `backup-myloader-path` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Path to myloader binary |
| Type | string |
| Default Value | "" |

##### `backup-mydumper-options` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Extra mydumper options |
| Type | string |
| Default Value | "--chunk-filesize=1000 --compress --less-locking --verbose=3 --triggers --routines --events --trx-consistency-only --kill-long-queries" |

##### `backup-myloader-options` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Extra myloader options |
| Type | string |
| Default Value | "--overwrite-tables --verbose=3 --innodb-optimize-keys=skip --max-threads-for-schema-creation=1 --max-threads-for-index-creation=1" |

##### `backup-mydumper-regex` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Mydumper regex filter for tables to backup |
| Type | string |
| Default Value | `^(?!(sys\.|performance_schema\.|information_schema\.|replication_manager_schema\.jobs|mysql\.gtid_slave_pos$))` |

##### `backup-mydumper-stream` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Enable mydumper stream mode (single file output) |
| Type | boolean |
| Default Value | false |

##### `backup-mydumper-stream-format` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Mydumper stream format (passed to --stream) |
| Type | string |
| Default Value | "" |

---

### 6.2.2.4 Thread Configuration

##### `backup-logical-dump-threads` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Number of threads for dumping (mydumper/mysqldump) |
| Type | integer |
| Default Value | 2 |

> Do not use too many threads when backing up the master — it can consume significant resources.

##### `backup-logical-load-threads` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Number of threads for restoring (myloader) |
| Type | integer |
| Default Value | 2 |

---

### 6.2.2.5 Physical Backup Options

##### `backup-lockddl` (2.2)

| Item | Value |
| ---- | ----- |
| Description | Use MariaDB backup stage for DDL locking |
| Type | boolean |
| Default Value | true |

---

### 6.2.2.6 Client Binary Paths

##### `backup-mysqlclient-path` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Path to mysql/mariadb client binary (used for piping restore SQL) |
| Type | string |
| Default Value | "" (uses bundled binary) |

##### `backup-mysqlclient-options` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Extra mysql client options for restore |
| Type | string |
| Default Value | "--force --batch" |

##### `backup-mysqlbinlog-path` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Path to mysqlbinlog binary |
| Type | string |
| Default Value | "" |

---

### 6.2.2.7 Compression

##### `compress-backups` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Compress backups using pgzip |
| Type | boolean |
| Default Value | false |

##### `compress-backups-logical` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Compression for logical backups |
| Type | string |
| Default Value | "auto" |

Values: `auto` (follows `compress-backups`), `true`, `false`

##### `compress-backups-physical` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Compression for physical backups |
| Type | string |
| Default Value | "auto" |

##### `compress-backups-compression-level` (3.0)

| Item | Value |
| ---- | ----- |
| Description | pgzip compression level |
| Type | integer |
| Default Value | 6 |

Range: 1 (fastest) to 9 (best compression)

##### `compress-backups-parallel-blocks` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Number of parallel blocks for pgzip decompression |
| Type | integer |
| Default Value | 16 |

##### `compress-backups-decompress-buffer-size` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Block size for pgzip decompression |
| Type | integer |
| Default Value | 250000 |

---

### 6.2.2.8 Disk Space Management

##### `backup-check-size` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Check free disk space before processing backup |
| Type | boolean |
| Default Value | true |

##### `backup-disk-treshold-warn` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Warning threshold for disk usage (percentage) |
| Type | integer |
| Default Value | 85 |

##### `backup-disk-treshold-crit` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Critical threshold — backup is skipped above this value |
| Type | integer |
| Default Value | 95 |

##### `backup-estimate-size` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Estimate backup size from information_schema before starting |
| Type | boolean |
| Default Value | false |

##### `backup-growth-percentage` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Expected growth from last backup for space check |
| Type | integer |
| Default Value | 50 |

##### `backup-keep-until-valid` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Keep previous backup renamed to .old until new backup is valid |
| Type | boolean |
| Default Value | false |

---

### 6.2.2.9 Custom Scripts

##### `backup-save-script` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Custom backup script (replaces mysqldump/mydumper). Receives: host, master_host, port, master_port, user, password, cluster, destination |
| Type | string |
| Default Value | "" |

##### `backup-load-script` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Custom restore script. Receives: host, master_host, port, master_port |
| Type | string |
| Default Value | "" |

##### `backup-logical-post-script` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Script to run after logical backup. Params: clustername, hostname, port, backup-path |
| Type | string |
| Default Value | "" |

##### `backup-physical-post-script` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Script to run after physical backup. Params: clustername, hostname, port, backup-path |
| Type | string |
| Default Value | "" |

---

### 6.2.2.10 Restore Options

##### `backup-restore-version-strict` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Abort restore if backup version doesn't match tools version |
| Type | boolean |
| Default Value | false |

When false, a version mismatch produces a warning but continues.

##### `backup-restore-definer-strict` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Fail on incompatible DEFINER clause during restore |
| Type | boolean |
| Default Value | false |

##### `backup-reseed-remote-decompress` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Decompress backup on remote server during reseed |
| Type | boolean |
| Default Value | false |

---

### 6.2.2.11 Binlog Archiving

##### `backup-binlogs` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Archive binary logs |
| Type | boolean |
| Default Value | false |

##### `backup-binlogs-keep` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Number of master binlog files to keep |
| Type | integer |
| Default Value | 10 |

---

## 6.2.3 Archive Backups (Restic)

replication-manager integrates with [Restic](https://restic.net/) for long-term backup archiving with block-level encryption, deduplication, and S3 storage. Restic must be pre-installed on the replication-manager host.

### 6.2.3.1 Enabling Restic

##### `backup-restic` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Enable Restic archiving of backups |
| Type | boolean |
| Default Value | false |

##### `backup-restic-binary-path` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Path to restic binary |
| Type | string |
| Default Value | "/usr/bin/restic" |

##### `backup-restic-password` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Restic repository encryption password |
| Type | string |
| Default Value | "secret" |

##### `backup-restic-timeout` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Restic operation timeout in seconds |
| Type | integer |
| Default Value | 7200 |

##### `backup-restic-dump-timeout` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Timeout for restic dump operations (0 uses backup-restic-timeout) |
| Type | integer |
| Default Value | 0 |

---

### 6.2.3.2 Repository Configuration

##### `backup-restic-repository` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Restic backend repository URL |
| Type | string |
| Default Value | "s3:https://s3.signal18.io/backups" |

##### `backup-restic-local-repository` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Restic local repository path (empty uses datadir/backups/archive/clustername) |
| Type | string |
| Default Value | "" |

##### `backup-restic-repo-append-cluster` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Append cluster name to restic repository path for per-cluster isolation |
| Type | boolean |
| Default Value | true |

---

### 6.2.3.3 S3 / AWS Configuration

##### `backup-restic-aws` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Archive to S3 (when false, archives to local datadir/backups/archive) |
| Type | boolean |
| Default Value | false |

##### `backup-restic-aws-access-key-id` (2.1)

| Item | Value |
| ---- | ----- |
| Description | AWS access key ID for S3 |
| Type | string |
| Default Value | "admin" |

##### `backup-restic-aws-access-secret` (2.1)

| Item | Value |
| ---- | ----- |
| Description | AWS secret access key for S3 |
| Type | string |
| Default Value | "secret" |

##### `backup-restic-aws-region` (3.0)

| Item | Value |
| ---- | ----- |
| Description | AWS region (empty uses SDK default) |
| Type | string |
| Default Value | "" |

##### `backup-restic-aws-endpoint` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Custom S3 endpoint URL (for MinIO, Ceph, etc.) |
| Type | string |
| Default Value | "" |

##### `backup-restic-aws-bucket` (3.0)

| Item | Value |
| ---- | ----- |
| Description | S3 bucket name (empty uses repository URL) |
| Type | string |
| Default Value | "" |

##### `backup-restic-aws-prefix` (3.0)

| Item | Value |
| ---- | ----- |
| Description | S3 key prefix inside the bucket |
| Type | string |
| Default Value | "" |

##### `backup-restic-additional-env` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Additional environment variables for restic (comma/space separated KEY=VALUE) |
| Type | string |
| Default Value | "" |

---

### 6.2.3.4 Direct S3 Streaming (without Restic)

For direct backup streaming to S3 without Restic:

##### `backup-streaming` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Enable direct backup streaming to S3 |
| Type | boolean |
| Default Value | false |

##### `backup-streaming-aws-access-key-id` (2.1)

| Item | Value |
| ---- | ----- |
| Description | AWS access key ID |
| Type | string |
| Default Value | "admin" |

##### `backup-streaming-aws-access-secret` (2.1)

| Item | Value |
| ---- | ----- |
| Description | AWS secret access key |
| Type | string |
| Default Value | "secret" |

##### `backup-streaming-endpoint` (2.1)

| Item | Value |
| ---- | ----- |
| Description | S3 endpoint URL |
| Type | string |
| Default Value | "https://s3.signal18.io/" |

##### `backup-streaming-region` (2.1)

| Item | Value |
| ---- | ----- |
| Description | S3 region |
| Type | string |
| Default Value | "fr-1" |

##### `backup-streaming-bucket` (2.1)

| Item | Value |
| ---- | ----- |
| Description | S3 bucket name |
| Type | string |
| Default Value | "repman" |

---

### 6.2.3.5 Restic Tags and Host

##### `backup-restic-tags` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Comma-separated tags or templates applied to snapshots |
| Type | string |
| Default Value | "tenant,cluster,engine,version,backup-type,backup-tool,line" |

##### `backup-restic-host` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Override --host for restic operations (empty uses system hostname) |
| Type | string |
| Default Value | "" |

---

### 6.2.3.6 Retention Policy

Restic retention is configured with `backup-keep-*` parameters, applied during `restic forget`:

| Parameter | Default | Description |
|---|---|---|
| `backup-keep-last` | 10 | Keep this many recent snapshots |
| `backup-keep-hourly` | 1 | Keep this many hourly snapshots |
| `backup-keep-daily` | 1 | Keep this many daily snapshots |
| `backup-keep-weekly` | 4 | Keep this many weekly snapshots |
| `backup-keep-monthly` | 12 | Keep this many monthly snapshots |
| `backup-keep-yearly` | 2 | Keep this many yearly snapshots |
| `backup-keep-within` | "" | Keep all snapshots within this duration (e.g. `2y5m7d3h`) |
| `backup-keep-within-hourly` | "" | Keep hourly snapshots within duration |
| `backup-keep-within-daily` | "" | Keep daily snapshots within duration |
| `backup-keep-within-weekly` | "" | Keep weekly snapshots within duration |
| `backup-keep-within-monthly` | "" | Keep monthly snapshots within duration |
| `backup-keep-within-yearly` | "" | Keep yearly snapshots within duration |

Zero values or empty strings are omitted from the policy.

---

### 6.2.3.7 Purge Configuration

##### `backup-restic-purge-oldest-on-disk-space` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Automatically purge oldest backup when disk space is critically low |
| Type | boolean |
| Default Value | true |

##### `backup-restic-purge-oldest-on-disk-threshold` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Disk usage percentage above which to purge (0 uses backup-disk-treshold-crit) |
| Type | integer |
| Default Value | 0 |

##### `backup-restic-purge-group-by` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Restic forget --group-by value |
| Type | string |
| Default Value | "host,paths" |

##### `backup-restic-purge-keep-tag` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Space-separated tags to always keep during purge |
| Type | string |
| Default Value | "line:adhoc adhoc" |

##### `backup-restic-purge-host` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Restic forget --host filter |
| Type | string |
| Default Value | "" |

##### `backup-restic-purge-tag` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Restic forget --tag filter |
| Type | string |
| Default Value | "" |

##### `backup-restic-purge-path` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Restic forget --path filter |
| Type | string |
| Default Value | "" |

##### `backup-restic-purge-prune` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Run prune after forget |
| Type | boolean |
| Default Value | true |

##### `backup-restic-purge-prune-compact` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Restic prune --compact |
| Type | boolean |
| Default Value | false |

##### `backup-restic-purge-prune-max-unused` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Restic prune --max-unused size (e.g. 1G) |
| Type | string |
| Default Value | "" |

##### `backup-restic-purge-prune-max-repack-size` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Restic prune --max-repack-size |
| Type | string |
| Default Value | "" |

---

### 6.2.3.8 Restic FUSE Mount

Restic can mount snapshots as a FUSE filesystem for browsing:

| Parameter | Default | Description |
|---|---|---|
| `backup-restic-mount-dir` | "" | Base directory for FUSE mounts (empty = working-dir/cluster/mount) |
| `backup-restic-mount-target-dir` | "" | Mount target directory |
| `backup-restic-mount-host` | "" | Host filter (comma-separated) |
| `backup-restic-mount-tag` | "" | Tag filter |
| `backup-restic-mount-path` | "" | Path filter |
| `backup-restic-mount-path-template` | "" | Path templates |
| `backup-restic-mount-time-template` | "" | Time template (Go time layout) |
| `backup-restic-mount-allow-other` | true | Allow other users to access mount |
| `backup-restic-mount-no-default-permissions` | false | Ignore default permission handling |
| `backup-restic-mount-owner-root` | false | Force root ownership |
| `backup-restic-mount-no-lock` | true | Disable repository locking during mount |
| `backup-restic-mount-verbose` | 0 | Verbosity level (0-3) |
| `backup-restic-mount-quiet` | false | Quiet mode |
| `backup-restic-mount-recovery-enabled` | true | Cleanup stale mounts on startup |

##### `backup-restic-allow-unsafe-mount` (3.0)

| Item | Value |
| ---- | ----- |
| Description | Allow using a restic mount created by another process |
| Type | boolean |
| Default Value | false |

---

### 6.2.3.9 Restic Reseed

| Parameter | Default | Description |
|---|---|---|
| `backup-restic-reseed-strategy` | "auto" | Strategy: auto, restore, dump, mount |
| `backup-restic-reseed-temp-dir` | "" | Temp directory for reseed (empty = cluster datadir) |
| `backup-restic-reseed-cleanup` | true | Auto-cleanup temp files after reseed |
| `backup-restic-reseed-timeout` | 3600 | Reseed timeout in seconds |

---

### 6.2.3.10 Restic Permissions and Metadata

| Parameter | Default | Description |
|---|---|---|
| `backup-restic-dir-mode` | 700 | Directory permissions (octal) |
| `backup-restic-file-mode` | 600 | File permissions (octal) |
| `backup-restic-metadata-extractor-concurrency` | 2 | Concurrent snapshot metadata extractions |
| `backup-reconcile-interval` | 600 | Backup metadata reconciliation interval in seconds (0=disabled) |
| `backup-reconcile-auto-cleanup` | false | Auto-cleanup orphaned metadata during reconciliation |
