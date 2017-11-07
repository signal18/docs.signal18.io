---
title: Agent Usage
---
## Provisioning Agent Usage

### Get services manage by the agent

**svcmon**
```
                     app        type topology container | frozen disabled | avail      overall    
                     -----------------------------------+-----------------+-----------------------
10940044185188150515            PRD  flex     -         | no     no       | down       warn      
1167504531203395275             PRD  flex     -         | no     no       | down       warn      
12052738171475100528            PRD  flex     -         | no     no       | down       warn      
14874838644215743505            PRD  flex     -         | no     no       | down       warn      
15878474912568677158            PRD  flex     -         | no     no       | down       warn      
17041910947293356285            PRD  flex     -         | no     no       | warn       warn      
1707051732484776021             PRD  flex     -         | no     no       | warn       warn      
17311646700765639015            PRD  flex     -         | no     no       | up         up        
18201174717036044681            PRD  flex     -         | no     no       | up         up        
5381417461195480388             PRD  flex     -         | no     no       | warn       warn      
8994217380777306087             PRD  flex     -         | no     no       | down       warn      
9850169928245273131             PRD  flex     -         | no     no       | down       warn   
```

### Check service status

**8994217380777306087 print status**
```
8994217380777306087
overall                   warn       
'- avail                  warn       
   |- ip#01          .... down       192.168.100.50@br-prd@container#0001
   |- disk#00        .... up         loop /srv/8994217380777306087_docker.dsk
   |- disk#0000      .... up         pool zp8994217380777306087_00
   |- disk#01        .... up         loop /srv/8994217380777306087_pod01.dsk
   |- disk#1001      .... up         pool zp8994217380777306087_pod01
   |- fs#00          .... up         zfs zp8994217380777306087_00/docker@/srv/8994217380777306087/docker
   |- fs#01          .... up         zfs zp8994217380777306087_pod01/pod01@/srv/8994217380777306087/pod01
   |- container#0001 .... up         docker container 8994217380777306087.container.0001@busybox:latest
   '- container#2001 .... down       docker container 8994217380777306087.container.2001@prima/proxysql:latest
```

### Get service configuration

**8994217380777306087 print config**
```
[DEFAULT]
nodes = {env.nodes}
flex_primary = {env.nodes[0]}
cluster_type = flex
rollback = false
show_disabled = false

docker_daemon_private = true
docker_data_dir = {env.base_dir}/docker
docker_daemon_args = --log-opt max-size=1m --storage-driver=zfs

[disk#00]
type = loop
file = /srv/{svcname}_docker.dsk
size = {env.size}


[disk#0000]
name = zp{svcname}_00
type = zpool
vdev  = {disk#00.file}


[fs#00]
type = zfs
.....
```

Examine the **replication-manager** log you should found some error

```
19:28:22,968 ip#01          INFO    skip allocate: an ip is already defined
19:28:23,027 ip#01          INFO    checking 192.168.100.50 availability
19:28:23,035 ip#01          ERROR   192.168.100.50 is already up on another host**
```
Here we found an other service is using the ip address
We so should stop that service or unprovision it and start again the failed service

### Stop a service
**18201174717036044681 stop**

**18201174717036044681 unprovision**

**8994217380777306087 start**

**8994217380777306087 print status**
```
8994217380777306087
overall                   up         
'- avail                  up         
   |- ip#01          .... up         192.168.100.50@br-prd@container#0001
   |- disk#00        .... up         loop /srv/8994217380777306087_docker.dsk
   |- disk#0000      .... up         pool zp8994217380777306087_00
   |- disk#01        .... up         loop /srv/8994217380777306087_pod01.dsk
   |- disk#1001      .... up         pool zp8994217380777306087_pod01
   |- fs#00          .... up         zfs zp8994217380777306087_00/docker@/srv/8994217380777306087/docker
   |- fs#01          .... up         zfs zp8994217380777306087_pod01/pod01@/srv/8994217380777306087/pod01
   |- container#0001 .... up         docker container 8994217380777306087.container.0001@busybox:latest
   '- container#2001 .... up         docker container 8994217380777306087.container.2001@prima/proxysql:latest
  ```

### Get logs of a docker image

**8994217380777306087 docker logs 8994217380777306087.container.2001**
```
2017-10-21 19:40:42 ProxySQL_Admin.cpp:2870:flush_mysql_variables___database_to_runtime(): [ERROR] Impossible to set not existing variable session_debug with value "(null)". Deleting
2017-10-21 19:40:42 ProxySQL_Admin.cpp:2870:flush_mysql_variables___database_to_runtime(): [ERROR] Impossible to set not existing variable ping_interval_server with value "120000". Deleting
Standard ProxySQL Admin rev. 0.2.0902 -- ProxySQL_Admin.cpp -- Sat Oct 29 13:47:26 2016
Standard MySQL Threads Handler rev. 0.2.0902 -- MySQL_Thread.cpp -- Sat Oct 29 13:47:26 2016
Standard MySQL Authentication rev. 0.2.0902 -- MySQL_Authentication.cpp -- Sat Oct 29 13:47:26 2016
2017-10-21 19:40:42 [INFO] New mysql_replication_hostgroups table
Standard Query Processor rev. 0.2.0902 -- Query_Processor.cpp -- Sat Oct 29 13:47:26 2016
In memory Standard Query Cache (SQC) rev. 1.2.0905 -- Query_Cache.cpp -- Sat Oct 29 13:47:26 2016
Standard MySQL Monitor (StdMyMon) rev. 1.2.0723 -- MySQL_Monitor.cpp -- Sat Oct 29 13:47:26 2016
```

### Login docker image

**8994217380777306087 docker exec -ti 8994217380777306087.container.2001 /bin/sh**
