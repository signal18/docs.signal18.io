---
title: Tag Reference
taxonomy:
    category: docs
---

## Database Tags

Tags are set via `prov-db-tags` (comma-separated). Each tag activates a matching ruleset in the compliance module, which contributes one or more `.cnf` fragments to the generated configuration. Tags can also be added at runtime through the GUI or API without a cluster restart.

```toml
prov-db-tags = "innodb,noquerycache,threadpool,slow,pfs,semisync,row,ssl,docker,linux"
```

### Storage Engines

| Tag | Effect |
|---|---|
| `innodb` | Enable InnoDB as default engine; tune buffer pool, log files, IOPS capacity |
| `myrocks` | Enable MyRocks (RocksDB) engine; tune block cache |
| `tokudb` | Enable TokuDB engine; tune TokuDB cache |
| `s3` | Enable S3 engine (column-store cold storage); tune S3 page cache |
| `spider` | Install Spider storage engine plugin |
| `sphinx` | Install Sphinx search engine plugin |
| `blackhole` | Install BLACKHOLE engine (replication topology use) |
| `connect` | Install CONNECT engine plugin |
| `mroonga` | Install Mroonga full-text search engine |
| `oqgraph` | Install OQGRAPH graph engine |
| `archive` | Install ARCHIVE engine plugin |
| `wsrep` | Configure Galera Cluster (wsrep) settings |

### Disk and I/O

| Tag | Effect |
|---|---|
| `ssd` | Tune I/O settings for SSD (higher concurrency, `innodb_flush_neighbors=0`) |
| `zfs` | Enable ZFS-specific tuning (disable `O_DIRECT`, tune page size) |
| `nodoublewrite` | Disable InnoDB doublewrite buffer (`innodb_doublewrite=OFF`) |
| `noodirect` | Disable `O_DIRECT` flush method |
| `noaio` | Disable asynchronous I/O (`innodb_use_native_aio=0`) |
| `smallredolog` | Reduce InnoDB redo log size to 128 MB (dev/test) |
| `nodurable` | Relax durability settings (`innodb_flush_log_at_trx_commit=2`, `sync_binlog=0`) |
| `autodefrag` | Enable online InnoDB defragmentation (`innodb_defragment=ON`) |
| `compresstable` | Enable InnoDB page compression by default |
| `compressbinlog` | Enable binary log compression (`log_bin_compress=ON`) |

### Logging

| Tag | Effect |
|---|---|
| `slow` | Enable slow query log |
| `general` | Enable general query log |
| `sqlerror` | Enable SQL error log plugin |
| `pfs` | Enable Performance Schema |
| `userstats` | Enable user statistics (QUERY_RESPONSE_TIME plugin, `userstat=ON`) |
| `metadatalocks` | Enable metadata lock instrumentation plugin |
| `audit` | Enable MariaDB Audit Plugin (SERVER_AUDIT) |
| `logtotable` | Route log output to `mysql.general_log` / `mysql.slow_log` tables |
| `diskmonitor` | Enable disk usage monitoring |

### Network and Threads

| Tag | Effect |
|---|---|
| `threadpool` | Enable MariaDB thread pool |
| `noquerycache` | Disable query cache (`query_cache_size=0`, `query_cache_type=0`) |
| `resolvdns` | Enable DNS hostname resolution (`skip_name_resolve=OFF`) |
| `proxyprotocol` | Enable PROXY protocol support |

### Replication

| Tag | Effect |
|---|---|
| `row` | Set binary log format to ROW |
| `statement` | Set binary log format to STATEMENT |
| `nobinlog` | Disable binary logging |
| `semisync` | Enable semi-synchronous replication |
| `nologslaveupdates` | Disable `log_slave_updates` on replicas |
| `multidomains` | Enable multi-domain replication (unique domain IDs per cluster) |
| `gtidstrict` | Enable GTID strict mode |
| `mysqlgtid` | Use MySQL-compatible GTID format |
| `idempotent` | Set `slave_exec_mode=IDEMPOTENT` |
| `lossyconv` | Allow lossy type conversions on replicas |
| `readonly` | Set server to read-only mode |

### Security

| Tag | Effect |
|---|---|
| `ssl` | Generate and deploy TLS certificates; configure `ssl-ca`, `ssl-cert`, `ssl-key` |
| `pwdchecksimple` | Install `simple_password_check` plugin |
| `pwdcheckcracklib` | Install `cracklib_password_check` plugin (requires OS cracklib library) |
| `encryptfile` | Enable file-at-rest encryption |
| `localinfile` | Enable `LOCAL INFILE` (disabled by default for security) |

### Optimizer

| Tag | Effect |
|---|---|
| `compresstables` | Enable compressed row format for InnoDB tables |
| `lowercasetable` | Set `lower_case_table_names=1` |
| `sqlmodeunstrict` | Clear SQL mode (permissive mode, no `STRICT_TRANS_TABLES`) |
| `sqlmodeoracle` | Set `sql_mode=ORACLE` |
| `noautocommit` | Disable autocommit (`autocommit=0`) |
| `eits` | Enable extended index and table statistics (`userstat=ON`, `use_stat_tables=PREFERABLY_FOR_QUERIES`) |
| `readcommitted` | Set transaction isolation to READ COMMITTED |
| `readuncommitted` | Set transaction isolation to READ UNCOMMITTED |
| `reapeatableread` | Set transaction isolation to REPEATABLE READ |
| `serialized` | Set transaction isolation to SERIALIZABLE |
| `hashjoin` | Set `join_cache_level=8` (hash join) |
| `mrrjoin` | Set `join_cache_level=6` (MRR join) |
| `nestedjoin` | Set `join_cache_level=2` (nested loop) |
| `subquerycache` | Enable `SUBQUERY_CACHE` optimizer switch |
| `semijoincache` | Enable `SEMIJOIN_WITH_CACHE` optimizer switch |
| `firstmatch` | Enable `FIRSTMATCH` optimizer switch |
| `extendedkeys` | Enable `EXTENDED_KEYS` optimizer switch |
| `loosescan` | Enable `LOOSESCAN` optimizer switch |
| `noicp` | Disable `INDEX_CONDITION_PUSHDOWN` |
| `nointoexists` | Disable `IN_TO_EXISTS` rewrite |
| `noderivedmerge` | Disable `DERIVED_MERGE` |
| `noderivedwithkeys` | Disable `DERIVED_WITH_KEYS` |
| `nomrr` | Disable `MRR` optimizer switch |
| `noouterjoincache` | Disable `OUTER_JOIN_WITH_CACHE` |
| `nosemijoincache` | Disable `SEMI_JOIN_WITH_CACHE` |
| `notableelimination` | Disable `TABLE_ELIMINATION` |

### Character Set

| Tag | Effect |
|---|---|
| `bm4ci` | Set `character_set_server=utf8mb4`, case-insensitive collation |
| `bm4cs` | Set `character_set_server=utf8mb4`, case-sensitive collation |
| `utf8ci` | Set `character_set_server=utf8`, case-insensitive collation |
| `utf8cs` | Set `character_set_server=utf8`, case-sensitive collation |

### Platform / Orchestrator

| Tag | Effect |
|---|---|
| `docker` | Adjust paths for container filesystems (`.system` → `/var/lib/mysql/.system`) |
| `linux` | Use Linux-specific startup and socket paths |
| `rpm` | Use RPM-style paths (`/etc/my.cnf.d/`, Red Hat layout) |
| `package` | Use Debian/Ubuntu package layout |
| `nosplitpath` | Flatten all `.system` sub-paths back into the root datadir. Logs, replication relay files, and InnoDB undo/redo directories are placed directly in `{datadir}` instead of `{datadir}/.system/…`. The maintenance job directory moves to `/var/lib/replication-manager-jobs`. Use this when backup tooling, snapshots, or storage layout cannot accommodate a hidden `.system` subdirectory inside the datadir. Adding or removing this tag at runtime triggers automatic config regeneration and a scheduled restart. |

---

## Proxy Tags

Proxy tags are set via `prov-proxy-tags`. Available tags for ProxySQL, HAProxy, and MaxScale:

| Tag | Effect |
|---|---|
| `readonmaster` | Allow read queries on the primary server (set automatically from `db-servers-read-on-master`) |
| `ssl` | Enable TLS on proxy connections |
| `docker` | Container filesystem paths |
| `linux` | Linux socket and init paths |

---

## Compliance Module

Tags are matched against the embedded compliance module (`share/opensvc/moduleset_mariadb.svc.mrm.db.json`). Each tag corresponds to a **filterset** (fset) in the module. A filterset groups one or more rulesets that each contribute:

- A `.cnf` file fragment placed under `etc/mysql/conf.d/`
- A symlink in `etc/mysql/rc.d/` that controls load order
- Optional `# mariadb_command:` or `# mariadb_default:` SQL lines for runtime application

You can supply a custom compliance module file instead of the embedded one:

```toml
prov-db-compliance  = "/etc/replication-manager/custom_db_module.json"
prov-proxy-compliance = "/etc/replication-manager/custom_proxy_module.json"
```
