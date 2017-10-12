---
title: ProxySQL Configuration
---

### ProxySQL Configuration

##### `proxysql` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Enable Driving ProxySQL. |
| Type | boolean |
| Default Value | false |  

##### `proxysql-binary-path` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Full path to ProxySQL binary. |
| Type | String |
| Default Value | "/usr/sbin/proxysql" |  

##### `proxysql-servers` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Comma separated list of the ProxySQL hosts. |
| Type | String |
| Default Value | "127.0.0.1" |  

##### `proxysql-write-port` (2.0)

| Item | Value |
| ---- | ----- |
| Description | ProxySQL port to get database connection in WRITE. |
| Type | Integer |
| Default Value | 3306 |  

##### `proxysql-ip-write-bind` (2.0)

| Item | Value |
| ---- | ----- |
| Description | If WRITE traffic bing to specific IP. |
| Type | String |
| Default Value | "0.0.0.0" |  

##### `proxysql-read-port` (2.0)

| Item | Value |
| ---- | ----- |
| Description | ProxySQL port to load balance read connection to all databases. |
| Type | Integer |
| Default Value | 3306 |  

##### `proxysql-ip-read-bind` (2.0)

| Item | Value |
| ---- | ----- |
| Description |  If READ traffic bing to specific ProxySQL IP. |
| Type | String |
| Default Value | "0.0.0.0" |  

##### `proxysql-stat-port` (2.0)

| Item | Value |
| ---- | ----- |
| Description | ProxySQL port to collect statistics.  |
| Type | Integer |
| Default Value | 1988 |  
