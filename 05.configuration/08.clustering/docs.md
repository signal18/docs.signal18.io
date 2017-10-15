---
title: Clustering Configuration
---

### Active standby with external arbitrator

When inside a single DC, we can use a single replication-manager that performs failover using keepalived, corosync, openha or etcd, but if you run on 2 DC it is advised to run two replication-manager instances in the same infrastructure. Both instances will use a heartbeat mechanism via the http protocol.

Make sure you activate the web server of **replication-manager**.


To configure active standby and arbitration, use the following settings on each **replication-manager**:

##### `arbitration-external` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Enable external arbitration on split brain. |
| Type | Boolean |
| Default Value | false |  

##### `arbitration-external-hosts` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Hosts list of external arbitrator service. |
| Type | List |
| Default Value | "arbitrator-fr.signal18.io,arbitrator-us.signal18.io" |  

Define one secret arbitration-external-secret it should be unique across all users of replication-manager, it is use by the arbitrator to identify your cluster, organization name and random alpha-numeric are very welcome.

##### `arbitration-external-secret` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Full path to an alerting script. |
| Type | String |
| Default Value | "cluster01.signal18.io" |  


Give each **replication-manager** server a  different value for arbitration-external-unique-id and instruct it for the other **replication-manager** in the cluster.

##### `arbitration-external-id` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Unique value on each replication-manager. |
| Type | Integer |
| Default Value | 0 |  


##### `arbitration-peer-hosts` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Peer replication-manager node. |
| Type | String |
| Default Value | "replication-manager01:10002" |  



On each instance instruct it's peer replication-manager node

On instance "127.0.0.1:10001"
arbitration-peer-hosts ="127.0.0.1:10002"

On instance "127.0.0.1:10002"
arbitration-peer-hosts ="127.0.0.1:10001"    

Once done start the fist replication-manager.
```
INFO[2017-03-20T09:48:38+01:00] [cluster_test_2_nodes] ERROR :Get http://127.0.0.1:10001/heartbeat: dial tcp 127.0.0.1:10001: getsockopt: connection refused
INFO[2017-03-20T09:48:38+01:00] [cluster_test_2_nodes] INFO : Splitbrain     
INFO[2017-03-20T09:48:38+01:00] [cluster_test_3_nodes] CHECK: External Abitration
INFO[2017-03-20T09:48:38+01:00] [cluster_test_3_nodes] INFO :Arbitrator say winner
INFO[2017-03-20T09:48:40+01:00] [cluster_test_2_nodes] ERROR :Get http://127.0.0.1:10001/heartbeat: dial tcp 127.0.0.1:10001: getsockopt: connection refused
INFO[2017-03-20T09:48:40+01:00] [cluster_test_2_nodes] INFO : Splitbrain     
INFO[2017-03-20T09:48:40+01:00] [cluster_test_3_nodes] CHECK: External Abitration
INFO[2017-03-20T09:48:40+01:00] [cluster_test_3_nodes] INFO Arbitrator say :winner
```

The split brain detection is trigger because your are the first instance to start, the peer **replication-manager** is not joinable so it ask for an arbitration to arbitration-external-hosts where the arbitrator daemon is running.

The arbitrator will enable that node to enter Active Mode  

When you start the peer replication-manager server, the split brain is resolve and replication-manager will detect an other active instance is running so it will get the Standby status.

>failover in a **replication-manager** cluster is requesting an arbitration. If arbitrator can't be contacted, you can come back to normal command line mode to failover but make sure you stopped all other running replication-manager.

### Run a private arbitrator

Configuration should be enable via this configuration.

```
[arbitrator]
title = "arbitrator"  
db-servers-hosts = "192.168.0.201:3306"
db-servers-credential = "user:password"
[default]
```

Start it via

```
/usr/bin/replication-manager-arb arbitrator --arbitrator-port=80
```
