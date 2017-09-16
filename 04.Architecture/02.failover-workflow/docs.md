---
title: Failover WorkFlow
---

### Failover

**replication-manager** is checking all master failure N times and all false positive checks pass,  the default behavior is to send alerts and put itself in waiting mode until a user force the failover or that the master self-heals.

This default is know as the On-call mode and configured `failover-mode = "manual"`

Failover can be resume via the console, the web server in default port http://replication-manger-host:1001/ the API or the command line client.

Conditions for a possible failover are constantly monitored.

- [x] A slave need to be available and up and running.
- [x] The cluster configuration need to be valid.

Some user define failover replication constraints can be added in the configuration file.

- [x] Exceeding a given replication delay `failover-max-slave-delay`
- [x] Time interval between failover `failover-time-limit`        
- [x] Number of Failover can be limited `failover-limit`
- [x] Synchronous state of the replication is lost `failover-at-sync`


We strongly advised to set following best practice in automatic failover:
```
failover-limit = 3
failover-time-limit = 10
failover-at-sync = false
failover-max-slave-delay = 30
```

For minimizing data lost in automatic failover:
```
failover-at-sync = true
failover-max-slave-delay = 0
```

A user can still force  failover by ignoring replication checks via:
```
check-replication-state = false
```

This can be dynamically changed via the HTTP interface "Replication Checks Change" button.

Per default Semi-Sync replication status is not checked during failover, but this check can be enforced with semi-sync replication to enable to preserve OLD LEADER recovery at all costs, and do not failover if none of the slaves are in SYNC status.

A user can change the sync check based on what is reported by SLA in sync, and decide that most of the time the replication is in sync and when it's not, that the failover should be manual. Via http console, use "Failover Sync" button


All cluster down lead to some situation where it is possible to first restart a slave previously stopped before the entire cluster was shutdown, failover in such situation can promote a delayed slave by a big amount of time and lead to as much time data lost, by default **replication-manager**  prevent such failover for the first slave unless you change failover-restart-unsafe to true. When using the default it is advise to start the old master first if not replication-manager will wait for the old master to show up again until it can failover again.   

Previous scenario is not that frequent and one can flavor availability in case the master never show up again. The DC crash would have bring down all the nodes around the same time. So data lost can be mitigated if you automate starting a slave node and failover on it via failover-restart-unsafe=true if the master can't or takes to long to recover from the crash.  

![failover](/doc/failover.png)
