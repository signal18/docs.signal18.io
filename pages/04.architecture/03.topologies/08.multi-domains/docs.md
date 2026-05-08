---
title: Multi Domains
taxonomy:
    category: docs
---
| Support Status  | Test Case |  
| ----------------|-----------|
| Stable      | 35 |       

**replication-manager** supports multi-domain GTID for both **MariaDB** and **MySQL**. The product can switchover and failover on topologies where each database node has a different domain ID (MariaDB) or uses multiple GTID sources (MySQL).

### MariaDB Multi-Domain GTID

MariaDB assigns a unique `gtid_domain_id` per node. replication-manager tracks all domain IDs and correctly handles failover and switchover across domains, ensuring GTID consistency.

### MySQL Multi-Source GTID

MySQL uses `server_uuid`-based GTID sets. replication-manager supports topologies where multiple sources contribute GTID transactions, including multi-source replication setups.

> **Note:** Writing on replicas is strongly discouraged. If writes on replicas are necessary, disable binary logging for those writes (`SET sql_log_bin=0`) to avoid GTID conflicts during failover.
