---
title: Master Master
---
| Support Status  | Test Case |  
| ----------------|-----------|
| Production      | 1 |       


**replication-manager** supports 2-node multi-master topology detection. It is required to declare it explicitely in the configuration.

##### `replication-multi-master (2.0)`, `multimaster` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Enable Master-Master topology |
| Type | boolean |
| Default Value | false |  

You just need to set one preferred master.

We advise to enable restart of database in read-only mode to cover the case where a failed node tries to rejoin but can't be contacted anymore from **replication-manager**, in such case no write traffic will be enabled on the rejoining node. In a Multi DC split brain with a proxy on each side, we can simply ensure the split brain will not make the database diverge on each side.    

MariaDB configuration file:  

```
read_only = 1
```

This flag ensures that in case of split brain + leader crash, when old leader is reintroduced it will not show up as a possible leader for WRITES.


MaxScale configuration file:  

Maxscale needs to be instructed to monitor multi-master, the following settings tracks the read-only flag and routes queries to the writable node.

```    
[Multi-Master Monitor]
type=monitor
module=mmmon
servers=server1,server2,server3
user=myuser
passwd=mypwd
detect_stale_master=true
```
