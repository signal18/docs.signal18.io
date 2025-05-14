---
title: Extra Dependencies
taxonomy:
    category: docs
---
## Extra Dependencies

#### Version requirements

**replication-manager** is a self-contained binary, no extra system libraries are needed at the operating system level.

It is advised to use GTID for replication and following database versions:

* MariaDB Version >= 10.0
* MySQL Version >= 5.6

Internet Explorer Web browser is reported as not functioning with the web gui.

####  Extra Dependencies

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
