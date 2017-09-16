---
title: Sharding Configuration
---

#### MariaDBShardProxy

Since version 1.1 **replication-manager** can manage Spider proxy for schema and table Sharding.

This type of proxy preserve transaction consistency across shard group, transactions can be run against multiple shard clusters. Joins queries can be achieved inter shard.

This is done using Spider storage engine

**replication-manager** discovering the master tables on startup and maintain link to master during failover and switchover.   


For every cluster definition you wan't to proxy add extra MariaDBShardProxy configuration
```
mdbshardproxy = true
mdbshardproxy-servers = "127.0.0.1:3306"
mdbshardproxy-user = "root:mariadb"
```

We advice to give a path to  MariaDB 10.2 and above version if you would like replication-manager to launch a local MariaDBShardProxy.   
```  
mariadb-binary-path = "/usr/local/mysql/bin"
```  
This instance will use a default configuration file in
```  
/usr/share/tests/etc/mdbsproxy.cnf
```  

In local wrapper mode replication-manager never stop proxies to avoid disturbing the workload:)
