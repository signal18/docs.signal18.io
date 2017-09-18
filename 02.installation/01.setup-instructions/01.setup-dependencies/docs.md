---
title: Extra Dependencies
taxonomy:
    category: docs
---
## Extra Dependencies

#### Version requirements

**replication-manager** is a self-contained binary, no extra system libraries are needed at the operating system level.

Database replication is advice to use GTID and version

- [x] MariaDB Version >= 10
- [x] MySQL Version >= 5.6

Web browser IE is reported not working with http interface.

####  Extra Dependencies

If you plan to use **replication-manager-tst** for automatic bootstrap of some local cluster un run some tests MariaDB-Server 10.2 package need be install.

- [x] Localhost regression testing  
- [x] MariaDBShardProxy

- [x] HaProxy package need to be install to benefit from haproxy  
- [x] Sysbench package are used for some non regression tests
- [x] MariaDB or MySQL packages

**replication-manager-tst** can be setup with following configuration options

```
mariadb-binary-path = "/usr/sbin"
haproxy-binary-path = "/usr/sbin/haproxy"
