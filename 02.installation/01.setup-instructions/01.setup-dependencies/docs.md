---
title: Setup Dependencies
taxonomy:
    category: docs
---

### System requirements

**replication-manager** is a self-contained binary, no extra system libraries are needed at the operating system level.

Database replication is advice to use GTID and version

MariaDB Version >= 10
MySQL Version >= 5.6

Web browser IE is reported not working with http interface.

####  Extra testing dependencies

If you plan to use **replication-manager-tst** for automatic bootstrap of some local cluster un run some tests MariaDB-Server 10.2 package need be install
- [x] Automatic node rejoin
- [x] Local non regression testing  
- [x] MariaDBShardProxy

HaProxy package need to be install to benefit from haproxy  
Sysbench package are used for some non regression tests

**replication-manager-tst** can be setup with following configuration options

```
mariadb-binary-path = "/usr/sbin"
haproxy-binary-path = "/usr/sbin/haproxy"
