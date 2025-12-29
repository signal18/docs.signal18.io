---
title: "Migrating from 2.x to 3.x"
taxonomy:
    category: docs
---

This guide covers migrating from **replication-manager** 2.x to 3.x. Version 3.0 introduced significant changes to configuration management, file locations, and default behaviors. Version 3.1 builds on these changes with additional features.

## Pre-Migration Checklist

Before upgrading:

- [ ] Backup your configuration files from `/etc/replication-manager/`
- [ ] Document current cluster states and topologies
- [ ] Review custom scripts that interact with **replication-manager**
- [ ] Check API client compatibility if using REST API
- [ ] Verify database versions are supported (MariaDB 10.x+, MySQL 5.7+, Percona 5.7+)
- [ ] Plan maintenance window for upgrade and testing

## Major Changes in 3.x

### Configuration File Location Changes

**Version 3.0** introduced a new configuration storage model:

**Old (2.x):**
```
/etc/replication-manager/config.toml          # Global config
/etc/replication-manager/cluster.d/*.toml     # Cluster configs
```

**New (3.x):**
```
/etc/replication-manager/config.toml          # Global config (read-only reference)
/root/.config/replication-manager/            # Active configuration directory
/root/.config/replication-manager/config.toml # Merged global config
/root/.config/replication-manager/clusters.d/ # Active cluster configs
```

**Behavior:**
- On first start, 3.x copies `/etc/replication-manager/` to `~/.config/replication-manager/`
- Dynamic configuration changes via API/UI are saved to `~/.config/replication-manager/`
- The `/etc/` directory becomes a template for initial configuration
- Git integration stores encrypted secrets in `~/.config/` directory

**Migration action:**
- Ensure `~/.config/replication-manager/` directory exists and is writable
- Review file ownership (runs as `replication-manager` user by default)
- Update backup scripts to include `~/.config/replication-manager/`

### Docker Volume Mapping Changes

**Old (2.x):**
```bash
docker run -v/home/repman/etc:/etc/replication-manager:rw \
           -v/home/repman/data:/var/lib/replication-manager:rw
```

**New (3.x):**
```bash
docker run -v/home/repman/etc:/etc/replication-manager:rw \
           -v/home/repman/data:/var/lib/replication-manager:rw \
           -v/home/repman/config:/root/.config/replication-manager:rw
```

Add the third volume mount for dynamic configuration persistence.

## Deprecated Parameters

**replication-manager** 3.x includes automatic detection of deprecated configuration parameters and will log warnings (WARN0159, WARN0160) when found.

### Parameter Renames

The following parameters were renamed. **replication-manager** supports both old and new names, but you should migrate to the new names:

| Old Parameter (2.x) | New Parameter (3.x) | Notes |
| ------------------- | ------------------- | ----- |
| `monitoring-config-rewrite` | `monitoring-save-config` | Control dynamic config saves |
| `api-user` | `api-credentials` | Changed to credential format |
| `replication-master-connection` | `replication-source-name` | Multi-source replication name |
| `master-connection` | `replication-source-name` | Alias for above |
| `logfile` | `log-file` | Log file path |
| `wait-kill` | `switchover-wait-kill` | Switchover kill timeout |
| `hosts` | `db-servers-hosts` | Database server list |
| `hosts-tls-ca-cert` | `db-servers-tls-ca-cert` | TLS CA certificate |
| `hosts-tls-client-key` | `db-servers-tls-client-key` | TLS client key |
| `hosts-tls-client-cert` | `db-servers-tls-client-cert` | TLS client certificate |
| `connect-timeout` | `db-servers-connect-timeout` | Connection timeout |
| `rpluser` | `replication-credential` | Replication user credentials |
| `prefmaster` | `db-servers-prefered-master` | Preferred master server |
| `ignore-servers` | `db-servers-ignored-hosts` | Ignored servers list |
| `master-connect-retry` | `replication-master-connection-retry` | Connection retry count |
| `readonly` | `failover-readonly-state` | Read-only enforcement |
| `mdbshardproxy-hosts` | `shardproxy-servers` | Shard proxy servers |
| `shardproxy-hosts` | `shardproxy-servers` | Shard proxy servers |
| `multimaster` | `replication-multi-master` | Multi-master topology |
| `multi-tier-slave` | `replication-multi-tier-slave` | Multi-tier topology |
| `pre-failover-script` | `failover-pre-script` | Pre-failover hook |
| `post-failover-script` | `failover-post-script` | Post-failover hook |
| `rejoin-script` | `autorejoin-script` | Rejoin hook script |
| `share-directory` | `monitoring-sharedir` | Shared files directory |
| `working-directory` | `monitoring-datadir` | Working data directory |
| `interactive` | `failover-mode` | Failover mode setting |

### Migration Script for Deprecated Parameters

Use this bash script to update your configuration files:

```bash
#!/bin/bash
# migrate-config-3x.sh

CONFIG_FILE="$1"

if [ -z "$CONFIG_FILE" ]; then
    echo "Usage: $0 <config-file>"
    exit 1
fi

# Create backup
cp "$CONFIG_FILE" "$CONFIG_FILE.backup-$(date +%Y%m%d)"

# Apply renames
sed -i 's/^monitoring-config-rewrite/monitoring-save-config/g' "$CONFIG_FILE"
sed -i 's/^api-user/api-credentials/g' "$CONFIG_FILE"
sed -i 's/^replication-master-connection/replication-source-name/g' "$CONFIG_FILE"
sed -i 's/^master-connection/replication-source-name/g' "$CONFIG_FILE"
sed -i 's/^logfile/log-file/g' "$CONFIG_FILE"
sed -i 's/^wait-kill/switchover-wait-kill/g' "$CONFIG_FILE"
sed -i 's/^hosts\s/db-servers-hosts /g' "$CONFIG_FILE"
sed -i 's/^hosts-tls-ca-cert/db-servers-tls-ca-cert/g' "$CONFIG_FILE"
sed -i 's/^hosts-tls-client-key/db-servers-tls-client-key/g' "$CONFIG_FILE"
sed -i 's/^hosts-tls-client-cert/db-servers-tls-client-cert/g' "$CONFIG_FILE"
sed -i 's/^connect-timeout/db-servers-connect-timeout/g' "$CONFIG_FILE"
sed -i 's/^rpluser/replication-credential/g' "$CONFIG_FILE"
sed -i 's/^prefmaster/db-servers-prefered-master/g' "$CONFIG_FILE"
sed -i 's/^ignore-servers/db-servers-ignored-hosts/g' "$CONFIG_FILE"
sed -i 's/^master-connect-retry/replication-master-connection-retry/g' "$CONFIG_FILE"
sed -i 's/^readonly\s/failover-readonly-state /g' "$CONFIG_FILE"
sed -i 's/^mdbshardproxy-hosts/shardproxy-servers/g' "$CONFIG_FILE"
sed -i 's/^shardproxy-hosts/shardproxy-servers/g' "$CONFIG_FILE"
sed -i 's/^multimaster/replication-multi-master/g' "$CONFIG_FILE"
sed -i 's/^multi-tier-slave/replication-multi-tier-slave/g' "$CONFIG_FILE"
sed -i 's/^pre-failover-script/failover-pre-script/g' "$CONFIG_FILE"
sed -i 's/^post-failover-script/failover-post-script/g' "$CONFIG_FILE"
sed -i 's/^rejoin-script/autorejoin-script/g' "$CONFIG_FILE"
sed -i 's/^share-directory/monitoring-sharedir/g' "$CONFIG_FILE"
sed -i 's/^working-directory/monitoring-datadir/g' "$CONFIG_FILE"
sed -i 's/^interactive/failover-mode/g' "$CONFIG_FILE"

echo "Configuration migrated. Backup saved to $CONFIG_FILE.backup-$(date +%Y%m%d)"
echo "Review changes and test before deploying to production"
```

## Default Value Changes

Several parameters have new default values in 3.x:

| Parameter | 2.x Default | 3.x Default | Impact |
| --------- | ----------- | ----------- | ------ |
| `monitoring-performance-schema-mutex` | false | true | Mutex monitoring enabled by default |
| `monitoring-performance-schema-latch` | false | true | Latch monitoring enabled by default |
| `monitoring-performance-schema-memory` | false | true | PFS memory monitoring enabled |
| `backup-restic-purge-oldest-on-disk-space` | N/A | true | Automatic backup purge enabled |
| `backup-disk-threshold-warn` | N/A | 85 | Warning at 85% disk usage |
| `backup-disk-threshold-crit` | N/A | 95 | Critical at 95% disk usage |

**Migration action:**
- Review monitoring overhead if performance schema monitoring causes issues
- Verify disk space management aligns with your backup strategy

## New Features Requiring Configuration

### 1. Active-Passive Topology (3.1)

If you want monitoring without automatic failover:

```toml
replication-active-passive = true
```

See [Active-Passive Topology documentation](/architecture/topologies/active-passive).

### 2. Enhanced Backup Management (3.1)

Configure disk space management for restic backups:

```toml
backup-restic-purge-oldest-on-disk-space = true
backup-restic-purge-oldest-on-disk-threshold = 90
backup-disk-threshold-warn = 85
backup-disk-threshold-crit = 95
backup-restic-timeout = 3600
```

### 3. Transaction Monitoring (3.1)

Enable sleeping transaction detection:

```toml
monitoring-processlist = true
monitoring-processlist-transactions = true
monitoring-processlist-information-schema = true
```

### 4. Per-Module Log Levels (3.1)

Fine-grained logging control:

```toml
log-level = 2                    # Global default
log-level-backup-stream = 3      # More verbose for backups
log-level-proxy = 1              # Less verbose for proxies
log-level-heartbeat = 4          # Debug heartbeat issues
```

## Breaking Changes

### API Changes

**Endpoint modifications in 3.x:**

- OAuth authentication support added (optional)
- Compact JSON response format available
- Some endpoint response structures changed for consistency

**Migration action:**
- Test API clients against 3.x before upgrading production
- Update API client libraries if available
- Review custom scripts using REST API

### SSL/TLS Mode Changes

MySQL 8.4 compatibility required adjustments to SSL mode handling:

- Validate your `db-servers-tls-mode` setting
- Ensure SSL certificates are properly configured
- Test SSL connections after upgrade

### Backup Tool Changes

Default paths for backup tools changed:

**Old (2.x):**
```toml
backup-mydumper-path = "/usr/bin/mydumper"
backup-myloader-path = "/usr/bin/myloader"
```

**New (3.x):**
```toml
backup-mydumper-path = ""  # Auto-detect from PATH
backup-myloader-path = ""  # Auto-detect from PATH
```

**MariaDB tool names:**
- `maria-backup` renamed to `mariadb-backup`
- `maria-import` renamed to `mariadb-import`

**Migration action:**
- Update `backup-*-path` parameters if using non-standard locations
- Verify backup tools are in PATH or specify full paths

## Migration Procedure

### Step 1: Prepare Environment

```bash
# Stop replication-manager 2.x
systemctl stop replication-manager

# Backup configuration
tar czf /tmp/repman-2x-config-$(date +%Y%m%d).tar.gz /etc/replication-manager/
tar czf /tmp/repman-2x-data-$(date +%Y%m%d).tar.gz /var/lib/replication-manager/

# Backup database states
replication-manager-cli status --cluster=all > /tmp/repman-2x-status.txt
```

### Step 2: Update Configuration Files

```bash
# Run migration script on each config file
for file in /etc/replication-manager/cluster.d/*.toml; do
    ./migrate-config-3x.sh "$file"
done

# Review and update config.toml
./migrate-config-3x.sh /etc/replication-manager/config.toml

# Add new 3.x parameters as needed
```

### Step 3: Install replication-manager 3.x

**Repository installation:**

```bash
# Update repository to 3.1
# CentOS/RHEL
echo "[replication-manager]
name=Replication-Manager
baseurl=http://repo.signal18.io/centos/\$releasever/\$basearch/3.1/
gpgcheck=0
enabled=1" > /etc/yum.repos.d/replication-manager.repo

yum update replication-manager
```

**Docker:**

```bash
docker pull signal18/replication-manager:3.1
```

### Step 4: Verify Configuration

```bash
# Test configuration syntax
replication-manager-cli test --cluster=all

# Check for deprecated parameters
journalctl -u replication-manager | grep -i "WARN0159\|WARN0160"
```

### Step 5: Start and Validate

```bash
# Start replication-manager 3.x
systemctl start replication-manager

# Verify all clusters are discovered
replication-manager-cli status --cluster=all

# Check web UI
# https://your-server:10005

# Verify monitoring is active
replication-manager-cli api /api/clusters

# Test failover capability (non-production first!)
# replication-manager-cli switchover --cluster=test_cluster
```

### Step 6: Monitor for Issues

```bash
# Watch logs for warnings
tail -f /var/log/replication-manager.log

# Check for deprecated parameter warnings
grep "WARN0159\|WARN0160" /var/log/replication-manager.log

# Monitor system resources (new PFS monitoring may increase load)
top
iostat -x 1

# Verify backups still work
# Trigger manual backup via API or CLI
```

## Post-Migration Tasks

### Update Backup Scripts

If backing up configuration:

```bash
#!/bin/bash
# New backup locations for 3.x

# Backup active configs
tar czf /backup/repman-config-$(date +%Y%m%d).tar.gz \
    /root/.config/replication-manager/

# Backup data directory
tar czf /backup/repman-data-$(date +%Y%m%d).tar.gz \
    /var/lib/replication-manager/
```

### Update Monitoring

- Grafana dashboards may need updates for new metric names
- Alert rules may need adjustment for new warning codes
- Prometheus exporters should work without changes

### Documentation Updates

- Update internal documentation with new parameter names
- Document new 3.x features being used
- Update runbooks with new CLI commands and API endpoints

## Rollback Procedure

If issues occur, rollback to 2.x:

```bash
# Stop 3.x
systemctl stop replication-manager

# Remove 3.x
yum remove replication-manager  # or apt remove

# Restore 2.x configuration
rm -rf /etc/replication-manager/
tar xzf /tmp/repman-2x-config-*.tar.gz -C /

# Install 2.x
yum install replication-manager-2.x  # specify exact version

# Restore data if needed
rm -rf /var/lib/replication-manager/
tar xzf /tmp/repman-2x-data-*.tar.gz -C /

# Start 2.x
systemctl start replication-manager
```

## Troubleshooting

### Configuration Not Loading

**Symptom:** Clusters not discovered after upgrade

**Solution:**
```bash
# Check file permissions
ls -la /root/.config/replication-manager/
chown -R replication-manager:replication-manager /root/.config/replication-manager/

# Verify configuration syntax
replication-manager-cli test --cluster=all
```

### Deprecated Parameter Warnings

**Symptom:** WARN0159 or WARN0160 in logs

**Solution:**
- Use migration script to update parameter names
- Check `/root/.config/replication-manager/clusters.d/` for old parameter names
- Review and update manually if script missed any

### Performance Schema Overhead

**Symptom:** Increased database load after upgrade

**Solution:**
```toml
# Disable new monitoring features temporarily
monitoring-performance-schema-mutex = false
monitoring-performance-schema-latch = false
monitoring-performance-schema-memory = false
```

### Docker Container Not Persisting Config

**Symptom:** Configuration changes lost on restart

**Solution:**
```bash
# Ensure third volume is mounted
docker run -v/home/repman/etc:/etc/replication-manager:rw \
           -v/home/repman/data:/var/lib/replication-manager:rw \
           -v/home/repman/config:/root/.config/replication-manager:rw \
           signal18/replication-manager:3.1
```

### Backup Tools Not Found

**Symptom:** Backup jobs failing with "command not found"

**Solution:**
```toml
# Specify full paths
backup-mydumper-path = "/usr/local/bin/mydumper"
backup-myloader-path = "/usr/local/bin/myloader"
backup-restic-binary-path = "/usr/bin/restic"
```

## Getting Help

If you encounter issues during migration:

- Review logs: `/var/log/replication-manager.log`
- Check deprecated parameter warnings (WARN0159, WARN0160)
- Consult documentation: https://docs.signal18.io
- Report issues: https://github.com/signal18/replication-manager/issues
- Community support: Signal18 Slack/Discord channels

## Version-Specific Notes

### Migrating from 2.0 to 3.x

- Largest configuration changes
- Requires most careful planning
- Test thoroughly before production

### Migrating from 2.1/2.2 to 3.x

- Fewer breaking changes
- Most parameter renames still apply
- Configuration location change is primary concern

### Migrating from 2.3 to 3.x

- Minimal breaking changes
- Configuration location change
- Some default value changes
- Docker volume mapping addition

### Upgrading from 3.0 to 3.1

- No breaking changes
- New features added
- New parameters with safe defaults
- Backward compatible with 3.0 configurations
