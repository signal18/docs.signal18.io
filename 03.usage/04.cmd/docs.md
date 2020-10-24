---
title: CMD Client Usage
taxonomy:
    category: docs
---

## CMD Client Usage

#### Command line switchover

Trigger replication-manager client to perform a switchover

`replication-manager-cli switchover --cluster=test_cluster`

#### Command line failover

Trigger replication-manager in non-interactive to perform a failover ,

`replication-manager-cli failover --cluster="test_cluster"`

#### Command line replication bootstrap

With some already existing database nodes but no replication setup in place, replication-manager enables you to initialize the replication on various topologies:
* master-slave
* master-slave-no-gtid
* maxscale-binlog
* multi-master
* multi-tier-slave

`replication-manager-cli --cluster="cluster_test_3_nodes" bootstrap --clean-all --topology="multi-tier-slave"`

#### Command line cluster status

`replication-manager-cli --cluster="cluster_test_3_nodes" status`

#### Command line database in maintenance

`replication-manager-cli server --id=9624235790336213315 --maintenance`

`replication-manager-cli topology`

```
| Group: cluster_haproxy_masterslaveslave |  Mode: Manual
                 Id            Host   Port          Status   Failures   Using GTID         Current GTID           Slave GTID             Replication Health  Delay  RO
5641630519400684578       127.0.0.1   3317          Master          0           No          0-3317-3124                                                          0 OFF
9624235790336213315       127.0.0.1   3318     Maintenance          0    Slave_Pos          0-3317-3124          0-3317-3124                                     0  ON
3944708846436490796       127.0.0.1   3319           Slave          0    Slave_Pos          0-3317-3124          0-3317-3124                                     0  ON
```
