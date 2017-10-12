---
title: Filesystem Hierarchy
taxonomy:
    category: docs
---

### Packages hierarchy

Packages installation will deploy a set of directories and files

  * `/etc/replication-manager/`
    Default and example conf file

  * `/usr/share/replication-manager`
    Static files, templates haproxy and graphite services

    The http server root directory:
    `/usr/share/replication-manager/dashboard`
    The files used for non-regression testing, and example mysql configuration files:
    `/usr/share/replication-manager/tests`

  * `/var/lib/replication-manager`
    A data directory used to bootstrap proxies and MariaDB local instances for regression tests, to backup binary logs, to store metrics

Logs can be found in `/var/log/replication-manager.log`.


####  Archive (tar.gz) hierarchy

* `/usr/local/replication-manager/etc`
  Default and example conf file

* `/usr/local/replication-manager/share`
  Static files, templates haproxy and graphite services

  The http server root directory:
  `/usr/local/replication-manager/share/dashboard`
  The files used for non-regression testing, and example mysql configuration files:
  `/usr/share/replication-manager/tests`

* `/usr/local/replication-manager/data`
  A data directory used to bootstrap proxies and MariaDB local instances for regression tests, to backup binary logs, to store metrics.
