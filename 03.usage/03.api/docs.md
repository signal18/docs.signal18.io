---
title: API Client Usage
---

## API Client Usage

The rest API is using JWT TLS and is served by default on port 10005 by the replication-manager monitor/

Credentials can be customized by setting your own user and password in configuration file.  

```
api-port ="10005"
api-credential = "admin:repman"
```

At startup of the monitor, X509 certificates are loaded from the replication-manager share directory to ensure TLS https secure communication.

Replace those files with your own certificate to make sure your deployment is secured.

```
# Key considerations for algorithm "RSA" ≥ 2048-bit
openssl genrsa -out server.key 2048

# Key considerations for algorithm "ECDSA" ≥ secp384r1
# List ECDSA the supported curves (openssl ecparam -list_curves)
openssl ecparam -genkey -name secp384r1 -out server.key
openssl req -new -x509 -sha256 -key server.key -out server.crt -days 3650
```

At startup **replication-manager** monitor will generate in memory extra self-signed RSA certificate to ensure token encryption exchange for JWT.

# Calling API via the client

API can be called via command line client to simplify curl syntax with JWT token.

```
./replication-manager-cli api  --url="https://127.0.0.1:10005/api/clusters/ux_dck_zpool_loop/servers/actions/add/192.168.1.73/3306"   --cluster="ux_dck_zpool_loop"
```

### Monitor Unprotected Endpoints

/api/login

INPUT: POST
```
{"username":"admin", "password":"mariadb"}
```
OUTPUT:
```
{"token":"hash"}
```

/api/status

OUPUT:
```
{"alive": true}      
./replication-manager api  --url="https://127.0.0.1:10005/api/status"  
```

/api/clusters/{clusterName}/status

# Version 2.0 & 2.1

/clusters/{clusterName}/servers/{serverName}/master-status
Return Code 200 if server id is a master

/clusters/{clusterName}/servers/{serverName}/slave-status
Return Code 200 if server id is a slave   

/clusters/{clusterName}/servers/{serverHost}/{serverPort}/master-status
Return Code 200 if server id is a master

/clusters/{clusterName}/servers/{serverHost}/{serverPort}/slave-status
Return Code 200 if server id is a slave   
# Version  2.1

/clusters/{clusterName}/servers/{serverName}/is-master
Return Code 200 if server id is a master

/clusters/{clusterName}/servers/{serverName}/is-slave
Return Code 200 if server id is a slave   

/clusters/{clusterName}/servers/{serverHost}/{serverPort}/is-master
Return Code 200 if server id is a master

/clusters/{clusterName}/servers/{serverHost}/{serverPort}/is-slave
Return Code 200 if server id is a slave    

### API Protected Endpoints

/api/monitor

OUPUT:
```
{
	"version": "2.0.0-rc2",
	"full-version": "2.0.0-rc2-132-gae9091a",
	"os": "darwin",
	"arch": "amd64",
	"agents": [],
	"uuid": "7224a9d3",
	"hostname": "macbook-pro-de-apple-2.local",
	"status": "A",
	"spitbrain": false,
	"clusters": [
		"cluster_haproxy_masterslave"
	],
	"tests": [
		"testSwitchoverAllSlavesDelayMultimasterNoRplChecksNoSemiSync",
		"testSwitchoverLongTransactionNoRplCheckNoSemiSync",
		"testSwitchoverLongQueryNoRplCheckNoSemiSync",
		"testSwitchoverLongTrxWithoutCommitNoRplCheckNoSemiSync",
		"testSwitchoverReadOnlyNoRplCheck",
		........
		"testSlaReplAllSlavesStopNoSemiSync",
		"testSlaReplAllSlavesDelayNoSemiSync"
	],
	"config": {
		"monitoring-basedir": "system",
		"monitoring-datadir": "/var/lib/replication-manager",
		"monitoring-sharedir": "/opt/replication-manager/share",
		"monitoring-confdir": "/etc/replication-manager",
		"monitoring-ticker": 2,
		"monitoring-socket": "",
		"monitoring-tunnel-host": "",
		"monitoring-tunnel-credential": "",
		"monitoring-address": "",
		"monitoring-write-heartbeat": false,
		"monitoring-write-heartbeat-credential": "",
		"monitoring-schema-change": true,
		"monitoring-schema-change-script": "",
		"monitoring-queries": true,
		"monitoring-long-query-time": 10000,
		"monitoring-long-query-script": "",
		"monitoring-scheduler": true,
		"verbose": false,
		"log-file": "",
		"log-syslog": false,
		"log-level": 0,
		"log-sst": false,
		"db-servers-credential": "",
		"db-servers-hosts": "",
		"db-servers-tls-ca-cert": "",
		"db-servers-tls-client-key": "",
		"db-servers-tls-client-cert": "",
		"db-servers-prefered-master": "",
		"db-servers-ignored-hosts": "",
		"db-servers-connect-timeout": 5,
		"db-servers-read-timeout": 15,
		"db-servers-binary-path": "/usr/local/mysql/bin",
		"db-servers-locality": "127.0.0.1",
		"replication-master-connect-retry": 10,
		"replication-credential": "",
		"replication-source-name": "",
		"replication-use-ssl": false,
		"replication-multi-master-ring": false,
		"replication-multi-master-wsrep": false,
		"replication-multi-master": false,
		"replication-multi-tier-slave": false,
		"replication-master-slave-never-relay": true,
		"switchover-wait-kill": 5000,
		"switchover-wait-trx": 10,
		"switchover-wait-write-query": 10,
		"switchover-at-equal-gtid": false,
		"switchover-at-sync": false,
		"switchover-max-slave-delay": 0,
		"switchover-slave-wait-catch": true,
		"failover-limit": 5,
		"failover-pre-script": "",
		"failover-post-script": "",
		"failover-readonly-state": true,
		"failover-time-limit": 0,
		"failover-at-sync": false,
		"failover-event-scheduler": false,
		"failover-event-status": false,
		"failover-restart-unsafe": false,
		"failover-falsepositive-ping-counte": 5,
		"failover-reset-time": 0,
		"failover-mode": "manual",
		"failover-max-slave-delay": 30,
		"failover-falsepositive-heartbeat": true,
		"failover-falsepositive-maxscale": false,
		"failover-falsepositive-heartbeat-timeout": 3,
		"failover-falsepositive-maxscale-timeout": 14,
		"failover-falsepositive-external": false,
		"failover-falsepositive-external-port": 80,
		"autorejoin": true,
		"autorejoin-flashback": false,
		"autorejoin-script": "",
		"autorejoin-mysqldump": false,
		"autorejoin-backup-binlog": true,
		"autorejoin-flashback-on-sync": true,
		"autorejoin-flashback-on-unsync": false,
		"autorejoin-slave-positional-hearbeat": false,
		"autorejoin-zfs-flashback": false,
		"check-type": "tcp",
		"check-replication-filters": true,
		"check-binlog-filters": true,
		"check-grants": true,
		"check-replication-state": true,
		"force-slave-heartbeat": false,
		"force-slave-heartbeat-time": 3,
		"force-slave-heartbeat-retry": 5,
		"force-slave-gtid-mode": false,
		"force-slave-no-gtid-mode": false,
		"force-slave-semisync": false,
		"force-slave-readonly": false,
		"force-binlog-row": false,
		"force-binlog-annotate": false,
		"force-binlog-compress": false,
		"force-binlog-slowqueries": false,
		"force-binlog-checksum": false,
		"force-inmemory-binlog-cache-size": false,
		"force-disk-relaylog-size-limit": false,
		"force-disk-relaylog-size-limit-size": 1000000000,
		"force-sync-binlog": false,
		"force-sync-innodb": false,
		"orce-noslave-behind": false,
		"http-bind-address": "localhost",
		"http-port": "10001",
		"http-server": true,
		"http-root": "/opt/replication-manager/share/dashboard",
		"http-auth": false,
		"http-bootstrap-button": false,
		"http-session-lifetime": 3600,
		"mail-from": "mrm@localhost",
		"mail-to": "",
		"mail-smtp-addr": "localhost:25",
		"heartbeat-table": false,
		"extproxy": false,
		"extproxy-address": "",
		"shardproxy": false,
		"shardproxy-servers": "127.0.0.1:3307",
		"shardproxy-user": "root:mariadb",
		"shardproxy-copy-grants": true,
		"maxscale": false,
		"maxscale-servers": "127.0.0.1",
		"maxscale-port": "6603",
		"maxscale-user": "admin",
		"maxscale-pass": "mariadb",
		"maxscale-write-port": 3306,
		"maxscale-read-port": 3307,
		"maxscale-read-write-port": 3308,
		"maxscale-maxinfo-port": 3309,
		"maxscale-binlog": false,
		"MxsBinlogPort": 3309,
		"maxscale-disable-monitor": false,
		"maxscale-get-info-method": "maxadmin",
		"maxscale-server-match-port": false,
		"myproxy": false,
		"myproxy-port": 4000,
		"myproxy-user": "admin",
		"myproxy-password": "repman",
		"haproxy": true,
		"haproxy-servers": "127.0.0.1",
		"haproxy-write-port": 3306,
		"haproxy-read-port": 3307,
		"haproxy-stat-port": 1988,
		"haproxy-ip-write-bind": "0.0.0.0",
		"haproxy-ip-read-bind": "0.0.0.0",
		"haproxy-binary-path": "/usr/local/bin/haproxy",
		"proxysql": false,
		"proxysql-servers": "127.0.0.1",
		"proxysql-port": "6033",
		"proxysql-admin-port": "6032",
		"proxysql-user": "admin",
		"proxysql-password": "admin",
		"proxysql-writer-hostgroup": "0",
		"proxysql-reader-hostgroup": "1",
		"proxysql-copy-grants": true,
		"proxysql-bootstrap": false,
		"mysqlrouter": false,
		"mysqlrouter-servers": "",
		"mysqlrouter-port": "",
		"mysqlrouter-use": "",
		"mysqlrouter-pass": "",
		"mysqlrouter-write-port": 0,
		"mysqlrouter-read-port": 0,
		"mysqlrouter-read-write-port": 0,
		"sphinx": false,
		"sphinx-servers": "127.0.0.1",
		"sphinx-config": "/opt/replication-manager/share/sphinx/sphinx.conf",
		"sphinx-sql-port": "9306",
		"sphinx-port": "9312",
		"registry-consul": false,
		"registry-servers": "127.0.0.1",
		"graphite-metrics": false,
		"graphite-embedded": false,
		"graphite-carbon-host": "127.0.0.1",
		"graphite-carbon-port": 2003,
		"graphite-carbon-api-port": 10002,
		"graphite-carbon-server-port": 10003,
		"graphite-carbon-link-port": 7002,
		"graphite-carbon-pickle-port": 2004,
		"graphite-carbon-pprof-port": 7007,
		"sysbench-binary-path": "/usr/local/bin/sysbench",
		"sysbench-time": 100,
		"sysbench-threads": 4,
		"arbitration-external": false,
		"arbitration-external-secret": "",
		"arbitration-external-hosts": "88.191.151.84:80",
		"arbitration-external-unique-id": 0,
		"arbitration-peer-hosts": "127.0.0.1:10001",
		"arbitrator-bind-address": "",
		"arbitrator-driver": "",
		"FailForceGtid": false,
		"test": true,
		"test-inject-traffic": false,
		"Enterprise": false,
		"opensvc-host": "",
		"opensvc-register": false,
		"opensvc-admin-user": "",
		"opensvc-user": "",
		"opensvc-codeapp": "",
		"prov-db-localhost-binary-path": "",
		"prov-db-service-type": "",
		"prov-db-agents": "",
		"prov-db-memory": "",
		"prov-db-disk-iops": "",
		"prov-db-cpu-cores": "",
		"prov-db-tags": "",
		"prov-db-disk-size": "",
		"prov-db-disk-fs": "",
		"prov-db-disk-pool": "",
		"prov-db-disk-device": "",
		"prov-db-disk-type": "",
		"prov-db-disk-snapshot-prefered-master": false,
		"prov-db-disk-snapshot-keep": 0,
		"prov-db-net-iface": "",
		"prov-db-net-mask": "",
		"prov-db-net-gateway": "",
		"prov-db-docker-img": "",
		"prov-db-datadir-version": "10.2",
		"prov-db-load-sql": "",
		"prov-db-load-csv": "",
		"prov-proxy-service-type": "",
		"prov-proxy-agents": "",
		"prov-proxy-disk-size": "",
		"prov-proxy-disk-fs": "",
		"prov-proxy-disk-pool": "",
		"prov-proxy-disk-device": "",
		"prov-proxy-disk-type": "",
		"prov-proxy-net-iface": "",
		"prov-proxy-net-mask": "",
		"prov-proxy-net-gateway": "",
		"prov-proxy-route-addr": "",
		"prov-proxy-route-port": "",
		"rov-proxy-route-mask": "",
		"prov-proxy-route-policy": "",
		"prov-proxy-docker-shardproxy-img": "",
		"prov-proxy-docker-maxscale-img": "",
		"prov-proxy-docker-haproxy-img": "",
		"prov-proxy-docker-proxysql-img": "",
		"prov-proxy-docker-mysqlrouter-img": "",
		"prov-proxy-tags": "",
		"prov-sphinx-agents": "",
		"prov-sphinx-docker-img": "",
		"prov-sphinx-memory": "",
		"prov-sphinx-disk-size": "",
		"prov-sphinx-cpu-cores": "",
		"prov-sphinx-max-childrens": "",
		"prov-sphinx-disk-pool": "",
		"prov-sphinx-disk-fs": "",
		"prov-sphinx-disk-device": "",
		"prov-sphinx-disk-type": "",
		"prov-sphinx-tags": "",
		"prov-sphinx-reindex-schedule": "",
		"prov-sphinx-service-type": "",
		"prov-tls-server-ca": "",
		"prov-tls-server-cert": "",
		"prov-tls-server-key": "",
		"api-credential": "admin:repman",
		"api-port": "10005",
		"api-bind": "0.0.0.0",
		"alert-script": "",
		"backup": false,
		"scheduler-db-servers-logical-backup": true,
		"scheduler-db-servers-physical-backup": true,
		"scheduler-db-servers-logs": true,
		"scheduler-db-servers-optimize": true,
		"scheduler-db-servers-logical-backup-cron": "0 0 1 * * 6",
		"scheduler-db-servers-physical-backup-cron": "0 0 0 * * *",
		"scheduler-db-servers-logs-cron": "@every 10m",
		"scheduler-db-servers-optimize-cron": "0 0 3 1 * 5",
		"backup-logical-type": "mysqldump",
		"backup-physical-type": "xtrabackup",
		"backup-keep-hourly": 1,
		"backup-keep-daily": 1,
		"backup-keep-weekly": 1,
		"backup-keep-monthly": 1,
		"backup-keep-yearly": 1,
		"backup-repo": "directory",
		"backup-repo-aws-key": "",
		"backup-repo-aws-secret": "",
		"backup-repo-aws-uri": ""
	},
	"logs": {
		"Buffer": [
			{
				"Group": "cluster_haproxy_masterslave",
				"Level": "STATE",
				"Timestamp": "2018/02/16 11:36:59",
				"Text": "OPENED WARN0073 : Xtrabackup task running on server 127.0.0.1:3313"
			},
			{
				"Group": "cluster_haproxy_masterslave",
				"Level": "STATE",
				"Timestamp": "2018/02/16 11:36:59",
				"Text": "OPENED ERR00060 : To many non closed task in scheduler, donor may not work on server 127.0.0.1:3312"
			},
      ........

```

## Cluster Protected Endpoint

### 2.0

/api/clusters/{clusterName}

```
{
	"name": "cluster_haproxy_masterslave",
	"db-servers": [
		"8228534449735335205",
		"13916101692659507786",
		"9706829687233797502",
		"3862921442801615377"
	],
	"db-servers-crashes": null,
	"proxies-servers": [
		"12217188530888569408"
	],
	"failover-counter": 0,
	"failover-last-time": 0,
	"active-passive-status": "A",
	"config": {
		"monitoring-basedir": "system",
		"monitoring-datadir": "/var/lib/replication-manager",
		"monitoring-sharedir": "/opt/replication-manager/share",
		"monitoring-confdir": "/etc/replication-manager",
		"monitoring-ticker": 2,
		.....
		"backup-keep-daily": 1,
		"backup-keep-weekly": 1,
		"backup-keep-monthly": 1,
		"backup-keep-yearly": 1,
		"backup-repo": "directory",
		"backup-repo-aws-key": "",
		"backup-repo-aws-secret": "",
		"backup-repo-aws-uri": ""
	},
	"clean-replication": false,
	"is-down": false,
	"is-provisionned": false,
	"schedule": null
}
```

/api/clusters/{clusterName}/settings

Return cluster configuration file

/api/clusters/{clusterName}/actions/switchover

/api/clusters/{clusterName}/actions/failover

/api/clusters/{clusterName}/actions/replication/bootstrap/{topology}

/api/clusters/{clusterName}/actions/replication/cleanup

/api/clusters/{clusterName}/actions/services todo
List agents services resources

/api/clusters/{clusterName}/actions/services/provision

/api/clusters/{clusterName}/actions/services/bootstrap

/api/clusters/{clusterName}/actions/start-traffic

/api/clusters/{clusterName}/actions/stop-traffic


/api/clusters/{clusterName}/actions/addserver/{host}/{port}

/api/clusters/{clusterName}/topology/servers

/api/clusters/{clusterName}/topology/master

/api/clusters/{clusterName}/topology/slaves

/api/clusters/{clusterName}/topology/proxies

/api/clusters/{clusterName}/topology/logs

/api/clusters/{clusterName}/topology/alerts

/api/clusters/{clusterName}/topology/crashes

/api/clusters/{clusterName}/tests

/api/clusters/{clusterName}/tests/actions/run/{testName}

/api/clusters/{clusterName}/settings

/api/clusters/{clusterName}/settings/reload

/api/clusters/{clusterName}/settings/switch/{parmaterName}

## Database Server Protected Endpoint

### 2.0

/api/clusters/{clusterName}/servers/{serverName}/actions/start

/api/clusters/{clusterName}/servers/{serverName}/actions/stop

/api/clusters/{clusterName}/servers/{serverName}/actions/backup

/api/clusters/{clusterName}/servers/{serverName}/actions/maintenance

/api/clusters/{clusterName}/servers/{serverName}/actions/unprovision

/api/clusters/{clusterName}/servers/{serverName}/actions/provision

### 2.1

/api/clusters/{clusterName}/actions/master-physical-backup

/api/clusters/{clusterName}/servers/{serverName}/processlist

/api/clusters/{clusterName}/servers/{serverName}/variables

/api/clusters/{clusterName}/servers/{serverName}/status

/api/clusters/{clusterName}/servers/{serverName}/errorlog

/api/clusters/{clusterName}/servers/{serverName}/slowlog

/api/clusters/{clusterName}/servers/{serverName}/tables

/api/clusters/{clusterName}/servers/{serverName}/schemas

/api/clusters/{clusterName}/servers/{serverName}/innodb-status

/api/clusters/{clusterName}/servers/{serverName}/all-slaves-status

/api/clusters/{clusterName}/servers/{serverName}/master-status

/api/clusters/{clusterName}/servers/{serverName}/is-master

/api/clusters/{clusterName}/servers/{serverName}/is-slave

/api/clusters/{clusterName}/servers/{serverName}/{serverPort}/is-master

/api/clusters/{clusterName}/servers/{serverName}/{serverPort}/is-slave

## Proxy Protected Endpoint

/api/clusters/{clusterName}/proxies/{proxyName}/actions/unprovision

/api/clusters/{clusterName}/proxies/{proxyName}/actions/provision
