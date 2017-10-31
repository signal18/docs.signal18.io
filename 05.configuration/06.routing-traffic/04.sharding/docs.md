---
title: Sharding Configuration
---

#### MariaDBShardProxy

Since version 1.1 **replication-manager** can manage Spider proxy for schema and table Sharding.

This type of linked table proxy preserve transaction consistency across shard group, transactions can be run against multiple shard clusters. Joins queries can be achieved inter shard.

This is done using Spider storage engine

**replication-manager** discovering the master tables on startup and maintain link to master during failover and switchover.   

For every cluster definition you wan't to proxy add extra MariaDBShardProxy configuration:


##### `shardproxy` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Enable Driving mdbshardproxy. |
| Type | boolean |
| Default Value | false |  

##### `shardproxy-servers` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Comma separated list of the mdbshardproxy hosts. |
| Type | String |
| Default Value | "127.0.0.1:3306" |  

##### `shardproxy-credential` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Credential to connect to mdbshardproxy. |
| Type | String |
| Default Value | "root:mariadb" |  



## Testing Configuration

Give a path to MariaDB 10.2  or Spiral Spider if you would like *replication-manager-tst* to bootstrap a local MariaDBShardProxy.   

##### `mariadb-binary-path` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Full path to mdbshardproxy binary. |
| Type | String |
| Default Value | "/usr/local/mariadb" |  

This instance will use a default configuration file in
```  
/usr/share/tests/etc/mdbsproxy.cnf
```  

In local wrapper mode replication-manager never stop proxies to avoid disturbing the workload:)
