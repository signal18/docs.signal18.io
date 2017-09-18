---
title: Enforce Best Practice
---

### Forcing best practices

**replication-manager** (1.1) can dynamically enforce best database practices around the replication usage.

It can dynamically configure the MariaDB it monitor via SET GLOBAL VARIABLES when it is possibel to do so without database restart.

>Enforcement can be lost if replication manager monitoring is shutdown and the database is restarted.

**replication-manager** (2.0) produce warnings if one of the possible enforcement is not found in the database nodes and not dynamically enforced. Some non tunable warnings are produced for  

- [x] missing log-slave-updates  
- [x] non GTID strict mode  

**replication-manager** ignore enforcement and warnings on the nodes excluded from election with using the ignore list.   


- [x] force-slave-heartbeat
- [x] force-slave-gtid-mode
- [x] force-slave-semisync
- [x] force-slave-readonly
- [x] force-binlog-row
- [x] force-binlog-annotate
- [x] force-binlog-slowqueries
- [x] force-inmemory-binlog-cache-size
- [x] force-disk-relaylog-size-limit
- [x] force-sync-binlog
- [x] force-sync-innodb
- [x] force-binlog-checksum


**replication-manager** default enforcement are `force-slave-readonly` and  `force-slave-heartbeat`

We advice to permanently set the variables inside your database node configuration, to disable most dynamic enforcement on the long run.
