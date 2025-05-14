---
title: Agent Usage
taxonomy:
    category: docs
---
## Provisioning Agent Usage

### Get services manage by the agent

**om mon**
```
Threads                                               s18-fr-4    s18-fr-5     s18-fr-6   
 daemon                  running                    |                                     
 dns                     running                   
 hb#1.rx                 running         [::]:10000 | X           X            /          
 hb#1.tx                 running                    | O           O            /          
 hb#2.rx                 running relay2.opensvc.com | O           O            /          
 hb#2.tx                 running                    | O           O            /          
 hb#3.rx                 running 37.187.220.6:10001 | O           O            /          
 hb#3.tx                 running                    | O           O            /          
 listener                running              :1214
 monitor                 running                   
 scheduler               running                   

Nodes                                                 s18-fr-4    s18-fr-5     s18-fr-6   
 score                                              | 94          65           91         
  load 15m                                          | 0.5         1.2          1.4        
  mem                                               | 30/98%:188g 65/98%:187g  24/98%:188g
  swap                                              | 3/90%:2.00g 81/90%:1023m 2/90%:1023m
 state                                              |                                     

*/svc/*                                               s18-fr-4    s18-fr-5     s18-fr-6   
 arceau/svc/db1          up             ha    1/1   | O^                                  
 arceau/svc/db2          up             ha    1/1   |             O^                      
 arceau/svc/db3          up             ha    1/1   |                          O^         
 arceau/svc/prx1         up             ha    1/1   | O^                                  
 arceau/svc/prx2         up             ha    1/1   |             O^                      
```

### Check service status
Get to the node running the service  
**om arceau/svc/db3 print status**  
```
arceau/svc/db3                   up                                                           
`- instances            
   `- s18-fr-6                   up         idle, started
      |- ip#01          ...../.. up         cni s18 10.60.48.92/20 eth12                      
      |- volume#01      ........ up         db3                                               
      |- volume#02      ........ up         db3-sec                                           
      |- container#01   ...../.. up         docker ghcr.io/opensvc/pause                      
      |- container#02   ...O./.. n/a        docker alpine                                     
      |- container#db   ...../.. up         docker mariadb:11.4                               
      `- container#jobs ...../.. up         docker mariadb:11.4                               

```

### Get service configuration

**om arceau/svc/db3 print config**
```
[DEFAULT]
app =
docker_daemon_private = false
nodes = s18-fr-6
orchestrate = ha
rollback = false
id = aa37e187-f2af-4383-a58c-9594e2bb2493

[container#01]
hostname = {svcname}.{namespace}.svc.{clustername}
image = ghcr.io/opensvc/pause
rm = true
run_args = --sysctl net.ipv4.tcp_tw_reuse=1 --sysctl net.core.somaxconn=1024  --sysctl net.ipv4.tcp_fin_timeout=10
type = docker

[container#02]
command = -c 'wget --no-check-certificate -q -O- $REPLICATION_MANAGER_URL/static/configurator/opensvc/bootstrap | sh'
configs_environment = env/REPLICATION_MANAGER_USER env/REPLICATION_MANAGER_URL
detach = false
entrypoint = /bin/sh
environment = REPLICATION_MANAGER_CLUSTER_NAME={namespace} REPLICATION_MANAGER_HOST_NAME={fqdn} REPLICATION_MANAGER_HOST_PORT=3306
image = alpine
netns = container#01
optional = true
rm = true
secrets_environment = env/REPLICATION_MANAGER_PASSWORD
start_timeout = 30s
type = docker
volume_mounts = /etc/localtime:/etc/localtime:ro {name}:/bootstrap

[container#db]
##docker_image = quay.io/mariadb-foundation/mariadb-debug:10.11-mdev-33798-knielsen-pkgtest
#command = gdb -ex r -ex thread apply all bt -frame-arguments all full --args mariadbd
#run_args = --user mysql --cap-add SYS_PTRACE --ulimit nofile=262144:262144
environment = MYSQL_INITDB_SKIP_TZINFO=yes
image = {env.docker_image}
netns = container#01
rm = true
run_args = --tmpfs=/tmp:size=256m --ulimit nofile=262144:262144 --sysctl net.ipv4.tcp_tw_reuse=1 --sysctl net.core.somaxconn=1024  --sysctl net.ipv4.tcp_fin_timeout=10 --memory=16384m --memory-swap=16384m --cpus=4.0
secrets_environment = env/MYSQL_ROOT_PASSWORD
tags =
type = docker
volume_mounts = /etc/localtime:/etc/localtime:ro {name}/data:/var/lib/mysql:rw {name}/mysql-files:/var/lib/mysql-files:rw {name}/etc/mysql:/etc/mysql:rw {name}/init:/docker-entrypoint-initdb.d:rw {name}/run/mysqld:/run/mysqld:rw

[container#jobs]
command = /docker-entrypoint-initdb.d/dbjobs_launcher
entrypoint = /bin/bash
environment = MYSQL_INITDB_SKIP_TZINFO=yes
image = {env.docker_image}
netns = container#01
rm = true
run_args = --ulimit nofile=262144:262144
secrets_environment = env/MYSQL_ROOT_PASSWORD
tags =
type = docker
volume_mounts = /etc/localtime:/etc/localtime:ro {name}/jobs:/var/lib/replication-manager-jobs:rw {name}/data:/var/lib/mysql:rw {name}/etc/mysql:/etc/mysql:rw {name}/init:/docker-entrypoint-initdb.d:rw {name}/run/mysqld:/run/mysqld:rw {name}-sec/:/credentials

[env]
docker_image = mariadb:11.4
nodes = s18-fr-6
size = 50g

[ip#01]
netns = container#01
network = s18
type = cni

[volume#01]
directories = run/mysqld
group = 999
name = {name}
pool = dbssd
size = {env.size}
user = 999

[volume#02]
dirperm = 700
name = {name}-sec
perm = 600
secrets = env/MYSQL_ROOT_PASSWORD:/
size = 1m
type = shm
user = 99
.....
```

Examine the **service** log you should found some error
**om arceau/svc/db3  logs**  
```
19:28:22,968 ip#01          INFO    skip allocate: an ip is already defined
19:28:23,027 ip#01          INFO    checking 192.168.100.50 availability
19:28:23,035 ip#01          ERROR   192.168.100.50 is already up on another host**
```

Working with services 
**om arceau/svc/db3 provision**
**om arceau/svc/db3 stop**
**om arceau/svc/db3 unprovision**
**om arceau/svc/db3 start**


### Get logs of a docker image

**om arceau/svc/db3 docker logs {db}**
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

### Entering a docker image

**om arceau/svc/db3 docker exec -ti {db} /bin/sh**
