---
title: Overview
taxonomy:
    category: docs
---

## Maintenance

replication-manager embeds a cluster scheduler that automates database maintenance tasks: backups, log collection, table optimization, rolling restarts, and more. The scheduler is disabled by default — each task is individually enabled and given its own cron expression.

---

## How the Job System Works

Maintenance tasks are coordinated through a message queue table that replication-manager creates and manages on every monitored database server. The flow is:

![dbjobnodetorepman](/images/dbjobnodetorepman.png)

1. **Scheduler fires** — the [Robfig](https://pkg.go.dev/github.com/robfig/cron) cron scheduler triggers a task at the configured interval
2. **Job is enqueued** — replication-manager opens a TCP connection to the database server and inserts a row into `replication_manager_schema.jobs`. All writes to this table use `sql_log_bin=0` so they do not produce binlog events
3. **A port is reserved** — replication-manager picks a free port from `scheduler-db-servers-sender-ports` to receive the streamed result. It passes this address and port in the job row
4. **The dbjobs script runs** — a shell script on the database host reads the job table, picks up the pending task, marks it `processing`, executes the work, and streams the output back to replication-manager via `socat`
5. **Result is received** — replication-manager accepts the stream on the reserved TCP port and writes it to the appropriate destination (log file, backup directory, etc.)
6. **Job is closed** — the script writes the result into the `result` field and sets `done=1`; replication-manager confirms completion on the next check

### Jobs Table

```sql
CREATE TABLE replication_manager_schema.jobs (
  id      INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  task    VARCHAR(20),
  port    INT,
  server  VARCHAR(255),
  done    TINYINT      NOT NULL DEFAULT 0,
  state   TINYINT      NOT NULL DEFAULT 0,
  result  MEDIUMTEXT,
  payload MEDIUMTEXT,
  start   DATETIME,
  end     DATETIME,
  KEY idx1 (task, done),
  KEY idx2 (result(1), task),
  KEY idx3 (task, state),
  UNIQUE  (task)
) ENGINE=InnoDB;
```

The `state` column tracks the task lifecycle:
- `0` — available (pending)
- `1` — processing
- `3` — finished recently (post-job check)
- `5` — halted (replication-manager restarted mid-task; aborted for safety)

---

## Scheduled Tasks

| Task | Config key | Description |
|---|---|---|
| Logical backup | `scheduler-db-servers-logical-backup` | mydumper or mysqldump full backup |
| Physical backup | `scheduler-db-servers-physical-backup` | mariabackup or xtrabackup xbstream |
| Log collection | `scheduler-db-servers-logs` | Fetch error log and slow query log from each server |
| Log table rotate | `scheduler-db-servers-logs-table-rotate` | Rotate `mysql.general_log` / `slow_log` tables |
| Optimize | `scheduler-db-servers-optimize` | `OPTIMIZE TABLE` on all schemas |
| Analyze | `scheduler-db-servers-analyze` | `ANALYZE TABLE` on all schemas |
| Rolling restart | `scheduler-rolling-restart` | Restart replicas then primary one at a time |
| Rolling reprov | `scheduler-rolling-reprov` | Re-provision services on a rolling basis (pro) |
| SSH job runner | `scheduler-jobs-ssh` | Trigger the dbjobs script via SSH (osc) |

Each task has a matching `-cron` key for its schedule, e.g.:

```toml
monitoring-scheduler                      = true
scheduler-db-servers-logical-backup       = true
scheduler-db-servers-logical-backup-cron  = "0 0 1 * * *"   # 01:00 every day

scheduler-db-servers-logs                 = true
scheduler-db-servers-logs-cron            = "0 0 */6 * * *"  # every 6 hours

scheduler-db-servers-optimize             = true
scheduler-db-servers-optimize-cron        = "0 0 3 * * 0"    # Sundays at 03:00
```

---

## How the dbjobs Script Is Delivered

The script that runs on each database node is a bash script named `dbjobs_new`. How it gets there depends on the orchestration mode:

### replication-manager-pro (container mode)

In pro mode with OpenSVC, Kubernetes, or local orchestration, the `dbjobs_new` script is packaged into a gzip'd config tarball. An **init container** that shares the same network namespace as the database container downloads this tarball from replication-manager at startup and unpacks it. The script runs inside the container on the same host as the database process, connecting to the database over `127.0.0.1`.

### replication-manager-osc (SSH mode)

In osc mode, or when using the `onpremise` orchestrator in pro, replication-manager delivers and runs the script over SSH:

1. The script is unpacked into the server's data directory at `{datadir}/init/init/dbjobs_new`
2. On each scheduler tick (controlled by `scheduler-jobs-ssh-cron`), replication-manager SSHs into the database host, prepends the environment variable block, and pipes the script through the remote shell

Enable SSH job execution:

```toml
scheduler-jobs-ssh       = true
scheduler-jobs-ssh-cron  = "*/1 * * * * *"   # run every minute

onpremise-ssh            = true
onpremise-ssh-port       = 22
onpremise-ssh-credential = "deploy"           # user; key auth used if no password
onpremise-ssh-private-key = "/home/repman/.ssh/id_rsa"
```

To use a custom script instead of the generated one:

```toml
onpremise-ssh-db-job-script = "/opt/custom/dbjobs.sh"
```

---

## Environment Variables Injected by replication-manager

When the script runs via SSH, replication-manager prepends a block of `export` statements before the script body. The script can use these variables without hardcoding any credentials or addresses:

| Variable | Content |
|---|---|
| `REPLICATION_MANAGER_URL` | Full HTTPS URL of the replication-manager API (`https://host:port`) |
| `REPLICATION_MANAGER_URL_HOST` | API hostname / IP (`monitoring-address`) |
| `REPLICATION_MANAGER_URL_PORT` | API port (`api-port`) |
| `REPLICATION_MANAGER_USER` | API admin username |
| `REPLICATION_MANAGER_PASSWORD` | API admin password |
| `REPLICATION_MANAGER_HOST_NAME` | Database server hostname |
| `REPLICATION_MANAGER_HOST_PORT` | Database server port |
| `REPLICATION_MANAGER_HOST_USER` | Database user replication-manager connects with |
| `REPLICATION_MANAGER_HOST_PASSWORD` | Database password (also exported as `MYSQL_ROOT_PASSWORD`) |
| `MYSQL_ROOT_PASSWORD` | Alias for `REPLICATION_MANAGER_HOST_PASSWORD` |
| `REPLICATION_MANAGER_CLUSTER_NAME` | Cluster section name from the config file |

These variables allow the script to call back into the replication-manager API, connect to the local database, and stream results without any static configuration.

---

## How Results Are Streamed Back

Tasks that produce output (backups, log fetches) stream their data back to replication-manager using `socat`. replication-manager opens a TCP listener on a port from `scheduler-db-servers-sender-ports` and records that address in the job row. The script reads the address from the row and pipes the output directly:

```bash
# Example: stream a physical backup back to replication-manager
mariabackup --stream=xbstream ... | socat -u stdio TCP:$ADDRESS
```

replication-manager writes the received stream to:
- **Logs** (error log, slow query log): `{datadir}/{cluster}/{host_port}/`
- **Backups**: `{datadir}/Backups/{cluster}/{host_port}/`
- **Restic archive**: streamed into Restic after local write (if `backup-restic = true`)

Configure the TCP address replication-manager advertises to the database nodes:

```toml
monitoring-address              = "10.0.1.5"          # must be reachable from DB hosts
scheduler-db-servers-sender-ports = "4000,4001,4002"  # port pool; one per concurrent task
```

> If `scheduler-db-servers-sender-ports` is not set, replication-manager picks any available port on the host. In firewalled environments, setting an explicit port pool allows precise firewall rules.
