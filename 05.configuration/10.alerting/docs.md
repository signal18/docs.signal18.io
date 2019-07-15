---
title: Alerting Configuration
---

## Alert Configuration

**replication-manager** offers multiple way of alerting on cluster node status change.

### External Script Configuration

An alert script can be triggered when enabled via this config file parameter:

##### `monitoring-ignore-errors` (2.1)

| Item | Value |
| ---- | ----- |
| Description | List errors or warnings to be ignored. |
| Type | String |
| Default Value | "WARN0067,WARN0066" |  


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

An email can be send via postfix using the following parameters:

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

##### `mail-smtp-user` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Auth SMTP User |
| Type | String |
| Default | "" |  

##### `mail-smtp-password` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Auth SMTP password |
| Type | String |
| Default | "" |  

##### `mail-to` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Single email to send the alert. |
| Type | String |
| Example | "alert@signal18.io" |  


>__Important Note__ No secure mail server is supported .

### Alerting from slack

>__ Slack reporting is common to all clusters

##### `alert-slack-url` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Slack webhook URL to alert. |
| Type | String |
| Default Value | "" |  

##### `alert-slack-channel` (2.1)

| Item | Value |
| ---- | ----- |
| Description |Slack channel to alert. |
| Type | String |
| Default Value | "#support" |  


##### `alert-slack-user` (2.1)

| Item | Value |
| ---- | ----- |
| Description |Slack user for alert. |
| Type | String |
| Default Value | "" |  


### Alerting from logs

The logs can be send to syslog services via

##### `log-syslog` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Duplicate messages to syslog. |
| Type | Boolean |
| Default Value | false |  


User can lookup in logs for tag type=state to trigger some custom alerting.

We can improve log facilities to send messages to various log analyze systems, contact signal18.io for NRE.


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
