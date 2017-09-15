---
title: Filesystem Organization
taxonomy:
    category: docs
---

### Packages organization

Packages installation will deploy a set of directories

  - [x] /etc/replication-manager/
    Default and example conf file

  - [x] /usr/share/replication-manager
    Static files, templates haproxy and graphite services

    The root of http server
    /usr/share/replication-manager/dashboard
    The files used for non regression testing, example mysql conf files
    /usr/share/replication-manager/tests

  - [x] /var/lib/replication-manager
    A data directory used to bootstrap proxies and MariaDB local instances for reg tests, to backup binary logs, to store metrics

Log can be found in /var/log/replication-manager.log


####  tar.gz organization

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
