---
title: Install Dependencies
taxonomy:
    category: docs
---

## 2.1.11.1 Install Dependencies

replication-manager is a self-contained binary — no system libraries are required at the OS level. However, several external tools are needed to enable specific features such as backup, proxy management, load testing, and terminal sessions.

replication-manager **auto-detects** all external tools at startup by searching `PATH`. If a tool is found, its version is logged and used automatically. If a tool is not found, the related feature is disabled and a `WARN` state is set in the cluster dashboard. Paths can always be overridden explicitly in the cluster configuration.

---

## 2.1.11.2 Supported Database Versions

| Database | Minimum version | Notes |
|---|---|---|
| MariaDB | 10.0 | GTID recommended (10.0.2+) |
| MySQL | 5.6 | GTID recommended (5.6+) |
| Percona Server | 5.6 | Treated as MySQL-compatible |

GTID-based replication is strongly recommended for reliable failover. Non-GTID topologies are supported with limitations.

---

## 2.1.11.3 External Tools

### 2.1.11.3.1 Auto-Detected Tool Matrix

| Tool | Feature | Auto-detect order | Config key to override |
|---|---|---|---|
| `mariadb` / `mysql` | SQL client, connection checks | `mariadb` → `mysql` → embedded | `backup-mysqlclient-path` |
| `mariadb-dump` / `mysqldump` | Logical backups | `mariadb-dump` → `mysqldump` → embedded | `backup-mysqldump-path` |
| `mariadb-binlog` / `mysqlbinlog` | Binlog processing and pitr | `mariadb-binlog` → `mysqlbinlog` → embedded | `backup-mysqlbinlog-path` |
| `mydumper` | Fast parallel logical backup | `mydumper` → embedded | `backup-mydumper-path` |
| `myloader` | Fast parallel restore | `myloader` → embedded | `backup-myloader-path` |
| `restic` | Snapshot backup archiving | configured path only | `backup-restic-binary-path` |
| `haproxy` | TCP/HTTP proxy | configured path | `haproxy-binary-path` |
| `proxysql` | MySQL-protocol proxy | configured path | `proxysql-binary-path` |
| `maxscale` | MaxScale proxy | configured path | `maxscale-binary-path` |
| `sysbench` | Benchmarking / load injection | `sysbench` in PATH | `sysbench-binary-path` |
| `gotty-client` | Browser terminal sessions | `gotty-client` → embedded | `backup-gotty-client-path` |
| `fusermount` / `fusermount3` | Restic FUSE mount for backup browsing | `fusermount3` → `fusermount` | — |
| `ssh` | Remote scripting (onpremise orchestrator) | system `openssh-client` | — |

**Embedded fallback:** For `mariadb`/`mysql`, `mariadb-dump`/`mysqldump`, `mariadb-binlog`/`mysqlbinlog`, `mydumper`, `myloader`, and `gotty-client`, replication-manager includes compiled-in versions under `<share>/<arch>/<os>/`. These are used automatically when no system binary is found — no installation required for basic operation.

### 2.1.11.3.2 Version Monitoring

replication-manager actively tracks tool versions at runtime. On every monitoring cycle it runs `--version` on each configured tool and logs any version change as `discovered` or `changed`. If a tool is missing or its version cannot be parsed, the corresponding `WARN` state is raised:

| State | Tool |
|---|---|
| `WARN0117` | Database client (`mariadb` / `mysql`) not found or version unreadable |
| `WARN0118` | Dump client (`mariadb-dump` / `mysqldump`) not found or version unreadable |
| `WARN0119` | Binlog client (`mariadb-binlog` / `mysqlbinlog`) not found or version unreadable |
| `WARN0120` | MyDumper not found or version unreadable |
| `WARN0121` | Restic not found or version unreadable |
| `WARN0167` | Sysbench not found or version unreadable |

These warnings appear in the cluster dashboard and clear automatically when the tool becomes available.

---

## 2.1.11.4 Per-Feature Dependencies

### 2.1.11.4.1 Logical Backups

```bash
# MariaDB client tools (provides mariadb, mariadb-dump, mariadb-binlog)
apt-get install mariadb-client      # Debian/Ubuntu
yum install MariaDB-client          # RHEL/CentOS

# Or MySQL client tools
apt-get install mysql-client
```

### 2.1.11.4.2 Fast Parallel Backups (MyDumper)

```bash
# Debian/Ubuntu — from MariaDB repository or GitHub releases
apt-get install mydumper

# Or download from https://github.com/mydumper/mydumper/releases
```

### 2.1.11.4.3 Snapshot Backups (Restic)

```bash
apt-get install restic      # Debian/Ubuntu
yum install restic          # RHEL/CentOS
# Or: https://restic.net/
```

Configure Restic in the cluster TOML:

```toml
backup-restic               = true
backup-restic-binary-path   = "/usr/bin/restic"
backup-restic-repository    = "/var/lib/replication-manager/backup/restic"
backup-restic-password      = "your-repo-password"
```

### 2.1.11.4.4 HAProxy

```bash
apt-get install haproxy     # Debian/Ubuntu
yum install haproxy         # RHEL/CentOS
```

```toml
haproxy-binary-path = "/usr/sbin/haproxy"
```

### 2.1.11.4.5 ProxySQL

Download from [github.com/sysown/proxysql/releases](https://github.com/sysown/proxysql/releases):

```bash
# Debian/Ubuntu example
curl -LO https://github.com/sysown/proxysql/releases/download/v2.7.3/proxysql_2.7.3-debian12_amd64.deb
dpkg -i proxysql_2.7.3-debian12_amd64.deb
```

```toml
proxysql-binary-path = "/usr/bin/proxysql"
```

### 2.1.11.4.6 Sysbench (Benchmarking)

```bash
apt-get install sysbench    # Debian/Ubuntu
yum install sysbench        # RHEL/CentOS
```

```toml
sysbench-binary-path = "/usr/bin/sysbench"
```

### 2.1.11.4.7 Restic FUSE Mount (Backup Browsing)

```bash
apt-get install fuse        # provides fusermount / fusermount3
```

### 2.1.11.4.8 Remote Scripting (On-Premise Orchestrator)

```bash
apt-get install openssh-client
```

---

## 2.1.11.5 Docker Image Reference

The **pro** Docker image (`signal18/replication-manager:3.1-pro`) bundles all of the above. Use it as a reference for what a fully-equipped deployment looks like:

```
mariadb-client 11.4  (mariadb, mariadb-dump, mariadb-binlog, mariadb-plugin-spider)
mydumper 0.17 + myloader
restic (latest apt)
haproxy (distro)
proxysql 2.7.3
sysbench (distro)
grafana 8.1
gotty-client 1.10
openssh-client
fuse (fusermount3)
```

The **standard** image (`signal18/replication-manager:3.1`) installs none of these — it relies on the embedded fallback binaries for basic SQL client operations and `prov-orchestrator = "onpremise"` for any provisioning needs.

---

## 2.1.11.6 Overriding Detected Paths

If a tool is installed in a non-standard location, or you want to pin a specific version, set the path explicitly in the cluster TOML:

```toml
[mycluster]
backup-mysqlclient-path   = "/opt/mariadb/bin/mariadb"
backup-mysqldump-path     = "/opt/mariadb/bin/mariadb-dump"
backup-mysqlbinlog-path   = "/opt/mariadb/bin/mariadb-binlog"
backup-mydumper-path      = "/opt/mydumper/bin/mydumper"
backup-myloader-path      = "/opt/mydumper/bin/myloader"
backup-restic-binary-path = "/opt/restic/restic"
haproxy-binary-path       = "/opt/haproxy/sbin/haproxy"
proxysql-binary-path      = "/opt/proxysql/bin/proxysql"
sysbench-binary-path      = "/opt/sysbench/bin/sysbench"
```

When an explicit path is set, auto-detection is bypassed for that tool.
