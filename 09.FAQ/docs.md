
## Expire_log_days

Rejoining slaves during switchover after long write inactivity can fail when using expire_log_days    
https://jira.mariadb.org/browse/MDEV-10869

Rejoin Slaves on switchover could potentially fail when writing with SUPER PRIVILEGES on MariaDB
Unlike MySQL that is protected against writes with SUPER READ ONLY MariaDB does not have such concept
https://jira.mariadb.org/browse/MDEV-9458
So write protection is delegate to routing proxies, Proxysql , Maxscale will not enable write on a READ ONLY SLAVE and with external scripts replication change route after the log applied under protection of old master flush table with read lock , FTWL on old master can pile up with SUPER PRIVILEGES during the switch and de-queue after we release the LOCK . We don't observe such issue under regular load as we decrease max connections

## MySQL GTID with autoCommit=0 & super_read_only

https://dev.mysql.com/doc/relnotes/mysql/5.7/en/news-5-7-25.html#mysqld-5-7-25-bug
https://dev.mysql.com/doc/relnotes/mysql/8.0/en/news-8-0-14.html#mysqld-8-0-14-bug

Replication: If autocommit was set to 0 for a replication slave or Group Replication group member where GTIDs were in use and super_read_only=ON was set, server shutdown was prevented by a transaction that did not complete. The transaction was attempting to save GTIDs to the mysql.gtid_executed table, but the update failed because super_read_only=ON was set. (With autocommit set to 1, the transaction would complete in this situation, and the mysql.gtid_executed table would instead be updated at server startup.) Now, the check for the super_read_only setting is skipped for this task, so the transaction is able to save the GTIDs to the mysql.gtid_executed table and complete regardless of the combination of super_read_only and autocommit settings. (Bug #28183718)

Special can do for No Yun Ho that investigate this issue!
