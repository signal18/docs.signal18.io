---
title: Databases
taxonomy:
    category: docs
---

## 4.5.1.1 Database servers configuration (`db-servers`)

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
| Example | "root:password" |

Database login credentials will use IP (not socket). If you are using local configuration with MySQL or MariaDB, please set up credentials and privileges for `127.0.0.1` instead of `localhost`.

```
If the host is not specified or is localhost, a connection to the local host occurs:

On Windows, the client connects using shared memory, if the server was started with the [shared_memory](https://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_shared_memory) system variable enabled to support shared-memory connections.

On Unix, MySQL programs treat the host name localhost specially, in a way that is likely different from what you expect compared to other network-based programs: the client connects using a Unix socket file. The [--socket](https://dev.mysql.com/doc/refman/8.0/en/connection-options.html#option_general_socket) option or the MYSQL_UNIX_PORT environment variable may be used to specify the socket name.
```

##### `db-servers-prefered-master` (2.0), `prefmaster` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Database preferred candidates for master election, in host:[port] format |
| Type          | list |
| Example       | "127.0.0.1:5055,127.0.0.1:5056" |

If `db-servers-prefered-master` specified, it will failover to candidates mentioned list first. `db-servers-prefered-master` does not prevent candidates outside the list to be master. If no suitable candidates found within the list, then it will try to failover outside the list. 
If you want to exclude the database, you need to put the host address within `db-servers-ignored-hosts`.

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

##### `db-servers-monitor-extra-port` (3.1.46)

| Item          | Value |
| ----          | ----- |
| Description   | Port of replication-manager's own connections to the databases, usually MariaDB's `extra_port`. `db-servers-hosts` keeps the service port, the one applications, proxies and replication use. |
| Type          | integer |
| Default Value | 0 (the port of `db-servers-hosts`) |
| Example       | 3307 |

Available from replication-manager 3.1.46. It can be changed live in **Settings → Monitoring → Database Servers Monitor Extra Port**, and the value is kept across restarts.

**Why an extra port.** When a database reaches `max_connections`, new connections are refused with error 1040, including replication-manager's: it can no longer see the server it is supposed to watch. MariaDB's `extra_port` is a second listener with its own connection limit (`extra_max_connections`). Monitoring through it keeps replication-manager connected while the service port is full.

**What moves and what doesn't.** Only replication-manager's own SQL connections go to the extra port:

| Uses `db-servers-monitor-extra-port` | Keeps the port of `db-servers-hosts` |
| ---- | ----- |
| Monitoring checks and the connection pool replication-manager keeps on each server | HAProxy, ProxySQL and MaxScale backends |
| | Replication (`CHANGE MASTER TO ... MASTER_PORT`), rejoin and reseed |
| | Backup tools |
| | The server's name in the GUI and the API (`host:port`) |

! Do not put the extra port in `db-servers-hosts`. replication-manager writes that port into the proxies: with HAProxy in `runtimeapi` mode, the write backend would be repointed to the extra port, sending application writes there, and a read backend left on the service port would no longer match any server and disappear from the dashboard.

**Enable the extra port on MariaDB** (not dynamic, a restart of the database is needed):

```
[mariadb]
extra_port = 3307
extra_max_connections = 10
```

Size `extra_max_connections` for replication-manager: it keeps a pool of about two connections per server, plus one per check and one per job that runs (backups, schema checks). Databases provisioned by replication-manager with the `threadpool` tag already have `extra_port = 3307` and `extra_max_connections = 10`.

**When the extra port does not answer.** If the extra port refuses the connection, times out, or is full (error 1040), replication-manager monitors the server on the service port and raises **WARN0267** ("monitoring extra port ... unusable ..., monitoring on the service port ... until it answers again"). It tries the extra port again after one minute and goes back to it as soon as it answers; WARN0267 then closes. A misconfigured extra port never makes a healthy server look down, and never triggers a failover. An access denied error is not a reason to fall back: the same credentials are used on both ports.

On the dashboard, a server monitored on another port than its service port shows as `host:3306 (monitored on :3307)`.

Not used through an SSH tunnel (`tunnel-host`), nor for PostgreSQL.

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
