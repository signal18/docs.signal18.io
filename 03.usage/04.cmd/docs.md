---
title: CMD Client Usage
---

## CMD Client Usage

#### Command line switchover

Trigger replication-manager client to perform a switchover

`replication-manager-cli switchover --cluster=test_cluster`

#### Command line failover

Trigger replication-manager in non-interactive to perform a failover ,

`replication-manager-cli failover --cluster="test_cluster"`

#### Command line replication bootstrap

With some already exiting database nodes but no replication setup in place replication-manager enable you to init the replication on various topology
master-slave | master-slave-no-gtid | maxscale-binlog | multi-master | multi-tier-slave

`replication-manager-cli --cluster="cluster_test_3_nodes" bootstrap --clean-all --topology="multi-tier-slave"`

#### Command line cluster status

`replication-manager-cli --cluster="cluster_test_3_nodes" status
