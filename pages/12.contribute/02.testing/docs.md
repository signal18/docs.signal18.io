---
title: Testing
taxonomy:
    category: docs
---

### 10.2.0.1 Non-regression tests

**replication-manager** can play some predefined scenarios in a non-regression testing framework
The tests can be called via the command line client.

**replication-manager** is allowed to play tests only when setting the `test` variable in the predefined testing cluster in the configuration file or via using *replication-manager-tst** monitor binaries.
where this flag is always on.

You probably don't want to play tests on an existing cluster, so instead of that **replication-manager-tst** or **replication-manager-pro** can provision a testing cluster when the test is starting and unprovision it when the test finishes. Some client parameters can disable the auto provisioning.
If you want to get access to the running servers such as examine the database logs and more.

**replication-manager-tst** provisions a local host setup using different ports.
Because haproxy is supported on multiple OSes, it's the preferred way to perform local testing.   

```  
[Cluster_Test_2_Nodes]
title = "Cluster_Test_2_Nodes"
db-servers-hosts = "127.0.0.1:3310,127.0.0.1:3311"
db-servers-preferedd-master = "127.0.0.1:3310"
db-servers-credential = "root:xxxx"
db-servers-connect-timeout = 1
replication-credential = "root:xxxx"
haproxy = true
haproxy-binary-path = "/usr/sbin/haproxy"
haproxy-write-port=3303
haproxy-read-port=3304
test=true
mariadb-binary-path = "/usr/local/mysql/bin"
sysbench-binary-path = "/usr/sbin/sysbench"
sysbench-threads = 4
sysbench-time = 60
```  

Run the TST release with extra parameters not defined in configuration files as on OSX:

```
replication-manager-tst --config=etc/config.toml.sample.tst.masterslave-haproxy  monitor   --haproxy-binary-path=/usr/local/bin/haproxy --sysbench-binary-path=/usr/local/bin/sysbench --mariadb-binary-path = "/usr/local/mysql/bin"
```  


Some tests are requiring sysbench and haproxy, please refer to the Configuration Dependencies section to proceed further.

#### 10.2.0.1.1 Command line print all tests

```
./replication-manager-cli --config=/etc/replication-manager/mrm.cnf --config-group=cluster_test_2_nodes --show-tests=true test
INFO[2017-02-22T21:40:02+01:00] [testSwitchOverLongTransactionNoRplCheckNoSemiSync testSwitchOverLongQueryNoRplCheckNoSemiSync testSwitchOverLongTransactionWithoutCommitNoRplCheckNoSemiSync testSlaReplAllDelay testFailoverReplAllDelayInteractive testFailoverReplAllDelayAutoRejoinFlashback testSwitchoverReplAllDelay testSlaReplAllSlavesStopNoSemiSync testSwitchOverReadOnlyNoRplCheck testSwitchOverNoReadOnlyNoRplCheck testSwitchOver2TimesReplicationOkNoSemiSyncNoRplCheck testSwitchOver2TimesReplicationOkSemiSyncNoRplCheck testSwitchOverBackPreferedMasterNoRplCheckSemiSync testSwitchOverAllSlavesStopRplCheckNoSemiSync testSwitchOverAllSlavesStopNoSemiSyncNoRplCheck testSwitchOverAllSlavesDelayRplCheckNoSemiSync testSwitchOverAllSlavesDelayNoRplChecksNoSemiSync testFailOverAllSlavesDelayNoRplChecksNoSemiSync testFailOverAllSlavesDelayRplChecksNoSemiSync testFailOverNoRplChecksNoSemiSync testNumberFailOverLimitReach testFailOverTimeNotReach]
```
Command-line running some tests via passing a list of tests in run-tests
ALL is a special test to run all available tests.
```
./replication-manager-cli --config=/etc/replication-manager/cluster_test_2_nodes.cnf --cluster=cluster_test_2_nodes   --run-tests=testSwitchOver2TimesReplicationOkSemiSyncNoRplCheck test  

```

Test returns some JSON structure that get useful informations for debugging

PASS
FAIL
ERR
SKIP

#### 10.2.0.1.2 Test targets

A test can declare the setup it applies to. When the cluster does not match, the test does not run: its result is `SKIP`, and the log says why, for example `Skip testGroupReplicationPrimaryLoss: number-nodes-target 3, cluster has 2`. A test without targets runs on every cluster.

| Target | The cluster must have |
|---|---|
| topology-target | one of the listed topologies (`master-slave`, `multi-master-grprep`, ...), as declared in its configuration |
| number-nodes-target | at least this many database servers |
| flavor-target | one of the listed flavors: `mariadb`, `mysql`, `percona`, `postgresql` |
| orchestrator-target | one of the listed orchestrators. Only tests of orchestrator mechanics have one: a functional test runs whatever the orchestrator |
| dbu-target | at least this many DBU per database server |
| config-variable-target | each listed configuration variable at the given value, for example `topology-staging = true` |

So `ALL` can run on any cluster: a group replication test is skipped on a master-slave cluster, a staging test on a cluster without `topology-staging`.

A skipped test is not a tested setup: in test mode, the results sent to Cloud18 leave it out.
