---
title: Install from Repository
taxonomy:
    category: docs
---

## Install from Repository

Signal18 provides signed package repositories for Debian/Ubuntu and CentOS/RHEL. Package installations deploy binaries, configuration templates, and static assets to standard system paths.

> **Upgrading from 2.x?** Version 3.x introduced configuration file location changes and parameter renames. See the [Migration Guide](/installation/migration) before upgrading.

---

## CentOS / RHEL

### Configure the repository

```bash
cat > /etc/yum.repos.d/signal18.repo <<'EOF'
[signal18]
name=Signal18 repositories
baseurl=http://repo.signal18.io/centos/${releasever}/${basearch}/3.1/
gpgcheck=0
enabled=1
EOF
```

### Install

```bash
yum install replication-manager-osc
```

Replace `replication-manager-osc` with your chosen flavor (`replication-manager-pro`, `replication-manager-arb`).

### Upgrading from 2.x (epoch conflict)

If you encounter this error when upgrading from 2.x:

```
file /usr/bin/replication-manager-cli from install of replication-manager-client-3.1.xx-1.x86_64
conflicts with file from package replication-manager-pro-1730721861:2.3.53-1.x86_64
```

This is caused by an epoch removed in 3.x packages. Resolve it as follows:

1. Stop the replication-manager service
2. Back up `/var/lib/replication-manager/` and `/etc/replication-manager/`
3. Remove all existing replication-manager packages:
   ```bash
   yum remove replication-manager-pro replication-manager-osc replication-manager-client
   ```
4. Clear cached repository metadata:
   ```bash
   dnf clean metadata --disablerepo="*" --enablerepo=signal18
   ```
5. Install the 3.x package
6. Review and restore your configuration

---

## Debian / Ubuntu

### Configure the repository and install

```bash
# Set version
version="3.1"

# Import GPG signing key
gpg --recv-keys --keyserver keyserver.ubuntu.com FAE20E50
gpg --export FAE20E50 > /etc/apt/trusted.gpg.d/signal18.gpg

# Add repository
echo "deb [signed-by=/etc/apt/trusted.gpg.d/signal18.gpg] http://repo.signal18.io/deb $(lsb_release -sc) $version" \
  > /etc/apt/sources.list.d/signal18.list

# Install
apt-get update
apt-get install replication-manager-osc
```

Replace `replication-manager-osc` with your chosen flavor (`replication-manager-pro`, `replication-manager-arb`).

---

## Post-Installation

After package installation:

- Configuration template: `/etc/replication-manager/config.toml`
- Cluster configuration directory: `/etc/replication-manager/cluster.d/`
- Static assets: `/usr/share/replication-manager/`
- Data directory: `/var/lib/replication-manager/`
- Log file: `/var/log/replication-manager.log`

Enable and start the service:

```bash
systemctl enable --now replication-manager
```

Proceed to [Configuration](/installation/configuration) to set up your first cluster.
