---
title: Multi Source
taxonomy:
    category: docs
---
| Support Status  | Test Case |  
| ----------------|-----------|
| Experimental    | 0 |       

**replication-manager** supports MariADB multi source replication, one can setup a source to be monitored and create multiple clusters with the same servers to monitor multiple channels.

The code have been tested around MariaDB server but with MySQL and Percona in mind and can be adapted  for both. We are waiting for more feadbacks and test cases to declare the feature mature.  

##### `replication-source-name` (2.0), `master-connection` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Replication channel name to use for multisource |
| Type | string |
| Default Value | "" |  
