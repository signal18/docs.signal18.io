---
title: Replication Configuration
---
## Replication Configuration

**replication-manager**  to failover will be in charge of rebuilding the replication, following section explore how to proceed with it.

##### `replication-credential` (2.0), `rpluser` (0.6)

| Item | Value |
| ---- | ----- |
| Description | Replication is created with such credential, in the [user]:[password] format. |
| Type | string |
| Default Value | repl:repman |   


##### `replication-use-ssl` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Replication is created using SSL encryption to replicate from master. |
| Type | boolean |
| Default Value | false |   

##### `replication-master-connect-retry` (2.0), `master-connect-retry` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Replication is created using this connection retry timeout in second. |
| Type | integer |
| Default Value | 10 |   


## Topology Configuration

### Master Slaves Configuration

**replication-manager**  supports 2-node master slave setup, it is advice to use at least 3 nodes cluster to get the cluster tolerant to losing or stopping a slave.  


By default **replication-manager** assume a flat topology but can enable multi-tier topology with some additional setting, scenario is you stop a slave and his master die, when the master rejoin the topology it can keep his slave under him instead of switching the slave to the new master

##### `replication-multi-tier-slave` (2.0), `multi-tier-slave` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Enable relay slaves topology |
| Type | boolean |
| Default Value | false |   

## Multi Tier Slaves Configuration

##### `replication-multi-tier-slave` (2.0), `multi-tier-slave` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Enable relay slaves topology |
| Type | boolean |
| Default Value | false |   

**replication-manager**  have support for replication tree or relay slaves architecture, in case of master death one of the slaves under the relay is promoted as a master.

**replication-manager** does not manage yet the relay crash to replace it with a slave

## Multi Master Configuration

**replication-manager**  supports 2-node multi-master topology detection. It is required to declare it the configuration configuration

##### `replication-multi-master (2.0)`, `multimaster` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Enable Master-Master topology |
| Type | boolean |
| Default Value | false |  

You just need to set one preferred master

We advice to enable restart of database in read-only mode to cover the case where a failed node try to rejoin but can't be contacted anymore from **replication-manager**, in such case no write traffic will be enable on the rejoining node.In a Multi DC split brain with a proxy on each side we can simply ensure the split brain will not make the database diverge on each side    

MariaDB configuration file:  

```
read_only = 1
```

This flag ensures that in case of split brain + leader crash, when old leader is reintroduced it will not show up as a possible leader for WRITES.


MaxScale configuration file:  

Maxscale need to be instructed to monitor multi master, following setting track the read-only flag and route queries to the writable node.

```    
[Multi-Master Monitor]
type=monitor
module=mmmon
servers=server1,server2,server3
user=myuser
passwd=mypwd
detect_stale_master=true
```

## Multi Source or Channel Configuration

**replication-manager**  support monitoring of a specific replication channel, those feature are experimental and should be tested because they are not covered by QA

##### `replication-source-name` (2.0), `master-connection` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Replication channel name to use for multisource |
| Type | string |
| Default Value | "" |  
