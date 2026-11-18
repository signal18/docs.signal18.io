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

* **replication-manager-cli** (from 3.1.52): the dbjobs script makes every call to replication-manager and every data stream through it (config delivery, backup streaming, script upgrades, log shipping, jobs-check). You install nothing:
  * on OpenSVC, the service bootstrap downloads it from replication-manager;
  * on Kubernetes, the init container copies it next to the script;
  * on on-premise hosts run over SSH, replication-manager copies it into the jobs directory before each run, when it is absent or another version. The host must have replication-manager's own OS and architecture. On any other host, install the CLI of that platform by hand on the jobs user's `PATH`.

  Neither `socat` nor `openssl` is needed any more.
* **socat** (before 3.1.52 only): older dbjobs scripts stream through `socat`. Verify with `which socat` or install it via `apt install socat` (Debian/Ubuntu) or `yum install socat` (RHEL/CentOS).
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
