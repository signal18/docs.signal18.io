---
title: Enforce Best Practices
taxonomy:
    category: docs
---

### Enforcing best practices

**replication-manager** (1.1) can dynamically enforce best database practices around the replication usage.

It can dynamically configure the server it monitors via SET GLOBAL VARIABLES when it is possible to do so without database restart.

> Enforcement can be lost if replication-manager monitoring is shutdown and the database is restarted.

**replication-manager** (2.0) produces warnings if one of the possible practices is not found in the database nodes and not dynamically enforced. Some non-tunable warnings are produced for:

* missing log-slave-update directive
* non-strict GTID mode  

**replication-manager** ignores enforcement and warnings on the nodes excluded from election using the ignore list.   


* force-slave-heartbeat
* force-slave-gtid-mode
* force-slave-semisync
* force-slave-readonly
* force-binlog-row
* force-binlog-annotate
* force-binlog-slowqueries
* force-inmemory-binlog-cache-size
* force-disk-relaylog-size-limit
* force-sync-binlog
* force-sync-innodb
* force-binlog-checksum


**replication-manager** default enforcements are `force-slave-readonly` and `force-slave-heartbeat`

We advise to permanently set the variables inside your database node configuration, to disable most dynamic enforcements on the long run.
