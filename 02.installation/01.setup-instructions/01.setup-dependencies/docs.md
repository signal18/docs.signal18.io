---
title: Setup Dependencies
taxonomy:
    category: docs
---

### System requirements

**replication-manager** is a self-contained binary

No extra system libraries are needed at the operating system level.

Database replication is advice to use GTID and version

MariaDB Version >= 10

Web browser IE is reported not working with http interface.


### Downloads

As of today we build portable binary tarballs, Debian Jessie, Ubuntu, CentOS 6 & 7 packages.

Check https://github.com/signal18/replication-manager/releases for official releases.

Nightly builds available on https://orient.dragonscale.eu/replication-manager/nightly


####  Extra testing dependencies

MariaDB-Server package minimum 10.2 server need to be install if you plan to use following features
- [x] Automatic node rejoin
- [x] Non regression testing  
- [x] Binlog Backups
- [x] MariaDBShardProxy

HaProxy package need to be install to benefit from haproxy bootstrap mode
Sysbench package are used for some of the non regression tests

Can be setup according to following configuration options

```
mariadb-binary-path = "/usr/sbin"
haproxy-binary-path = "/usr/sbin/haproxy"
