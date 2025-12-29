---
title: 3.1 Features
taxonomy:
    category: docs
---

### 3.1 Features

#### Monitoring Enhancements

* **Sleeping Transactions Monitoring**: Monitor long-running transactions including sleeping transactions that hold InnoDB locks. Tracks transaction duration, isolation level, tables locked, rows modified, and memory usage. [See documentation](/configuration/monitoring/processlist-monitoring)

* **Performance Schema Mutex Monitoring**: Collect InnoDB mutex wait events from Performance Schema. Identifies mutex contention and performance bottlenecks. [See documentation](/configuration/monitoring/performance-schema)

* **Performance Schema Latch Monitoring**: Monitor InnoDB read-write lock (rwlock) wait events. Diagnoses concurrency issues with read/write operations. [See documentation](/configuration/monitoring/performance-schema)

* **Performance Schema Memory Monitoring**: Track memory consumption by InnoDB subsystems via Performance Schema memory instrumentation. [See documentation](/configuration/monitoring/performance-schema)

* **Per-Module Log Levels**: Configure log verbosity independently for each subsystem (backup, proxy, heartbeat, scheduler, etc.). 20+ new `log-level-*` parameters for fine-grained logging control

#### Topology

* **Active-Passive Topology**: New topology mode for monitoring single servers or multiple independent servers without replication management. Disables automatic failover while maintaining full monitoring capabilities. [See documentation](/architecture/topologies/active-passive)

#### Backup Enhancements

* **Restic Task Management**: Background task queue for backup operations with pause/resume/cancel support via API

* **Automatic Disk Space Management**: Restic automatically purges oldest backups when disk space is critically low (`backup-restic-purge-oldest-on-disk-space`)

* **Disk Threshold Configuration**: Separate thresholds for warning and critical disk usage (`backup-disk-threshold-warn`, `backup-disk-threshold-crit`)

* **Restic Timeout Control**: Configure operation timeouts for long-running backup/restore operations (`backup-restic-timeout`)

* **Local Repository Support**: Use local filesystem repositories with per-cluster isolation (`backup-restic-local-repository`)

* **MariaDB Backup Tools**: Native support for `mariadb-backup` and `mariadb-import` (formerly `maria-backup`)

#### Configuration Management

* **Configurator Diff Tracking**: Track differences between proposed and current configuration

* **Preserve Remote Configuration**: Configurator maintains remote settings during updates

* **Deprecated Configuration Detection**: Automatically identify outdated configuration parameters

#### Backup & Restore

* **Cross-Cluster User Restore**: Restore backups between clusters while merging database USERS tables

#### Provisioning

* **OpenSVC Go Agent**: Migration to new OpenSVC go-based agent

* **OpenSVC Service Logging**: Enhanced logging for OpenSVC agent operations

* **OpenSVC Failover Monitoring**: Split-brain detection and monitoring for OpenSVC clusters

#### API & Integration

* **Application Deployments**: Support for managing application deployments via peering

* **Compact JSON Responses**: Reduced API response sizes for improved performance

* **OAuth Authentication**: OAuth2 provider integration for API authentication (parameters: `api-oauth-client-id`, `api-oauth-client-secret`, `api-oauth-provider-url`, `api-oauth-redirect-url`)

#### Scheduler

* **Enhanced Scheduler System**: Comprehensive task scheduling with cron support for database maintenance, backups, and administrative operations

* **Rolling Restart/Reprovision**: Schedule automatic rolling restarts and reprovisioning operations

* **SLA Rotation**: Automated SLA metric rotation via scheduler

#### Database Compatibility

* **MySQL 8.4 Support**: Version parsing and SSL mode compatibility fixes for MySQL 8.4

* **PostgreSQL Enhancements**: Improved PostgreSQL logical and streaming replication support

#### Performance & Reliability

* **Job Script Versioning**: Automatic upgrades and version tracking for database job scripts

* **Database Jobs Schema Upgrades**: API endpoint for upgrading jobs schema

* **SQL Error Tailers**: Monitor and tail SQL error logs for analysis

* **Audit Log Tailers**: Capture and monitor audit log entries

#### Development & Build

* **SBOM Generation**: Software Bill of Materials generation for security compliance  
