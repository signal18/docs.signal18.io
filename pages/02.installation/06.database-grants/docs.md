---
title: Database Grants
taxonomy:
    category: docs
---

## 2.8.1 Minimum Database Grants

replication-manager connects to each monitored database server using two distinct credentials:

| Config Parameter | Purpose | Default |
|---|---|---|
| `db-servers-credential` | **Monitoring user** — used for all health checks, topology queries, and orchestration commands | `root:` |
| `replication-credential` | **Replication user** — configured as the `MASTER_USER` / `SOURCE_USER` on every replica | `repl:repman` |

Grant the minimum set of privileges shown below. Both users must be created with TCP/IP access (IP address, not `localhost`/socket), since replication-manager always connects via the network.

---

## 2.8.2 Monitoring User (`db-servers-credential`)

This account is used for everything replication-manager does continuously: topology discovery, status monitoring, failover, switchover, backup job dispatch, and schema checks.

### 2.8.2.1 Required Privileges — Summary

| Privilege | Why needed | replication-manager feature / config |
|---|---|---|
| `SELECT` | Read `mysql.user` to validate credentials; query `information_schema` and `performance_schema` | Always required; privilege check at startup |
| `PROCESS` | `SHOW PROCESSLIST` — detect long-running transactions, lock waits, blocking queries | `monitoring-processlist`, processlist monitoring |
| `REPLICATION CLIENT` | `SHOW MASTER STATUS`, `SHOW BINARY LOGS` — binlog position tracking | Replication lag, GTID monitoring |
| `REPLICATION SLAVE` | `SHOW SLAVE STATUS` / `SHOW REPLICA STATUS` — replica health | Core topology monitoring |
| `RELOAD` | `FLUSH TABLES WITH READ LOCK`, `FLUSH LOGS` — consistent backup snapshots | Backup integration (`backup-mysqldump`, `backup-mydumper`) |
| `SUPER` | `SET GLOBAL read_only`, `SET GLOBAL` variables, `CHANGE MASTER TO`, `RESET SLAVE` | Failover, switchover, `READ_ONLY ADMIN`, `CONNECTION ADMIN` on MariaDB ≥10.5 |
| `ALL` on `replication_manager_schema.*` | Read and write the job queue used for SST streaming and backup tasks | `scheduler-db-servers-logical-backup`, reseeding jobs |
| `EXECUTE` | Execute stored procedures on monitored schemas (optional, used by schema check features) | `check-replication-filters`, schema checks |

> **Note:** `SUPER` covers several operations on MariaDB < 10.5 and MySQL < 8.0. On newer versions you can replace it with fine-grained alternatives — see sections 2.8.2.2 and 2.8.2.3 below.

---

### 2.8.2.2 MariaDB Monitoring User

#### MariaDB < 10.5 (uses `SUPER`)

```sql
CREATE USER 'repman'@'%' IDENTIFIED BY 'repman_password';

GRANT SELECT, PROCESS, REPLICATION CLIENT, REPLICATION SLAVE,
      RELOAD, SUPER
  ON *.* TO 'repman'@'%';

GRANT ALL PRIVILEGES
  ON replication_manager_schema.*
  TO 'repman'@'%';

FLUSH PRIVILEGES;
```

#### MariaDB ≥ 10.5.2 (fine-grained privileges — recommended)

MariaDB 10.5.2 introduced dedicated fine-grained privileges that replace the need for `SUPER`:

| Fine-grained privilege | Replaces `SUPER` for |
|---|---|
| `BINLOG MONITOR` | `SHOW MASTER STATUS`, `SHOW BINARY LOGS`, `SHOW BINLOG EVENTS` |
| `REPLICATION SLAVE ADMIN` | `CHANGE MASTER TO`, `RESET SLAVE`, `START SLAVE`, `STOP SLAVE` |
| `REPLICATION MASTER ADMIN` | `RESET MASTER`, purge binary logs |
| `READ_ONLY ADMIN` | `SET GLOBAL read_only = 1/0` — isolate master during switchover |
| `CONNECTION ADMIN` | Connect even when `max_connections` is exhausted; bypass `max_connections` |

```sql
CREATE USER 'repman'@'%' IDENTIFIED BY 'repman_password';

GRANT SELECT, PROCESS, RELOAD,
      REPLICATION CLIENT, REPLICATION SLAVE,
      BINLOG MONITOR,
      REPLICATION SLAVE ADMIN,
      REPLICATION MASTER ADMIN,
      READ_ONLY ADMIN,
      CONNECTION ADMIN
  ON *.* TO 'repman'@'%';

GRANT ALL PRIVILEGES
  ON replication_manager_schema.*
  TO 'repman'@'%';

FLUSH PRIVILEGES;
```

> **`CONNECTION ADMIN` is important:** during switchover replication-manager temporarily sets `max_connections = 1` to drain existing connections. Without `CONNECTION ADMIN` (or `SUPER`), replication-manager would lose its own monitoring connection during that critical window. This is the same reason the [extra port (3307)](/installation/network-ports) is recommended.

---

### 2.8.2.3 MySQL / Percona Server Monitoring User

#### MySQL < 8.0 (uses `SUPER`)

```sql
CREATE USER 'repman'@'%' IDENTIFIED BY 'repman_password';

GRANT SELECT, PROCESS, REPLICATION CLIENT, REPLICATION SLAVE,
      RELOAD, SUPER
  ON *.* TO 'repman'@'%';

GRANT ALL PRIVILEGES
  ON replication_manager_schema.*
  TO 'repman'@'%';

FLUSH PRIVILEGES;
```

#### MySQL ≥ 8.0 (dynamic privileges — recommended)

MySQL 8.0 introduced dynamic privileges to replace `SUPER`:

| Dynamic privilege | Replaces `SUPER` for |
|---|---|
| `REPLICATION_SLAVE_ADMIN` | `CHANGE REPLICATION SOURCE TO`, `RESET REPLICA`, `START REPLICA`, `STOP REPLICA` |
| `BINLOG_ADMIN` | `PURGE BINARY LOGS`, `RESET MASTER` |
| `SYSTEM_VARIABLES_ADMIN` | `SET GLOBAL` system variables (including `read_only`) |
| `CONNECTION_ADMIN` | Connect past `max_connections`; bypass `SUPER` connection limit |

```sql
CREATE USER 'repman'@'%' IDENTIFIED BY 'repman_password' PASSWORD EXPIRE NEVER;

GRANT SELECT, PROCESS, REPLICATION CLIENT, REPLICATION SLAVE,
      RELOAD
  ON *.* TO 'repman'@'%';

GRANT REPLICATION_SLAVE_ADMIN, BINLOG_ADMIN,
      SYSTEM_VARIABLES_ADMIN, CONNECTION_ADMIN
  ON *.* TO 'repman'@'%';

GRANT ALL PRIVILEGES
  ON replication_manager_schema.*
  TO 'repman'@'%';

FLUSH PRIVILEGES;
```

> On MySQL 8.0 `REPLICATION CLIENT` maps to `REPLICATION_CLIENT` (legacy alias still accepted). `RELOAD` is still required for `FLUSH TABLES WITH READ LOCK` used during consistent backups.

---

## 2.8.3 Replication User (`replication-credential`)

The replication user is set as `MASTER_USER` / `SOURCE_USER` on every replica via `CHANGE MASTER TO` / `CHANGE REPLICATION SOURCE TO`. It only needs one privilege:

| Privilege | Why needed |
|---|---|
| `REPLICATION SLAVE` | Stream binary logs from primary to replica |

#### MariaDB

```sql
CREATE USER 'repl'@'%' IDENTIFIED BY 'repl_password';

GRANT REPLICATION SLAVE ON *.* TO 'repl'@'%';

FLUSH PRIVILEGES;
```

#### MySQL ≥ 8.0

```sql
CREATE USER 'repl'@'%' IDENTIFIED BY 'repl_password' PASSWORD EXPIRE NEVER;

GRANT REPLICATION SLAVE ON *.* TO 'repl'@'%';

FLUSH PRIVILEGES;
```

> Configure replication credentials in `replication-manager.toml`:
> ```toml
> replication-credential = "repl:repl_password"
> ```

---

## 2.8.4 `replication_manager_schema` Database

replication-manager automatically creates the `replication_manager_schema` database and its tables on first connection. The monitoring user must have `ALL PRIVILEGES` on this schema because replication-manager:

- Creates the `jobs` table to dispatch SST/backup tasks to donor nodes
- Inserts and updates job rows for backup streaming (mariabackup/xtrabackup via `socat`)
- Stores heartbeat rows when arbitration is enabled
- Creates checksum tables for schema drift detection (`check-replication-filters`)

replication-manager creates the database with:
```sql
CREATE DATABASE IF NOT EXISTS replication_manager_schema;
```

If the monitoring user does not have `CREATE` on `*.*` (which is not in the minimum grant list above), grant it explicitly on the schema after manual creation:

```sql
CREATE DATABASE IF NOT EXISTS replication_manager_schema;

GRANT ALL PRIVILEGES
  ON replication_manager_schema.*
  TO 'repman'@'%';
```

---

## 2.8.5 Privilege Validation at Runtime

On every monitoring tick replication-manager reads `mysql.user` to verify the monitoring user's privileges are intact:

```sql
SELECT COALESCE(MAX(Select_priv),'N')      AS Select_priv,
       COALESCE(MAX(Process_priv),'N')     AS Process_priv,
       COALESCE(MAX(Super_priv),'N')       AS Super_priv,
       COALESCE(MAX(Repl_slave_priv),'N')  AS Repl_slave_priv,
       COALESCE(MAX(Repl_client_priv),'N') AS Repl_client_priv,
       COALESCE(MAX(Reload_priv),'N')      AS Reload_priv
FROM mysql.user
WHERE user = ? AND host IN (?, ...)
```

If any required column returns `N`, replication-manager logs a privilege warning and disables the features that depend on it. Check the replication-manager logs for messages like:

```
[WARN] No replication user defined. Please check the replication user is created with the required privileges
```

---

## 2.8.6 Restricting by IP / Host

For production environments, restrict access to the replication-manager host IP instead of using `%`:

```sql
-- Replace 192.168.1.100 with your replication-manager host IP
CREATE USER 'repman'@'192.168.1.100' IDENTIFIED BY 'repman_password';
GRANT ... ON *.* TO 'repman'@'192.168.1.100';

CREATE USER 'repl'@'192.168.1.%' IDENTIFIED BY 'repl_password';
GRANT REPLICATION SLAVE ON *.* TO 'repl'@'192.168.1.%';
```

> The replication user needs to connect from the **primary**, not from the replication-manager host. Use a subnet wildcard that covers all DB hosts (`192.168.1.%`) or create one entry per host.
