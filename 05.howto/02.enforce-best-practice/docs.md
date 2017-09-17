---
title: Enforce Best Practice
---

### Forcing best practices

**replication-manager** (1.1)  can dynamically enforce best database practices around the replication usage. It dynamically configure the MariaDB it does monitor. Note that such enforcement will be lost if replication manager monitoring is shutdown and the MariaDB restarted. The command line usage do not enforce but default config file do, so disable what may not be possible in your custom production setup.   

**replication-manager** (2.0) produce warnings if one of the possible enforcement is not setup in the database nodes

**replication-manager** ignore enforcement and warnings on the nodes excluded from election with using the ignore list.   

```
force-slave-heartbeat= true
force-slave-gtid-mode = true
force-slave-semisync = true
force-slave-readonly = true
force-binlog-row = true
force-binlog-annotate = true
force-binlog-slowqueries = true
force-inmemory-binlog-cache-size = true
force-disk-relaylog-size-limit = true
force-sync-binlog = true
force-sync-innodb = true
force-binlog-checksum = true
```

**replication-manager** default enforcement are `force-slave-readonly` and  `force-slave-heartbeat`

We advice to permanently set the variables inside your database node configuration, to disable most dynamic enforcement on the long run.
