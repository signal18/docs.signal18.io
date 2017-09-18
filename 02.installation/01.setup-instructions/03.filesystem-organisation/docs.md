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


- [x] /usr/local/replication-manager/ect
  Default and example conf file

- [x] /usr/local/replication-manager/share
  Static files, templates haproxy and graphite services

  The root of http server
  /usr/local/replication-manager/share/dashboard
  The files used for non regression testing, example mysql conf files
  /usr/share/replication-manager/tests

- [x] /usr/local/replication-manager/data
  A data directory used to bootstrap proxies and MariaDB local instances for reg tests, to backup binary logs, to store metrics
