---
title: Multi Domains
taxonomy:
    category: docs
---
| Support Status  | Test Case |  
| ----------------|-----------|
| Stable      | 35 |       

**replication-manager** supports multi-domain GTID for both **MariaDB** and **MySQL**. The product can switchover and failover on topologies where each database node has a different domain ID (MariaDB) or uses multiple GTID channels and tags (MySQL).

### MariaDB Multi-Domain GTID

MariaDB assigns a unique `gtid_domain_id` per node. replication-manager tracks all domain IDs and correctly handles failover and switchover across domains, ensuring GTID consistency.

### MySQL Multi-Channel Replication

MySQL uses **replication channels** to manage multiple replication sources. Each channel maintains its own GTID set and relay log. replication-manager supports multi-channel topologies and correctly handles failover and switchover across channels.

### MySQL GTID Tags (8.4+)

MySQL 8.4 introduced **GTID tags** — a label added to the GTID format (`uuid:tag:number`) that allows grouping transactions by origin, similar to MariaDB's `gtid_domain_id`. replication-manager supports GTID-tagged topologies and correctly handles failover and switchover when multiple tags are present.

> **Note:** Writing on replicas is strongly discouraged. If writes on replicas are necessary, disable binary logging for those writes (`SET sql_log_bin=0`) to avoid GTID conflicts during failover.
