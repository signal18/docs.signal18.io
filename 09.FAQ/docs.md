
Rejoining slaves during switchover after long write inactivity can fail when using expire_log_days    
https://jira.mariadb.org/browse/MDEV-10869

Rejoin Slaves on switchover could potentially fail when writing with SUPER PRIVILEGES on MariaDB
Unlike MySQL that is protected against writes with SUPER READ ONLY MariaDB does not have such concept
https://jira.mariadb.org/browse/MDEV-9458
So write protection is delegate to routing proxies, Proxysql , Maxscale will not enable write on a READ ONLY SLAVE and with external scripts replication change route after the log applied under protection of old master flush table with read lock , FTWL on old master can pile up with SUPER PRIVILEGES during the switch and de-queue after we release the LOCK . We don't observe such issue under regular load as we decrease max connections  
