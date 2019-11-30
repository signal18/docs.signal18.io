---
title: Replication Best Practices
---

## Replication best practices

### Parallel replication

The history of MariaDB replication has reached a point where replication can almost in any case catch up with the master. It can be ensured using new features like Group Commit improvement, optimistic in-order parallel replication and semi-synchronous replication.


MariaDB 10.1 settings for in-order optimistic parallel replication:

```
slave_parallel_mode = optimistic  
slave_domain_parallel_threads = %%ENV:CORES%%  
slave_parallel_threads = %%ENV:CORES%%  
expire_logs_days = 5  
sync_binlog = 1  
log_slave_updates = ON
```

### Semi-synchronous replication

Semi-synchronous replication enables to delay transaction commit until the transactional event reaches at least one replica. The "In Sync" status will be lost only when a tunable replication delay is attained. This Sync status is checked by __replication-manager__ to compute the last SLA metrics, the time we may auto-failover without losing data and when we can reintroduce the dead leader without re-provisioning it.


The MariaDB recommended settings for semi-sync:

```
plugin_load = "semisync_master.so;semisync_slave.so"  
rpl_semi_sync_master = ON  
rpl_semi_sync_slave = ON  
loose_rpl_semi_sync_master_enabled = ON  
loose_rpl_semi_sync_slave_enabled = ON
rpl_semi_sync_master_timeout = 10
```

Such parameters will print an expected warning in error.log on slaves about SemiSyncMaster Status switched OFF.


>__Important Note__: semisync SYNC status does not guarantee that the old leader is replication consistent with the cluster in case of crash [MDEV-11855](https://jira.mariadb.org/browse/MDEV-11855) or shutdown [MDEV-11853](https://jira.mariadb.org/browse/MDEV-11853) of the master,the failure can leave more data in the binary log but it guarantees that no client applications have seen those pending transactions if they have not reach a replica.


>This leads to a situation where semisync is used to slowdown the workload to the speed of the network until it reaches a timeout where it is not possible to catch up anymore. A crash or shutdown will lead to the requirement of re-provisioning the old leader from another node in most heavy write scenarios.  


>Setting rpl_semi_sync_master_wait_point to AFTER_SYNC may limit the number of extra transactions inside the binlog after a crash but those transactions would have been made visible to the clients and may have been lost during failover to an other node. It is highly recommended to keep AFTER_COMMIT to make sure the workload is safer.
