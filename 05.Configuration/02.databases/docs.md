---
title: Database Configuration
---

## Database servers configuration (`db-servers`)

##### `db-servers-hosts` (2.0), `hosts` (1.1)

| Item | Value |
| ---- | ----- |
| Description | List of database hosts to monitor, IP and port (optional), specified in the host:[port] format and separated by a comma |
| Type | list |
| Example | "127.0.0.1:5055,127.0.0.1:5056" |

##### `db-servers-credential` (2.0), `user` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database login with root privileges, specified in the [user]:[password] format |
| Type | string |
| Example | "root:adminpassword" |

##### `db-servers-prefered-master` (2.0), `prefmaster` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Database preferred candidate for master election, in host:[port] format |
| Type          | string |
| Example       | "127.0.0.1:5055" |

##### `db-servers-ignored-hosts` (2.0), `ignore-servers` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Database list of hosts to ignore for master election |
| Type          | list |
| Example       | "127.0.0.1:5057,127.0.0.1:5058" |

##### `db-servers-connect-timeout` (2.0), `connect-timeout` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Database connection timeout in seconds. The server will timeout if the connection cannot be established before that value. |
| Type          | integer |
| Default Value | 5 |

##### `db-servers-read-timeout` (2.0), `read-timeout` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Database I/O read timeout in seconds. The server will timeout if, on an already established connection, no data is received during a period equal to this option's value. |
| Type          | integer |
| Default Value | 15 |

##### `db-servers-tls-ca-cert` (2.0)

| Item          | Value |
| ----          | ----- |
| Description   | Path to the database connection TLS authority certificate. |
| Type          | string |
| Default Value | "" |

##### `db-servers-tls-client-cert` (2.0)

| Item          | Value |
| ----          | ----- |
| Description   | Path to the database connection TLS client certificate. |
| Type          | string |
| Default Value | "" |

##### `db-servers-tls-client-key` (2.0)

| Item          | Value |
| ----          | ----- |
| Description   | Database TLS client key. |
| Type          | string |
| Default Value | "" |

##### `db-servers-binary-path` (2.0), `mariadb-binary-path` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Path to mysqld binary for **replication-manager-tst** package to provisioning local clusters. |
| Type          | string |
| Default Value | "/usr/local/mysql/bin" |
