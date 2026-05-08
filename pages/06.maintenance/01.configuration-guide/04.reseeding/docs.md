---
title: Reseeding
taxonomy:
    category: docs
---

## 6.2.4 Reseeding

replication-manager can stream backups to a new or de-synced database node to bring it back into the replication chain. This process is called **reseeding**.

### 6.2.4.1 Prerequisites

Reseeding requires:

1. **A backup policy** — configure physical backups via the scheduler or run an on-demand backup from the GUI/API
2. **Backup tools installed on the database host** — `xtrabackup` or `mariabackup` (for physical reseeds) or `mysqldump`/`mydumper` (for logical reseeds)
3. **The dbjobs script running** on the database host — either via SSH (`scheduler-jobs-ssh`) or the container init entrypoint

### 6.2.4.2 How It Works

The reseed flow depends on the job dispatch mode ([see Job Dispatch Modes](/maintenance/overview#6-1-8-job-dispatch-modes-since-3-x)):

**SQL mode** (default):
1. replication-manager opens a TCP receiver on a port from `scheduler-db-servers-sender-ports`
2. Inserts a reseed task into the `replication_manager_schema.jobs` table with the receiver address
3. The dbjobs script picks up the task, receives the backup stream via `socat`, prepares it, and restores

**API mode**:
1. replication-manager sets a reseed cookie (`@cookie_waitreseedxtrabackup` or `@cookie_waitreseedmariabackup`)
2. The dbjobs script checks `needs/{task}` API → opens a local `socat` listener
3. Calls `receive-task/{task}` API → replication-manager opens a TCP receiver
4. Backup stream flows, dbjobs prepares and restores

### 6.2.4.3 Reseed Methods

| Method | API endpoint | Description |
| ---- | ------- | ------- |
| physicalbackup | `POST /api/clusters/{name}/servers/{host}/{port}/actions/reseed/physicalbackup` | Reseed from last physical backup (xtrabackup/mariabackup) |
| logicalbackup | `POST /api/clusters/{name}/servers/{host}/{port}/actions/reseed/logicalbackup` | Reseed from last mysqldump/mydumper backup |
| logicalmaster | `POST /api/clusters/{name}/servers/{host}/{port}/actions/reseed/logicalmaster` | Direct mysqldump from master via replication-manager |

### 6.2.4.4 Automatic Reseeding

When a server shows up as **standalone** (no slave status, e.g. after a failover or restart), replication-manager attempts to rejoin it to the replication chain. The rejoin strategy depends on the configuration:

1. **Incremental rejoin** (default) — tries to resume replication from the last known GTID position. Works when binlog events are still available on the master.
2. **Force restore** — when `autorejoin-force-restore = true`, incremental rejoin is skipped entirely and a full state transfer (SST) is triggered instead.

To enable force restore:

```toml
autorejoin-force-restore = true
```

The SST method used depends on which rejoin option is enabled:

| Config | Method | Description |
|---|---|---|
| `autorejoin-mysqldump = true` | Direct dump | mysqldump from master via replication-manager |
| `autorejoin-logical-backup = true` | Logical backup | Restore from last mysqldump/mydumper backup |
| `autorejoin-physical-backup = true` | Physical backup | Restore from last xtrabackup/mariabackup backup |
| `autorejoin-zfs-flashback = true` | ZFS snapshot | Rollback to previous ZFS snapshot |
| `backup-load-script` set | Custom script | Execute user-provided restore script |

These options are evaluated in priority order — the first enabled method is used.

##### `autorejoin-force-restore` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Force a full state transfer when a standalone server rejoins, instead of attempting incremental rejoin from GTID position |
| Type | boolean |
| Default Value | false |

> **Warning:** Force restore destroys all data on the target replica and replaces it with the backup. Enable only when you have a reliable backup schedule and understand the implications. At least one SST method (`autorejoin-mysqldump`, `autorejoin-physical-backup`, etc.) must also be enabled.

### 6.2.4.5 Donor and Joiner Script

The dbjobs script handles both donor (backup streaming) and joiner (restore) operations.

**replication-manager-pro (container mode):**

The script is delivered automatically via the compliance configurator. It is embedded in the replication-manager binary and written into the `config.tar.gz` tarball during configuration generation. The init container unpacks and runs it. No manual setup is needed.

Since version 3.x, the script is embedded directly via `go:embed` to avoid the 65535-byte truncation limit of the OpenSVC compliance database. Existing deployments receive the full script automatically via the built-in script auto-upgrade mechanism.

**replication-manager-osc (SSH mode):**

The script is delivered and executed over SSH on each scheduler tick (`scheduler-jobs-ssh-cron`). Enable it with:

```toml
monitoring-scheduler = true
scheduler-jobs-ssh = true
scheduler-jobs-ssh-cron = "0 * * * * *"

onpremise-ssh = true
onpremise-ssh-credential = "deploy"
onpremise-ssh-private-key = "/home/repman/.ssh/id_rsa"
```

To use a custom script instead of the built-in one:

```toml
onpremise-ssh-db-job-script = "/opt/custom/dbjobs.sh"
```

The custom script receives the same environment variables ([see Environment Variables](/maintenance/overview#6-1-5-environment-variables-injected-by-replication-manager)) and should follow the same job protocol.

**Source:**

The script source is at `share/scripts/dbjobs_new.sh` in the replication-manager repository. It is the reference for both container and SSH deployments.
