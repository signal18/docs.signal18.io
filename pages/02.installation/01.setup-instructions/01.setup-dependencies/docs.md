---
title: Extra Dependencies
published: false
taxonomy:
    category: docs
---
## 2.1.3.1 Extra Dependencies

#### 2.1.3.1.0.1 Version requirements

**replication-manager** is a self-contained binary, no extra system libraries are needed at the operating system level.

It is advised to use GTID for replication and following database versions:

* MariaDB Version >= 10.0
* MySQL Version >= 5.6

Internet Explorer Web browser is reported as not functioning with the web gui.

#### 2.1.3.1.0.2  Database Host Dependencies

The following packages are required **on each database host** (or inside each database container) for the dbjobs maintenance script to function:

* **socat** — required for all data streaming between database hosts and replication-manager (config delivery, backup streaming, script upgrades, log shipping, jobs-check). Without socat, dbjobs cannot communicate with replication-manager. Included as a dependency of MariaDB Server packages (used by Galera SST). Verify with `which socat` or install via `apt install socat` (Debian/Ubuntu) or `yum install socat` (RHEL/CentOS). Docker images include socat by default.
* **bash** — the dbjobs script requires bash (not sh/dash)
* **MariaDB or MySQL client** — `mariadb` / `mysql` CLI for SQL job execution

#### 2.1.3.1.0.3  Extra Dependencies

If you plan to use **prov-orchestrator = "local" or prov-orchestrator = "onpremise"** for automatic bootstrap of a local cluster and run some tests, Minimal MariaDB Server 10.2 packages need to be installed.

* Localhost regression testing  
* MariaDBShardProxy is a regular MariaDB Server that support spider
* HAProxy packages need to be installed to benefit from haproxy
* Sysbench package is used for some non-regression tests
* MariaDB or MySQL server packages
* Restic for archiving backups  

A deployment can be customized with changing configuration options to match binaries path
Please use replication-manager monitor --help to check all available options:

```
mariadb-binary-path = "/usr/sbin"
haproxy-binary-path = "/usr/sbin/haproxy"
```
