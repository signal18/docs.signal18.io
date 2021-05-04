---
title: Binlogs
taxonomy:
    category: maintenance
---

**replication-manager (2.1)** can backup binlogs it is monitoring binlogs and on rotation will copy the previous binlog   

##### `backup-binlogs` (2.1)  

| Item | Value |
| ---- | ----- |
| Description | Archive binlogs |
| Type | Boolean |
| Default Value | false |

##### `backup-mysqlbinlog-path` (2.1)  

| Item | Value |
| ---- | ----- |
| Description | Path to mysqlbinlog binary |
| Type | string |
| Default Value | "" |

When empty a default binary is used provided in share directory.

It can be old and it's advice to install a database package similar or most up to date version to your database version.

##### `backup-binlogs-keep` (2.1)  

| Item | Value |
| ---- | ----- |
| Description |  |
| Type | Integer |
| Default Value | 10|
