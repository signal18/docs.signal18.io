---
title: Configuration Guide
taxonomy:
    category: docs
---

## 1. Configuration Guide

All configurator settings are per-cluster (set under the `[cluster-name]` section or in `[DEFAULT]`).

---

## 2. Database Resource Settings

These settings describe the hardware allocated to each database service. The configurator uses them to size buffer pools, log files, thread counts, and IOPS capacity automatically.

##### `prov-db-memory`

| | |
|---|---|
| Description | Total memory in MiB allocated to the database service. The configurator distributes this across engines according to `prov-db-memory-shared-pct`. |
| Type | String |
| Default | `"256"` |
| Example | `"4096"` |

##### `prov-db-memory-shared-pct`

| | |
|---|---|
| Description | How `prov-db-memory` is split across shared global buffers. Comma-separated `engine:percent` pairs. The `threads` key reserves a share for per-thread buffers (sized by `prov-db-memory-threaded-pct`). |
| Type | String |
| Default | `"threads:16,innodb:60,myisam:10,aria:10,rocksdb:1,tokudb:1,s3:1,archive:1,querycache:0"` |

##### `prov-db-memory-threaded-pct`

| | |
|---|---|
| Description | How the `threads` share from `prov-db-memory-shared-pct` is split across per-connection thread buffers. |
| Type | String |
| Default | `"tmp:70,join:20,sort:10"` |

##### `prov-db-disk-size`

| | |
|---|---|
| Description | Disk space in GiB for the database data volume. |
| Type | String |
| Default | `"20"` |

##### `prov-db-disk-iops`

| | |
|---|---|
| Description | Provisioned random IOPS for the database disk. Used to size `innodb_io_capacity` (set to IOPS/3) and `innodb_io_capacity_max` (set to IOPS). |
| Type | String |
| Default | `"300"` |

##### `prov-db-disk-iops-latency`

| | |
|---|---|
| Description | Average disk I/O latency in seconds. Used with `prov-db-disk-iops` to calculate the optimal number of InnoDB write I/O threads (`innodb_write_io_threads = latency × iops`). |
| Type | String |
| Default | `"0.002"` |

##### `prov-db-cpu-cores`

| | |
|---|---|
| Description | Number of CPU cores available to the database service. Sets `thread_pool_size` and `innodb_read_io_threads`. |
| Type | String |
| Default | `"1"` |

---

## 3. Tag Settings

##### `prov-db-tags`

| | |
|---|---|
| Description | Comma-separated list of compliance tags applied to all database servers in this cluster. Each tag activates one or more ruleset fragments in the compliance module. |
| Type | String |
| Default | `"semisync,row,innodb,noquerycache,threadpool,slow,pfs,docker,linux,readonly,diskmonitor,sqlerror,compressbinlog"` |
| Example | `"innodb,semisync,row,slow,pfs,ssl,threadpool,noquerycache"` |

##### `prov-proxy-tags`

| | |
|---|---|
| Description | Comma-separated list of compliance tags applied to proxy servers (ProxySQL, HAProxy, MaxScale). |
| Type | String |
| Default | `""` |

---

## 4. Config Preservation

##### `prov-db-config-preserve`

| | |
|---|---|
| Description | Include the three-layer override files (`01_preserved.cnf`, `02_delta.cnf`, `03_agreed.cnf`) in the generated `config.tar.gz`. Set to `false` to generate a clean config from tags only (useful for resetting a server to a known-good state). |
| Type | Boolean |
| Default | `true` |

##### `prov-db-config-preserve-vars`

| | |
|---|---|
| Description | Semicolon-separated list of variable names or `name=value` pairs to hard-code into `01_preserved.cnf` on every regeneration. Values listed here survive all tag changes and config updates. |
| Type | String |
| Default | `""` |
| Example | `"innodb_data_home_dir=/var/lib/mysql;max_connections=1000"` |

---

## 5. Compliance Module Override

##### `prov-db-compliance`

| | |
|---|---|
| Description | Path to a custom compliance module JSON file for database configuration. If unset, replication-manager uses the embedded `moduleset_mariadb.svc.mrm.db.json`. |
| Type | String |
| Default | `""` (use embedded module) |
| Example | `"/etc/replication-manager/custom_db_module.json"` |

##### `prov-proxy-compliance`

| | |
|---|---|
| Description | Path to a custom compliance module JSON file for proxy configuration. |
| Type | String |
| Default | `""` (use embedded module) |

---

## 6. Replication and Domain

##### `prov-db-expire-log-days`

| | |
|---|---|
| Description | Value for `expire_logs_days` in the generated configuration. |
| Type | Integer |
| Default | `5` |

##### `prov-db-max-connections`

| | |
|---|---|
| Description | Value for `max_connections` in the generated configuration. |
| Type | Integer |
| Default | `1000` |

##### `prov-domain`

| | |
|---|---|
| Description | MariaDB replication domain ID. Used to avoid conflicts in multi-source setups. If set to `"0"` and the cluster has a `master-conn` (multi-source), the domain is calculated automatically from a CRC32 of the cluster name. |
| Type | String |
| Default | `"0"` |

---

## 7. Binary Distribution

##### `prov-db-binary-in-tarball`

| | |
|---|---|
| Description | Bundle the MariaDB/MySQL binary tarball inside `config.tar.gz` for air-gapped environments. Requires `prov-db-binary-tarball-name` to be set. |
| Type | Boolean |
| Default | `false` |

##### `prov-db-binary-tarball-name`

| | |
|---|---|
| Description | Filename of the binary tarball to bundle. The tarball is fetched from the URL returned by the `GetTarballUrl` lookup before being included. |
| Type | String |
| Default | `"mysql-8.0.17-macos10.14-x86_64.tar.gz"` |

---

## 8. Start Behavior

##### `prov-db-start-fetch-config`

| | |
|---|---|
| Description | When `true`, replication-manager signals each server to re-fetch the config archive on the next start (the `start` script checks `/need-config-fetch` and downloads a fresh `config.tar.gz`). Set to `false` to suppress config fetching — the database starts with whatever config files are already on disk. |
| Type | Boolean |
| Default | `true` |

---

## 9. Bootstrap Scripts

##### `prov-db-bootstrap-script`

| | |
|---|---|
| Description | Path to a custom shell script run on the database host at first bootstrap (after the config archive is unpacked). If unset, the built-in bootstrap script for the target OS/package layout is used. |
| Type | String |
| Default | `""` |

##### `prov-proxy-bootstrap-script`

| | |
|---|---|
| Description | Path to a custom shell script run on proxy hosts at first bootstrap. |
| Type | String |
| Default | `""` |

---

## 10. Security

##### `api-credentials-secure-config`

| | |
|---|---|
| Description | Require a valid JWT Bearer token to download `config.tar.gz` from the `/api/clusters/{name}/servers/{host}/{port}/config` endpoint. When `false` (default), the endpoint is open so init containers can bootstrap without pre-provisioned credentials. |
| Type | Boolean |
| Default | `false` |

---

## 11. Proxy Resources

##### `prov-proxy-memory`

| | |
|---|---|
| Description | Memory in MiB allocated to proxy service containers. |
| Type | String |
| Default | `"256"` |

##### `prov-proxy-cpu-cores`

| | |
|---|---|
| Description | CPU cores allocated to proxy service containers. |
| Type | String |
| Default | `"1"` |

##### `prov-proxy-disk-size`

| | |
|---|---|
| Description | Disk in GiB for proxy service containers. |
| Type | String |
| Default | `"2"` |
