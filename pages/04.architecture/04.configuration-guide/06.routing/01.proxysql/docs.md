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

## Backend rows managed by replication-manager

With `proxysql-bootstrap` on, replication-manager owns the `mysql_servers`
rows of its servers in three hostgroups:

| Hostgroup | Holds |
| --------- | ----- |
| `proxysql-writer-hostgroup` | the master |
| `proxysql-reader-hostgroup` | the replicas, and the master when [`proxy-servers-read-on-master`](../) applies |
| `666` | the writer row of a server that must not take writes (standalone, ignored, or the old master after a failover) |

Hostgroup `666` is replication-manager's offline hostgroup, not a leftover:
no user or query rule routes to it. Do not delete its rows by hand while their
server is standalone.

A row's `status` follows the server's state in replication-manager:

- To drain a server, put it in **maintenance** in replication-manager. Its
  rows are set `OFFLINE_SOFT` and stay so until maintenance ends.
- From 3.1.48, a master whose writer or reader row is `OFFLINE_SOFT` outside
  maintenance is set back `ONLINE` on the next monitoring tick (its reader row
  is dropped instead when the master should not serve reads). A drain made
  directly in the ProxySQL admin interface is therefore undone. Without
  `proxysql-bootstrap`, replication-manager never changes the rows.

### Version 3.1.48, PR #2296 and #2299: master back from a failure

Before 3.1.48, a master coming back through `Failed`, `StandAlone`, then
`Master` could stay `OFFLINE_SOFT` on every ProxySQL until fixed by hand. It
happened when the server also had a row left in hostgroup `666`, and the
replication-manager log showed:

```
Monitor ProxySQL setting online failed server <host>:<port>: Error 1045 (28000):
ProxySQL Admin Error: UNIQUE constraint failed: mysql_servers.hostgroup_id,
mysql_servers.hostname, mysql_servers.port
```

replication-manager now removes the stale `666` row, keeps the writer row with
its weight and connection settings, and sets it `ONLINE`. A master left in that
state by an earlier version is repaired on the first monitoring tick after the
upgrade. The master's own reader row, drained while it was standalone, is set
back `ONLINE` or dropped as described above.

| Code | Meaning |
| ---- | ------- |
| `ERR00071` | ProxySQL could not set the master back as writer. Now raised (it was only logged), with the ProxySQL error. |
| `ERR00091` | A server that is not in maintenance is `OFFLINE_SOFT` in ProxySQL. With bootstrap on, it clears once replication-manager has repaired the row. |
| `ERR00121` | ProxySQL could not set the master's `OFFLINE_SOFT` reader row back `ONLINE` or drop it. New in 3.1.48. |

On earlier versions, repair a stuck master on every ProxySQL:

```sql
-- drop the stale offline row only where a writer row exists
DELETE FROM mysql_servers WHERE hostgroup_id=666
  AND EXISTS (SELECT 1 FROM mysql_servers w WHERE w.hostgroup_id=0
              AND w.hostname=mysql_servers.hostname AND w.port=mysql_servers.port);
-- set the master's rows back ONLINE (replace host and port)
UPDATE mysql_servers SET status='ONLINE'
  WHERE hostname='<master host>' AND port=<master port> AND hostgroup_id IN (0,1);
LOAD MYSQL SERVERS TO RUNTIME;
SAVE MYSQL SERVERS TO DISK;
```

Replace `0` and `1` with your writer and reader hostgroups. Do not run
`UPDATE ... WHERE status='OFFLINE_SOFT'` without a host: it would also bring
back servers that are in maintenance.

### Version 3.1.42, PR #1767: Kubernetes provisioning

Kubernetes native provisioning supports ProxySQL with an admin Service port, a
SQL Service port, persistent storage, and startup config fetching. Proxy names
must satisfy Kubernetes RFC 1035 Service-name rules. The detailed lifecycle,
storage, and naming behavior is maintained in the
[Kubernetes proxy provisioning guide](../../../../10.provisioning/01.orchestrators/03.kubernetes).
