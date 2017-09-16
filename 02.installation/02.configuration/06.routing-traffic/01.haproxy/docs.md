---
title: Haproxy Configuration
---

### HaProxy Configuration

**replication-manager** require HaProxy to be install and on the same server it will than start one HaProxy Daemon per Cluster configured for using it.

**replication-manager** generate the HaProxy configuration file. A template is located in the share directory used by replication-manager. For safety HaProxy is not stopped when replication-manager is stopped

**replication-manager** re-generate the HaProxy configuration file when the topology change and instruct HaProxy to reload this configuration during failover and switchover.


##### `haproxy` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Enable Driving HaProxy. |
| Type | boolean |
| Default Value | false |  

##### `haproxy-binary-path` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Full path to HaProxy binary. |
| Type | String |
| Default Value | "/usr/sbin/haproxy" |  

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
