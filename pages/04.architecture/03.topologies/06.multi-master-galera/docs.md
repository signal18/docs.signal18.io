---
title: Multi Master Galera
taxonomy:
    category: docs
---
| Support Status  | Test Case |  
| ----------------|-----------|
| Experimental      | 0 |       

##### `replication-multi-master-wsrep (2.0)`

| Item | Value |
| ---- | ----- |
| Description | Enable Multi Master Galera  |
| Type | boolean |
| Default Value | false |  


Galera cluster is not always adapted for a Write to all node architecture, for those reasons:

- It do not support serialized isolation level, READ LOCKS acquire no cluster locks and can only be validated for REPEATABLE READ or SERIALIZED on the local node the transaction is executed.   

- It increase deadlock probability, while transactions are replicated in concurrency it's style take time to round trip the network, certification only apply on checkpointing, so the more nodes the more latency the more deadlock the workload may be exposed.

For those reasons your application as to be modify to write critical sections on a leader Galera node.

**replication-manager** can help you to get the best of Galera combine with layer7 proxy like MaxScale or ProxySQL.

**replication-manager** will elect a virtual master and failover or switchover it based on each Galera node status, if one node get excluded for any reason of that cluster.

**replication-manager** could help to get 2 node Galera Cluster with one **replication-manager** on each node and a **replication-manager-arb** to manage active-passive role on each.
The active **replication-manager**  can be use to start garbd and enable the cluster to survive.

#### Losing the virtual master

Available from replication-manager 3.1.49.

As long as a majority of the nodes is up, they keep a Primary component and every one of them takes writes. The virtual master is only the node the proxies send the writes to, so moving it puts no data at risk.

- **The virtual master moves by itself, in any failover mode, including manual.** As soon as the Primary component has a synced node, **replication-manager** moves the virtual master there and the proxies follow. No operator action is needed, and `failover-limit` and `failover-time-limit` do not apply to Galera. Master-slave clusters keep all of these.
- With no synced node in a Primary component there is no majority, and the virtual master does not move.
- When a cluster has no virtual master at all, one is elected and the warning **WARN0006** is raised.

#### Restarting a cluster entirely down

Available from replication-manager 3.1.49.

Galera only lets the last node that left start a new cluster (`safe_to_bootstrap: 1`), and refuses any other. **replication-manager** bootstraps the last node it saw alive, not the old master.

If several nodes were lost at the same moment, the last one is not known: the warning **WARN0317** is raised and the master is tried. If every node stopped abruptly, all of them are at `safe_to_bootstrap: 0` and an operator has to choose the node.

On OpenSVC v3, the database of a Galera node starts only once the node's name resolves, up to 30 seconds after its network is up, so the other nodes can reach it for its state transfer. This uses `blocking_pre_start` on `container#db`. If you set your own `blocking_pre_start` there, it is left alone and the node does not get this wait.

#### Supported versions and SST authentication

When **replication-manager** provisions the Galera cluster (an orchestrator renders the database configuration, not on premise), the root password is never written in that configuration:

- The SST (State Snapshot Transfer) authenticates the account `mysql@localhost` through `unix_socket`, with no password: `wsrep_sst_auth=mysql:`. The donor's mariabackup runs as the `mysql` OS user, so the socket authentication matches.
- The account is created when the first node's datadir is initialized, before any node joins, and **replication-manager** keeps it present on running clusters. While it cannot be created, the security ERROR **ERR00114** is open.
- **MariaDB 10.4 or later is required.** `unix_socket` is built in from 10.4. **MariaDB 10.3 and older are no longer supported** on a provisioned Galera topology: each such server raises the ERROR **ERR00115** until it is upgraded.
- MySQL and Percona keep their `wsrep_sst_auth` until the xtrabackup SST over `auth_socket` is validated.

On premise, **replication-manager** renders no configuration and these rules do not apply.
