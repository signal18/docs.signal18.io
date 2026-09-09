---
title: ProxySQL
taxonomy:
    category: docs
---

### 4.5.5.2.0.1 ProxySQL Configuration

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

##### `proxysql-bootstrap` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Auto create servers when start |
| Type | String |
| Default Value | true|

The dashboard and runtime setting API use `proxysql-bootstrap-servers` as the
setting name for this boolean. Do not add `proxysql-bootstrap-servers` as a
TOML key.

##### <span id="proxysql-bootstrap-users"></span> `proxysql-bootstrap-users` (2.3)

| Item | Value |
| ---- | ----- |
| Description | Auto push users when they are discovered |
| Type | String |
| Default Value |true |


##### `proxysql-copy-grants` (2.0) `obsolete` use [`proxysql-bootstrap-users`](#proxysql-bootstrap-users)

| Item | Value |
| ---- | ----- |
| Description | Auto push users when they are discovered |
| Type | String |
| Default Value |true |

##### `proxysql-reader-hostgroup` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Define reader hostgroup |
| Type | Integer |
| Default Value | 1 |

##### `proxysql-writer-hostgroup` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Define writer hostgroup |
| Type | Integer |
| Default Value | 0 |

---

## Bootstrap Control Reference

The following additional bootstrap controls predate version 3.1.42. Their
configuration keys are also used as their dashboard/API setting names.

### Existing ProxySQL bootstrap controls

##### `proxysql-bootstrap-variables` (2.1.2)

> **Available since:** replication-manager **v2.1.2**

| Item | Value |
| ---- | ----- |
| Description | Apply configured ProxySQL global variables |
| Type | Boolean |
| Default Value | false |

##### `proxysql-bootstrap-hostgroups` (2.1.1)

> **Available since:** replication-manager **v2.1.1**

| Item | Value |
| ---- | ----- |
| Description | Configure writer, reader, and backup-writer hostgroups |
| Type | Boolean |
| Default Value | false |

##### `proxysql-bootstrap-query-rules` (2.1.1)

> **Available since:** replication-manager **v2.1.1**

| Item | Value |
| ---- | ----- |
| Description | Load replication-manager's managed query-routing rules |
| Type | Boolean |
| Default Value | false |

Disabling user bootstrap leaves ProxySQL user definitions under operator
control while other backend and routing management can remain enabled.

### Version 3.1.42, PR #1767: Kubernetes provisioning

Kubernetes native provisioning supports ProxySQL with an admin Service port, a
SQL Service port, persistent storage, and startup config fetching. Proxy names
must satisfy Kubernetes RFC 1035 Service-name rules. The detailed lifecycle,
storage, and naming behavior is maintained in the
[Kubernetes proxy provisioning guide](../../../../10.provisioning/01.orchestrators/03.kubernetes).
