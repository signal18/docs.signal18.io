---
title: Server Configuration
---

## Minimal configuration

This is a minimal configuration sample required to run `replication-manager`:

```
[Default]
title = "ClusterTest"
db-servers-hosts = "127.0.0.1:5055,127.0.0.1:5056"
db-servers-credential = "skysql:skyvodka"
replication-credential = "skysql:skyvodka"
failover-mode = "manual"

```
## Server daemon configuration

##### `config`

| Item | Value |
| ---- | ----- |
| Description | Full path to configuration file. |
| Type | string |
| Default | "/etc/replication-manager/config.toml" |

##### `cluster` (2.0), `config-group` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Only monitor the given cluster list. cluster are define with sections like [cluster1], every parameters from the [default] section apply to all cluster, and are overwrite by the cluster section, any command line parameters will be ignored if they are define in a section.  |
| Type | list |
| Example | "cluster1,cluster2" |

##### `log-file`

| Item | Value |
| ---- | ----- |
| Description | Write messages to log file per default messages are output to stdout. |
| Type | string |
| Example | "/var/log/replication-manager.log" |

##### `log-file` (2.0), `logfile` (0.6)

| Item | Value |
| ---- | ----- |
| Description | Write messages to log file per default messages are output to stdout|
| Type | string |
| Example | "/var/log/replication-manager.log" |

##### `log-level`

| Item | Value |
| ---- | ----- |
| Description | Log verbosity level, og level >3 are very verbose and print the server monitoring workflow only to use in debugging |
| Type | int |
| Example | 1 to 7 |

##### `log-syslog ` (2.0)

| Item          | Value |
| ----          | ----- |
| Description   | duplicate stdout to local UDP syslog port |
| Type          | boolean |
| Example       | true |

##### `log-rotate` (2.1)

| Item          | Value |
| ----          | ----- |
| Description   | Rotate log file  |
| Type          | boolean |
| Example       | true |

##### `verbose` (2.1)

| Item          | Value |
| ----          | ----- |
| Description   | Add more debugging information |
| Type          | int |
| Example       | 1 |

##### `memprofile`

| Item          | Value |
| ----          | ----- |
| Description   | Write a memory profile to a file readable by pprof. |
| Type          | string |
| Default       | "/tmp/repmgr.mprof" |


##### `monitoring-datadir` (2.0), `working-directory` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Path to write temporary and persistent files |
| Type          | string |
| Example       | "/var/lib/replication-manager" |

##### `monitoring-sharedir` (2.0), `share-directory` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Path to the share files provided with packaging with no write access |
| Type          | string |
| Default Value | "/usr/share/replication-manager" |

##### `monitoring-ticker` (2.0), `read-timeout` (1.1)

| Item          | Value |
| ----          | ----- |
| Description   | Monitoring interval in seconds. |
| Type          | integer |
| Default Value | 2 |

##### `monitoring-basedir` (2.0)

| Item          | Value |
| ----          | ----- |
| Description   | Path to a basedir where a data and share directory can be found, used mostly for developer or collocation of the product with a tar.gz deployment. |
| Type          | string |
| Default Value | "/usr/local/replication-manger" |

##### `title`

| Item          | Value |
| ----          | ----- |
| Description   | An explicit description of the managed cluster  |
| Type          | string |
| Default Value | "CRM production MariaDB cluster" |

### Deprecated

##### `daemon`  (2.0)

| Item          | Value |
| ----          | ----- |
| Description   | Tell to monitor in post 2.0 release |
| Type          | integer |
| Default Value | 1 |
