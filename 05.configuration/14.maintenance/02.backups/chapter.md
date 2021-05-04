---
title: Backups
taxonomy:
    category: Maintenance
---

Supported backups methods are mysqldump, mydumper, xtrabackup, mariabackup

A physical and a logical backup can be schedule via configuration variables.

**replication-manager** store the last backup into a data directory of replication-manager under: <datadir>/backups/<cluster>/<host_port>/
From **replication-manager 2.1** it is possible to store backups using s3 protocol and backups are available via fuse mounting directory <datadir>/s3/<cluster>/<host_port>/

In **replication-manager 2.1 **>, backups can be archive and purge via interaction with the Restic binaries (need pre install) that interconnect to an s3 protocol bucket, Restic enable block level encryption & deduplication and will store the entire backup directory for mixing multiple files backups like mydumper.        



##  Backup streaming for archive

When backup are executed they are sore compressed under the replication-manager <data_directory>/<cluster_name>/<server_name>_<server_port>/bck. The can be used to provision new nodes or restore broken slaves, but long archiving may be required. A full restic integration is used for this.   



##### `backup-logical-type` (2.1)    

| Item | Value |
| ---- | ----- |
| Description | river|mysqldump|mydumper  |
| Type | string |
| Default Value | "mysqldump" |

mysqldump only so far

##### `backup-physical-type` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  xtrabackup|mariabackup   |
| Type | string |
| Default Value | "xtrabackup" |


##### `backup-mysqldump-options` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  MySQLDump Extra Options  |
| Type | string |
| Default Value | "--hex-blob --single-transaction --verbose --all-databases --add-drop-database --system=all" |

When using MySQL Flavor or old MariaDB version it may needed to remove  system=al option that is printing system tables as SQL like for  USERS, GRANTS , ROLES, SERVERS

##### `backup-lockddl` (2.2)    

| Item | Value |
| ---- | ----- |
| Description |  Use MariaDB backup stage   |
| Type | boolean |
| Default Value | true|


##### `backup-logical-load-threads` (2.1)    

| Item | Value |
| ---- | ----- |
| Description | MyLoader can benefite multi threads for restore  |
| Type | Interger |
| Default Value | 2|


##### `backup-logical-dump-threads` (2.1)    

| Item | Value |
| ---- | ----- |
| Description | MyDumper and MySQLDump  can benefite multi threads for dumping  |
| Type | Interger |
| Default Value | 2|

This is not advice to dump with to many threads as it can consume a lot of ressource when backup happen on leader, the default and advice way of backing up a db  

##### `backup-mydumper-path` (2.1)  

| Item | Value |
| ---- | ----- |
| Description |  Path to mydumper binary |
| Type | string |
| Default Value | "/usr/bin/mydumper" |

##### `backup-myloader-path` (2.1)  

| Item | Value |
| ---- | ----- |
| Description | Path to myloader binary  |
| Type | string |
| Default Value | "/usr/bin/myloader" |


##### `backup-mysqldump-path` (2.1)  

| Item | Value |
| ---- | ----- |
| Description | Path to mysqldump binary |
| Type | string |
| Default Value | "" |

When empty a default binary is used provided in share directory.

It can be old and it's advice to install a database package similar or most up to date version to your database version.


##### `backup-mysqlclient-path` (2.1)  

| Item | Value |
| ---- | ----- |
| Description | Path to mysql client binary |
| Type | string |
| Default Value | "" |

To restore mysqldump "replicaction-manager" pipe the SQL to the mysql client

When empty a default binary is used provided in share directory

##### `db-servers-backup-hosts` (2.1)  

| Item | Value |
| ---- | ----- |
| Description | Database list of hosts to backup when set can backup a slave |
| Type | list  |
| Default Value | "" |

Use the same string as in db-servers-hosts
