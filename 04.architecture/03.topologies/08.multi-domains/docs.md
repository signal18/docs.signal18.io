---
title: Multi Domains
---
| Support Status  | Test Case |  
| ----------------|-----------|
| Alpha      | 35 |       

**replication-manager** supports MariaDB multi domain GTID, the product can switchover and failover on topology where each database node have a different domain id.

Writing on slaves is strongly not advice, it can be done but with caution to disable binary login on such WRITES.
