---
title: ProxySQL Configuration
---

### ProxySQL Configuration

##### `proxysql` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Enable ProxySQL driver |
| Type | boolean |
| Default Value | false |  

##### `proxysql-servers` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Comma separated list of ProxySQL hosts |
| Type | String |
| Default Value | "127.0.0.1" |  

##### `proxysql-port` (2.0)

| Item | Value |
| ---- | ----- |
| Description | ProxySQL port to get database connection  |
| Type | String |
| Default Value | "6033" |  

##### `proxysql-admin-port` (2.0)

| Item | Value |
| ---- | ----- |
| Description | ProxySQL port to get admin connection |
| Type | String |
| Default Value | "6032" |  

##### `proxysql-writer-hostgroup` (2.0)

| Item | Value |
| ---- | ----- |
| Description | ProxySQL writer hostgroup ID |
| Type | String |
| Default Value | "0" |  

##### `proxysql-reader-hostgroup` (2.0)

| Item | Value |
| ---- | ----- |
| Description | ProxySQL reader hostgroup ID |
| Type | String |
| Default Value | "1" |  

##### `proxysql-user` (2.0)

| Item | Value |
| ---- | ----- |
| Description | ProxySQL admin user name |
| Type | String |
| Default Value | "admin" |

##### `proxysql-password` (2.0)

| Item | Value |
| ---- | ----- |
| Description | ProxySQL admin password |
| Type | String |
| Default Value | "admin" |
