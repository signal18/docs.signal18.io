---
title: Alerting Configuration
---

## Alert Configuration

replication-manager offer multiple way of alerting on cluster server status change

### External Script Configuration

An alert script can be trigger if enable via this config file parameter


##### `alert-script` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Full path to an alerting script. |
| Type | String |
| Default Value | "" |  

The following arguments are passed to the script

- [x] Server URL
- [x] Server previous state
- [x] Server current state

### Email Configuration

An email can be send via postfix using the following parameters

##### `mail-from` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Alert email sender, a valid postfix user should be used. |
| Type | String |
| Example | "user@hostname" |  

##### `mail-smtp-addr` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Alert email SMTP server in host:[port] format. |
| Type | String |
| Example | "localhost:25" |  

##### `mail-to` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Single email to send the alert. |
| Type | String |
| Example | "alert@signal18.io" |  


>__Important Note__ No secure mail server is supported .

### Alerting from logs

The logs can be send to syslog services via

##### `log-syslog` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Duplicate messages to syslog. |
| Type | Boolean |
| Default Value | false |  


User can lookup for tag type=state to trigger some custom alerting

We can improve log facilities to send messages to various log analyze systems, contact signal18.io for NRE


### External status monitoring

The API provide some useful endpoint to check for status

Checking for monitoring daemon status can be done via URL     
http://replicaion-manager-host:3000/api/status
```
 {"alive": "running"}
 {"alive": "starting"}
```

http://replicaion-manager-host:3000/api/clusters/{clusterName}/status

```
{"alive": "running"}
{"alive": "errors"}
```
http://replicaion-manager-host:3000/api/clusters/{clusterName}/topology/alerts

### Client call checking status  

replication-manager-cli status  
```
running
```
replication-manager-cli status  --cluster=cluster_haproxy_masterslave
```
errors
```

replication-manager-cli status  --cluster=cluster_haproxy_masterslave --with-errors
```
{
	"errors": [
		{
			"number": "ERR00021",
			"desc": "All cluster db servers down",
			"from": "TOPO"
		},
		{
			"number": "ERR00010",
			"desc": "Could not find a slave in topology",
			"from": "TOPO"
		},
		{
			"number": "ERR00012",
			"desc": "Could not find a master in topology",
			"from": "TOPO"
		}
	],
	"warnings": [
		{
			"number": "INF00001",
			"desc": "Server 127.0.0.1:3310 is down",
			"from": "TOPO"
		}
	]
}
```

replication-manager-cli bootstrap  --cluster=cluster_haproxy_masterslave
```
Can't found topology after bootstrap
```

The cluster is not provisioned  launch it manually or via the replication-manager-tst or replication-manager-pro release

replication-manager-cli bootstrap  --cluster=cluster_haproxy_masterslave --with-provisioning
```
Provisioning done
```
replication-manager-cli status  --cluster=cluster_haproxy_masterslave
```
running
```
