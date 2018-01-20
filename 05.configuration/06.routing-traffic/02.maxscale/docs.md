---
title: MaxScale Configuration
---

#### Maxscale Configuration

**replication-manager** can operate with MaxScale with different modes.  

Read the following sections to route database traffic using Maxscale.

Using **replication-manager** we can speed up the failover process of Maxscale, **replication-manager** will instruct MaxScale about the new master so that the Maxscale monitor delay is shortcut. This bring less risk against MaxScale sending traffic to the old master during the monitoring delay, **replication-manager** put extra barriers against writes during failover and switchover on the database level but not forever.     

##### Maxscale Monitor Status Configuration  

With **replication-manager** one can get Maxscale Servers State in the HTTP server proxy tab.

**replication-manager** will monitor that the Maxscale Write Port point to the master in topology

##### `maxscale` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Enable MaxScale monitoring |
| Type | boolean |
| Default Value | false |  

##### `maxscale-servers` (2.0), `maxscale-host` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Comma separated list of the MaxScale hosts. |
| Type | String |
| Default Value | "" |  

##### `maxscale-write-port` (1.1)

| Item | Value |
| ---- | ----- |
| Description | MaxScale port to get database connection in WRITE. |
| Type | Integer |
| Default Value | 3306 |  

##### `maxscale-read-port` (1.1)

| Item | Value |
| ---- | ----- |
| Description | MaxScale port to load balance read connection to all databases. |
| Type | Integer |
| Default Value | 3306 |  

##### `maxscale-read-write-port` (1.1)

| Item | Value |
| ---- | ----- |
| Description | MaxScale port to a read write spilling connection.  |
| Type | Integer |
| Default Value | 3306 |  


**replication-manager** default monitoring method is to use MaxScale tcp row protocol. Configuration can be setup to use the MaxScale maxinfo plugin that provide a JSON REST service. In new Maxscale release a true REST API is now available but **replication-manager** do not use it yet.  

##### `maxscale-get-info-method` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Enable MaxScale monitoring |
| Type | list |
| Possible Values | maxinfo,maxadmin,maxapi |  
| Default Value | maxadmin |  


##### `maxscale-port` (0.7)

| Item | Value |
| ---- | ----- |
| Description | MaxAdmin port to send command in tcp raw protocol. |
| Type | Integer |
| Default Value | 6603 |

##### `maxscale-user` (0.7)

| Item | Value |
| ---- | ----- |
| Description | MaxAdmin user in tcp raw protocol. |
| Type | String |
| Default Value | "admin" |

##### `maxscale-password` (2.0) `maxscale-pass` (0.7)

| Item | Value |
| ---- | ----- |
| Description | MaxAdmin password in tcp raw protocol. |
| Type | String |
| Default Value | "mariadb" |

##### `maxscale-maxinfo-port` (0.7)

| Item | Value |
| ---- | ----- |
| Description | MaxInfo port to for reading server monitor state |
| Type | Integer |
| Default Value | 4003 |


##### Passive Mode

Advised mode

MaxScale auto-discovers the new topology after failover or switchover. Replication Manager can reduce MaxScale monitor detection time of the master failure to reduce the time where it might block clients. This setup best works in 3 nodes in Master-Slaves cluster, because one slave is always available for re-discovering new topologies.


Example Maxscale settings:
```
[MySQL Monitor]  
type=monitor  
module=mysqlmon  
servers=%%ENV:SERVERS_LIST%%  
user=root  
passwd=%%ENV:MYROOTPWD%%  
monitor_interval=500  
detect_stale_master=true

[Write Connection Router]  
type=service  
router=readconnroute  
router_options=master  
servers=%%ENV:SERVERS_LIST%%  
user=root  
passwd=%%ENV:MYROOTPWD%%  
enable_root_user=true  
```

>In case all slaves are down, MaxScale can still operate on the Master with the following maxscale monitoring setup :
https://github.com/mariadb-corporation/MaxScale/blob/2.1/Documentation/Monitors/MySQL-Monitor.md#failover

```
detect_stale_master
```
In Maxscale 2.1 routing to last node have been introduce so that transparent support of 2 nodes cluster is possible [![Doc]](https://github.com/mariadb-corporation/MaxScale/blob/2.1/Documentation/Monitors/MySQL-Monitor.md#failover)
```
detect_stale_slave=true
```

Use the following example grant for your MaxScale user:
```
CREATE USER 'maxadmin'@'%' IDENTIFIED BY 'maxpwd';
GRANT SELECT ON mysql.user TO 'maxadmin'@'%';
GRANT SELECT ON mysql.db TO 'maxadmin'@'%';
GRANT SELECT ON mysql.tables_priv TO 'maxadmin'@'%';
GRANT SHOW DATABASES, REPLICATION CLIENT ON *.* TO 'maxadmin'@'%';
GRANT ALL ON maxscale_schema.* TO 'maxadmin'@'%';
```
Also, to protect consistency it is strongly advised to disable *SUPER* privilege to users that perform writes, such as the MaxScale user when the Read-Write split module is instructed to check for replication lag:

```
[Splitter Service]
type=service
router=readwritesplit
max_slave_replication_lag=30
```
##### Driving Mode

Operating MaxScale without monitor is the second Replication-Manager mode via:

##### `maxscale-disable-monitor` (1.1)

| Item | Value |
| ---- | ----- |
| Description | MaxInfo port to for reading server monitor state |
| Type | Boolean |
| Default Value | false |

replication-manager will assign each server status flags to the MaxScale backend servers via the  MaxScale row protocol. This mode of operation is similar to the HAProxy but MaxScale server does not need to be collocated with **replication-manager**

If your are using old MaxScale release that does not support  detect_stale_slave it can be used to support 2 nodes cluster

##### Slave Mode

MaxScale drive  **replication-manager** via calling some extranm scripts using **replication-manager-cli**.

Implications is that **replication-manager**  need configuration for automatic failover. This is not always desirable, the false positive detection in  **replication-manager**  is much more advance  o the forced failover may not succeed, i'm not aware of any retry solution in such case. This would force anyway usage of **replication-manager**  command for more than a one shoot failure scenario.

##### MaxScale Binlog Server

##### `maxscale-binlog` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Enable support for MaxScale Binlog Server |
| Type | Boolean |
| Default Value | false |

##### `maxscale-maxinfo-port` (1.1)

| Item | Value |
| ---- | ----- |
| Description | MaxScale Binlog Server port  |
| Type | Integer |
| Default Value | 3306 |

##### `force-slave-gtid-mode` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Disable GTID on slave |
| Type | Boolean |
| Default Value | false |

This parameter need to be enable for maxscale < 2.2

> From MaxScale 2.2 can support MariaDB GTID so force-gtid-mode=false is not needed anymore
part of task https://github.com/mariadb-corporation/MaxScale/tree/MXS-1075
Maxscale Binlog router plugin configuration
```
transaction_safety=On,mariadb10-compatibility=On,mariadb_gtid=On
```

MaxScale Binlog Server need all databases node to have same binlog prefix
```
bin_log='mariadb-bin'
```

MaxScale Binlog Server Configuration
```
router_options=mariadb10-compatibility=1,server-id=999,user=skysql,password=skyvodka,send_slave_heartbeat=on,transaction_safety=on,semisync=1
```
