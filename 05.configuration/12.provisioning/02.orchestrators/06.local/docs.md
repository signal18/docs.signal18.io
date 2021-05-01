---
title: Orchestration Local
taxonomy: Orchestrator
    category: Orchestrator
---


##### `prov-db-binary-basedir` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Path to mysqld mariadbd or mysql_instal_db binary |
| Type | String |
| Default | "/usr/local/mysql/bin"  |
| Example | "/usr/local/mysql/bin" |

##### `db-servers-binary-path` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Deprecated for prov-db-binary-basedir |
| Type | String |
| Default | "/usr/local/mysql/bin"  |
| Example | "/usr/local/mysql/bin" |


##### `haproxy-binary-path` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Path to HaProxy |
| Type | String |
| Default | "/usr/sbin/haproxy"  |
| Example | "/usr/local/bin/haproxy" |

##### `proxysql-binary-path` (2.1)
| Item | Value |
| ---- | ----- |
| Description | Path to ProxySQL |
| Type | String |
| Default | "/usr/sbin/proxysql"  |
| Example | "/usr/sbin/proxysql" |


##### `maxscale-binary-path` (2.1)
| Item | Value |
| ---- | ----- |
| Description | Path to ProxySQL |
| Type | String |
| Default | "/usr/sbin/maxscale"  |
| Example | "/usr/sbin/maxscale" |

##### `backup-mysqlclient-path` (2.1)
| Item | Value |
| ---- | ----- |
| Description | Path to ProxySQL |
| Type | String |
| Default | "/usr/sbin/maxscale"  |
| Example | "/usr/sbin/maxscale" |

Some actions during bootstrap are run via command line mysql client    


## Sample MariaDB from source deployed in /usr/local


prov-orchestrator = "local"
prov-db-binary-basedir= "/usr/local/bin"
prov-db-client-basedir= "/usr/local/bin"
backup-mysqlclient-path ="/usr/local/bin/mysql"
backup-mysqlbinlog-path = "/usr/local/bin/mysqlbinlog"
