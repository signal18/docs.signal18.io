---
title: Archiving
taxonomy:
    category: docs
---
### 7.2.2.2.0.1 Archiving Backups

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

## 7.2.2.2.1 Disk Space Management

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

## 7.2.2.2.2 Configuration Examples

### 7.2.2.2.2.1 Local Repository Configuration

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

### 7.2.2.2.2.2 S3/Minio Configuration

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

### 7.2.2.2.2.3 Retention Policy

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

Each snapshot is the backup directory at that time, so it restores on its own. Every snapshot
carries a `trigger:` tag saying what took it:

| Tag | Taken by |
| --- | -------- |
| `trigger:backup` | a backup, once it is in the backup directory |
| `trigger:manual` | the Archive action of one backup |
| `trigger:snapback` | a snapback, of the backup directory before it is put back |
| `trigger:binlogs` | a binlog-only snapshot: binlog copies leaving `backup-binlogs-keep` that no snapshot holds yet (see Binlogs) |

#### Tagged purge policies (3.1)

The `backup-keep-*` values above are the **default policy**. A **tagged policy** keeps the
snapshots carrying its tag by its own keep values, counted apart: the binlog-only snapshots never
push a backup out of `backup-keep-last`. A snapshot falls under the first tagged policy whose tag
it carries, else under the default one.

##### `backup-keep-tag-policies` (3.1)

| Item | Value |
| ---- | ----- |
| Description | The cluster's tagged policies: rules separated by `;`, each a tag then its keep values (`keep-last`, `keep-hourly`, `keep-daily`, `keep-weekly`, `keep-monthly`, `keep-yearly`, `keep-within`, `keep-within-hourly` ... `keep-within-yearly`) |
| Type | string |
| Default Value | `trigger:binlogs keep-within=1d` |

```toml
backup-keep-tag-policies = "trigger:binlogs keep-within=1d; trigger:snapback keep-last=1"
```

Under the `trigger:binlogs` policy, the binlog-only snapshots taken since the last backup are
always kept, whatever its keep values: a binlog only extends a backup, so the binlog chain from
the last backup is never cut. The keep values decide how long the older ones stay, the
point-in-time restore window before the last backup.

**An archive's own policies.** Each archive that purges has a `purge-policies` setting, in the
same form. Empty, the cluster's policies apply (`backup-keep-*` and `backup-keep-tag-policies`).
Set, they replace them for that archive; a `*` rule replaces the default policy too:

```toml
[[mycluster.archive]]
name = "archive-bucket"
plugin = "archive-restic-s3"
purge-policies = "* keep-daily=30 keep-monthly=12; trigger:binlogs keep-within=7d"
```

With tagged policies, replication-manager decides which snapshots stay, counting as restic
forget does (per host and paths, or `purge-group-by` for a restic archive), and deletes the
others by id: the same for restic and for the plugin archives (plakar, PBS). The purge options
of the archive still apply: always kept tag, host/tag/path filters, prune. Without any tagged
policy, the archive applies `backup-keep-*` its own way, as before. In the GUI: Archive
settings, Retention policy, **Tagged policies**; and in an archive's **Purge options**.

The retention purge always applies, a snapback included. Only the deletion of one snapshot that
a snapback holding a backup directory at an older snapshot needs (the one it is at, or its latest
one) is refused: snap back to the latest snapshot or release the snapback first.

## 7.2.2.2.3 Backup Catalogue (3.1)

A backup directory holds one backup of each type: each new backup of a type replaces the one
there, and the history is the snapshots of the directory in the archive. The **backup catalogue**
is the list of the backups replication-manager knows, and the snapshots holding each one. It is
kept in the cluster's working directory, outside the backup directories:

```
<working-dir>/<cluster>/backup-catalogue/backups.json            every backup
<working-dir>/<cluster>/backup-catalogue/archives/<archive>.json every snapshot of the archive and its backups
```

A backup a newer one replaced stays in the catalogue while a snapshot holds it, shown **only in
the archive**: a restart or a backups folder switch no longer forgets it. It is restored from its
snapshot (a snapback of the backup directory). The active replication-manager writes the catalogue; a standby never does.

**Rebuild from the archives.** Each snapshot carries the records of the backups it holds.
replication-manager reads them in the snapshots the catalogue does not know: a backup the
catalogue lacks joins it, only in the archive; a backup it has gets the snapshot when it records
none of that archive. The rebuild runs:

- at the first start with no catalogue, and when a standby becomes active, about a minute later;
- from the archive's **Backup catalogue** row in the Archive settings (**Rebuild**), or the API:
  `POST /api/clusters/{cluster}/archive-actions/rebuild-catalogue/{archive}` (every readable
  archive without `{archive}`), with the `db-backup` grant.

It reads the archive plugins that list a snapshot's content (plakar, PBS) and the attached restic
archive. One runs at a time, CINF0021 while it runs; the row shows the outcome of the last one.

**When the catalogue and an archive differ** (WARN0260): a snapshot the plugin cannot list is not
read, so the backups only in it stay unknown; a snapshot the catalogue records that the archive no
longer has means its backups point to a copy that no longer exists. replication-manager never
fixes it silently: **Drop missing** on the Backup catalogue row (or
`POST /api/clusters/{cluster}/archive-actions/drop-missing-snapshots/{archive}`) lists the archive
again and takes out each snapshot still gone; a backup whose only copy it was leaves the backup
list. Nothing is deleted in the archive. A purge, of the archive's plugin or of restic, keeps the
catalogue right by itself: the backups recording a snapshot it deleted lose it.

## 7.2.2.2.4 Snapback (3.1.45)

A **snapback** puts a server's backup directory back exactly as it was in a snapshot of the
attached archive (restic, plakar or Proxmox Backup Server), to restore from what it held then.
Ask it from the **Maintenance** tab, the rewind button on a snapshot of the attached archive, or
through the API: `POST /api/clusters/{cluster}/archive-actions/snapback/{snapshot}` (the
`db-restore` grant). It runs in the background: the directory as it is now is snapshotted first,
which is its latest snapshot, then emptied, then the snapshot is restored into it, and the
server's backups are read again; it never takes twice the directory's size on disk. It is refused
while a backup is written, while the backups folder moves, while a snapback of the server runs, or
without room for the snapshot.

While the directory is not at its latest snapshot, the **archive is read-only for that server**
(WARN0255): no backup is written in its backup directory nor pushed to the archive, and the
snapshot it stands on and its latest one cannot be deleted by hand. Two ways out, each offered as
a button next to WARN0255 in the **Health** alerts, or WARN0256 when a snapback failed:

- **Snapback to latest**: the directory back as it was before the snapback, its backups open again
  (`.../archive-actions/snapback/{latest snapshot}`).
- **Release**: the directory kept as it is and its backups opened again; its next backup's snapshot
  is its new latest one (`POST /api/clusters/{cluster}/archive-actions/snapback-release/{server}`).

The snapback state is kept across a restart: a snapback running when replication-manager stops
reads as failed (WARN0256) and names the latest snapshot to snap back to.

## 7.2.2.2.5 Restic Task Management

**replication-manager 3.1** includes task queue management for restic operations. Long-running backup operations are tracked and can be:

- Paused via API: `PUT /api/clusters/{cluster}/servers/{server}/actions/task-pause`
- Resumed via API: `PUT /api/clusters/{cluster}/servers/{server}/actions/task-resume`
- Cancelled via API: `PUT /api/clusters/{cluster}/servers/{server}/actions/task-cancel`

Task status is visible in the web UI under server details.

## 7.2.2.2.6 Automatic Cleanup Workflow

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

## 7.2.2.2.7 Performance Considerations

- Restic operations are I/O intensive, especially `prune` and `check`
- Set `backup-restic-timeout` appropriately for repository size
- Network timeouts may occur with S3 for large repositories
- Purge operations can take significant time on full disks
- Monitor restic task status via API to track progress
