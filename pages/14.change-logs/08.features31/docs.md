---
title: 3.1 Features
taxonomy:
    category: docs
---

### 13.8.0.1 Features

#### 13.8.0.1.1 Monitoring Enhancements

* **Sleeping Transactions Monitoring**: Monitor long-running transactions including sleeping transactions that hold InnoDB locks. Tracks transaction duration, isolation level, tables locked, rows modified, and memory usage. [See documentation](/configuration/monitoring/processlist-monitoring)

* **Performance Schema Mutex Monitoring**: Collect InnoDB mutex wait events from Performance Schema. Identifies mutex contention and performance bottlenecks. [See documentation](/configuration/monitoring/performance-schema)

* **Performance Schema Latch Monitoring**: Monitor InnoDB read-write lock (rwlock) wait events. Diagnoses concurrency issues with read/write operations. [See documentation](/configuration/monitoring/performance-schema)

* **Performance Schema Memory Monitoring**: Track memory consumption by InnoDB subsystems via Performance Schema memory instrumentation. [See documentation](/configuration/monitoring/performance-schema)

* **Per-Module Log Levels**: Configure log verbosity independently for each subsystem (backup, proxy, heartbeat, scheduler, etc.). 20+ new `log-level-*` parameters for fine-grained logging control

* **Schema Change Script Hook**: The `monitoring-schema-change-script` config key is now wired into the monitoring loop. Called when a table is created, altered, or dropped with a column diff piped to stdin showing exactly what changed. [See documentation](/provisioning/orchestrators/scripts#monitoring-schema-change-script-31)

* **Config Tracking Fixes**: Prevent empty `dummy.cnf` overwrite on repman shutdown that caused all variables to show as "Deprecated (loose)". Delta computation skipped when server is down. New GUI Config Files panel with Clear Delta button. [See documentation](/provisioning/configurator/config-tracking)

* **On-Premise Rolling Upgrade**: New upgrade scripts for Debian and RHEL with version pinning from `prov-db-docker-img`. Two-axis `db_distributions.json` for OS family × deploy method, pushed by back office. `StopDatabaseServiceClean` with `innodb_fast_shutdown=0` for safe major version upgrades. [See documentation](/provisioning/configurator/distributions)

* **OpenSVC Instance-Level Start/Stop**: Switch from orchestrated to per-node instance API to avoid 409 "warn state" errors. Optimistic clear before start. New `opensvc-use-orchestrated-start` flag for HA-safe abort+restart recovery. [See documentation](/provisioning/configurator/distributions#opensvc-use-orchestrated-start)

* **Server Menu: Restart and Upgrade**: New "Restart Database" (generic stop+start for all orchestrators) and "Upgrade Database" (clean stop + upgrade script) entries in the server context menu.

* **Docker Image Version Sort**: Version dropdown in the configurator now sorts by semantic version descending (newest first) instead of alphabetically.

#### 13.8.0.1.2 Topology

* **Active-Passive Topology**: New topology mode for monitoring single servers or multiple independent servers without replication management. Disables automatic failover while maintaining full monitoring capabilities. [See documentation](/architecture/topologies/active-passive)

#### 13.8.0.1.3 Backup Enhancements

* **Restic Task Management**: Background task queue for backup operations with pause/resume/cancel support via API

* **Automatic Disk Space Management**: Restic automatically purges oldest backups when disk space is critically low (`backup-restic-purge-oldest-on-disk-space`)

* **Disk Threshold Configuration**: Separate thresholds for warning and critical disk usage (`backup-disk-threshold-warn`, `backup-disk-threshold-crit`)

* **Restic Timeout Control**: Configure operation timeouts for long-running backup/restore operations (`backup-restic-timeout`)

* **Local Repository Support**: Use local filesystem repositories with per-cluster isolation (`backup-restic-local-repository`)

* **MariaDB Backup Tools**: Native support for `mariadb-backup` and `mariadb-import` (formerly `maria-backup`)

#### 13.8.0.1.4 Configuration Management

* **Configurator Diff Tracking**: Track differences between proposed and current configuration

* **Preserve Remote Configuration**: Configurator maintains remote settings during updates

* **Deprecated Configuration Detection**: Automatically identify outdated configuration parameters

#### 13.8.0.1.5 Backup & Restore

* **Cross-Cluster User Restore**: Restore backups between clusters while merging database USERS tables

#### 13.8.0.1.6 Provisioning

* **OpenSVC Go Agent**: Migration to new OpenSVC go-based agent

* **OpenSVC Service Logging**: Enhanced logging for OpenSVC agent operations

* **OpenSVC Failover Monitoring**: Split-brain detection and monitoring for OpenSVC clusters

#### 13.8.0.1.7 API & Integration

* **Application Deployments**: Support for managing application deployments via peering

* **Compact JSON Responses**: Reduced API response sizes for improved performance

* **OAuth Authentication**: OAuth2 provider integration for API authentication (parameters: `api-oauth-client-id`, `api-oauth-client-secret`, `api-oauth-provider-url`, `api-oauth-redirect-url`)

#### 13.8.0.1.8 Scheduler

* **Enhanced Scheduler System**: Comprehensive task scheduling with cron support for database maintenance, backups, and administrative operations

* **Dual-Mode Job Dispatch (sql/api)**: New `scheduler-jobs-mode` configuration. In `api` mode, tasks are dispatched via REST API cookies instead of polling the SQL jobs table, removing the need for the `replication_manager_schema.jobs` table on database hosts. The dbjobs script authenticates via JWT and checks task availability through the replication-manager API. Default mode remains `sql` for full backward compatibility. [See documentation](/maintenance/overview#6-1-8-job-dispatch-modes-since-3-x)

* **Configurable Task Execution Mode**: New `scheduler-jobs-exec-remote` parameter allows forcing specific tasks to run remotely via the dbjobs script instead of locally in replication-manager. Useful for tasks like `mysqldump` where the client binary version must match the database server version. [See documentation](/maintenance/overview#6-1-8-5-task-execution-mode)

* **Embedded dbjobs Script**: The dbjobs maintenance script is now embedded directly in the replication-manager binary via `go:embed`, bypassing the 65535-byte truncation limit of the OpenSVC compliance moduleset database column. Existing deployments receive the full script automatically via the built-in script auto-upgrade mechanism.

* **Job API Security**: Job dispatch API endpoints now require the `db-jobs` ACL grant and JWT authentication. The `system` service account created by `secret-login` receives this grant automatically as part of the `db` grant group.

* **Schema Monitoring Default**: Default cron for schema monitoring changed from once-a-year (`0 0 1 1 * *`) to daily at 2 AM (`0 0 2 * * *`). On startup, if no cached table metadata exists, a one-time schema scan is triggered automatically.

* **Optimize Cron Fix**: Default optimize cron fixed from `0 0 3 1 * 5` (1st of month AND Friday — rare) to `0 0 3 1 * *` (1st of every month at 3 AM).

* **Metadata Lock Avoidance**: The jobs table creation now checks `information_schema` before issuing DDL, avoiding unnecessary `CREATE DATABASE IF NOT EXISTS` and `CREATE TABLE IF NOT EXISTS` statements that could hold metadata locks during backups.

* **Rolling Restart/Reprovision**: Schedule automatic rolling restarts and reprovisioning operations

* **SLA Rotation**: Automated SLA metric rotation via scheduler

#### 13.8.0.1.9 Database Compatibility

* **MySQL 8.4 Support**: Version parsing and SSL mode compatibility fixes for MySQL 8.4

* **PostgreSQL Enhancements**: Improved PostgreSQL logical and streaming replication support

#### 13.8.0.1.10 Performance & Reliability

* **Job Script Versioning**: Automatic upgrades and version tracking for database job scripts

* **Database Jobs Schema Upgrades**: API endpoint for upgrading jobs schema

* **SQL Error Tailers**: Monitor and tail SQL error logs for analysis

* **Audit Log Tailers**: Capture and monitor audit log entries

#### 13.8.0.1.11 Development & Build

* **SBOM Generation**: Software Bill of Materials generation for security compliance  
