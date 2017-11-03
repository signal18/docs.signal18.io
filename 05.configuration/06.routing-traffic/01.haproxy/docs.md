---
title: Haproxy Configuration
---

### HaProxy Configuration


**replication-manager** can operate with HaProxy with different modes: localhost and remote since **version 2.0**.

##### `haproxy` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Enable Driving HaProxy. |
| Type | boolean |
| Default Value | false |  

##### `haproxy-servers` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Comma separated list of the HaProxy hosts. |
| Type | String |
| Default Value | "127.0.0.1" |  


##### `haproxy-write-port` (0.7)

| Item | Value |
| ---- | ----- |
| Description | HaProxy port to get database connection in WRITE. |
| Type | Integer |
| Default Value | 3306 |  

##### `haproxy-ip-write-bind` (0.7)

| Item | Value |
| ---- | ----- |
| Description | If WRITE traffic bing to specific IP. |
| Type | String |
| Default Value | "0.0.0.0" |  

##### `haproxy-read-port` (0.7)

| Item | Value |
| ---- | ----- |
| Description | HaProxy port to load balance read connection to all databases. |
| Type | Integer |
| Default Value | 3306 |  

##### `haproxy-ip-read-bind` (0.7)

| Item | Value |
| ---- | ----- |
| Description | If READ traffic bing to specific IP. |
| Type | String |
| Default Value | "0.0.0.0" |  

##### `haproxy-stat-port` (0.7)

| Item | Value |
| ---- | ----- |
| Description | HaProxy port to collect statistics.  |
| Type | Integer |
| Default Value | 1988 |  

#### Local mode

##### `haproxy-binary-path` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Full path to HaProxy binary. |
| Type | String |
| Default Value | "/usr/sbin/haproxy" |  

**replication-manager**, need HaProxy to be install and on the same server **replication-manager** will than start one HaProxy Daemon per Cluster configured and maintain the configuration to route traffic to the topology.

**replication-manager** generate some HaProxy configuration file. A template is located in the share directory used by replication-manager. For safety HaProxy is not stopped when replication-manager is stopped

**replication-manager** re-generate the HaProxy configuration file when the topology change and instruct HaProxy to reload this configuration during failover and switchover.

#### Remote mode

HaProxy can call replication **replication-manage(2.0)** [http handlers](/configuration/routing-traffic/check-http-handler) via the check-external feature to check that a backend is a valid Master or valid Slave  

> **replication-manager (2.0)** http server need to be enable and it's bind address need to be joinable from HaProxy for node health check to happen.   

**replication-manager-pro (2.0)** can auto deploy HaProxy micro service, HaProxy config need the http-bind-address to be joinable from the proxy.


The haproxy conf that is deployed via micro services looks like this:

```
global

 daemon
 maxconn 4096
 stats socket /run/admin.sock level admin
 log 127.0.0.1 local0 debug
 external-check

defaults
   log global
   mode http
   option dontlognull
   option redispatch
   option clitcpka
   option srvtcpka
   retries 3
   maxconn 500000
   timeout http-request 5s
   timeout connect 5000ms
   timeout client 50000s
   timeout server 50000s

listen stats
   bind 0.0.0.0:1988
   mode http
   stats enable
   stats uri /
   stats refresh 2s
   stats realm Haproxy\ Stats

frontend my_write_frontend
    bind 0.0.0.0:3303
    option tcplog
    mode tcp
    default_backend service_write

frontend my_read_frontend
    bind 0.0.0.0:3302
    option tcplog
    mode tcp
    default_backend service_read

backend service_write
    mode tcp
    balance leastconn
    option external-check
    external-check path "/usr/bin:/bin"
    external-check command /usr/bin/checkmaster


server server1 192.168.100.70:3306  weight 100 maxconn 2000 check inter 1000
server server2 192.168.100.71:3306  weight 100 maxconn 2000 check inter 1000
server server3 192.168.100.72:3306  weight 100 maxconn 2000 check inter 1000

backend service_read
    mode tcp
    balance leastconn
    option external-check
    external-check path "/usr/bin:/bin"
    external-check command /usr/bin/checkmaster


server server1 192.168.100.70:3306  weight 100 maxconn 2000 check inter 1000
server server2 192.168.100.71:3306  weight 100 maxconn 2000 check inter 1000
server server3 192.168.100.72:3306  weight 100 maxconn 2000 check inter 1000
```

The checkmaster script deployed looks like that: where http://192.168.100.20:10001 is the local dc **replication-manager (2.0)**  web server  

```
/usr/bin/checkmaster

cat checkmaster
#!/bin/sh

if [[ $(wget -O - http://192.168.100.20:10001/clusters/ux_dck_zpool_loop/servers/$3/$4/master-status | grep -vc "200 -") > 0 ]] ; then exit 0; else exit 1 ; fi
```


cat /usr/bin/checkslave

```
#!/bin/sh

if [[ $(wget -O - http://192.168.100.20:10001/clusters/ux_dck_zpool_loop/servers/$3/$4/slave-status | grep -vc "200 -") > 0 ]] ; then exit 0; else exit 1 ; fi
```

> using **replication-manager** active/standby & arbitrator make sure each script point to it's local replication-manager. The all side loosing arbitration will repoert backend down.    
