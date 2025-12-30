---
title: 'Setup Instructions'
taxonomy:
    category:
        - docs
---

**replication-manager** is a self-contained binary without dependencies that works on most platforms. Builds are tested for Linux, FreeBSD, and macOS. Binaries are provided for multiple architectures (x86_64, ARM64).

Packages are provided for Debian/Ubuntu and CentOS/RHEL and derivatives.

The current stable version is **3.1**. Check [GitHub Releases](https://github.com/signal18/replication-manager/releases) for the latest release or review the [changelog](/change-logs/features31) for version history.

Development builds are available on the [Continuous Integration Server](http://ci.signal18.io/mrm/builds/tags/)

### Quick Installation

Install the embedded binary with a single command:

```bash
curl -fsSL https://signal18.io/get-repman | bash
```

**Features:**
- [x] Auto-detect operating system and architecture
- [x] Download latest release from GitHub
- [x] Auto-fallback to user directory if sudo not available
- [x] Optional CLI client installation
- [x] Post-installation verification
- [x] Comprehensive error handling

**Supported Platforms:**
- Linux: amd64, arm64
- macOS (Darwin): amd64 (Intel), arm64 (Apple Silicon)

**Environment Variables:**

| Variable | Description | Default |
| ---- | ----- | ------- |
| `REPMAN_VERSION` | Specific version to install | latest |
| `REPMAN_INSTALL_DIR` | Custom installation directory | `/usr/local/bin` or `~/.local/bin` |
| `REPMAN_INSTALL_CLI` | Install CLI client alongside server | `false` |
| `REPMAN_SKIP_VERIFY` | Skip post-installation verification | `false` |

**Examples:**

Install with sudo (system-wide installation):

```bash
curl -fsSL https://signal18.io/get-repman | sudo bash
```

Install specific version:

```bash
curl -fsSL https://signal18.io/get-repman | REPMAN_VERSION=v3.1.16 bash
```

Install server + CLI client:

```bash
curl -fsSL https://signal18.io/get-repman | REPMAN_INSTALL_CLI=true bash
```

Custom installation directory:

```bash
curl -fsSL https://signal18.io/get-repman | REPMAN_INSTALL_DIR=/opt/repman bash
```

### Package Naming Convention

**replication-manager** uses a client-server architecture with multiple binary flavors based on included features.

The server monitoring daemon is available in different flavors: **replication-manager-osc**, **replication-manager-tst**, **replication-manager-pro**, **replication-manager-arb**, and **replication-manager** (embedded).

The command-line client binary **replication-manager-cli** sends requests to the monitoring daemon via a secured protocol.

**replication-manager-cli** is bundled with server packages but can be installed independently.

| Package | Flavor | Description |
| ---- | ------ | ----------- |
| replication-manager-osc | Open Source | All features except provisioning (recommended) |
| replication-manager-tst | Test | OSC features plus testing tools (local bootstrap, benchmarking) |
| replication-manager-pro | Provisioning | Commercial cluster provisioning solution |
| replication-manager-arb | Arbitrator | Arbitration for replication-manager clustering |
| replication-manager | Embedded | Standalone binary with embedded web dashboard and all assets |

### Binary Distribution Types

**replication-manager** provides three binary distribution types:

**Regular Binaries** (package installations):
- Designed for RPM/DEB package installations
- Uses system directories (`/etc/`, `/usr/share/`, `/var/lib/`)
- Requires separate installation of shared assets

**Basedir Variants** (tarball distributions):
- Built with embedded static assets
- Self-contained directory structure
- Suffix: `-basedir` (e.g., `replication-manager-osc-basedir`)
- Used for tarball installations at `/usr/local/replication-manager/`

**Embedded Binary** (`replication-manager` without suffix):
- Full standalone binary with embedded web dashboard
- Includes all assets: dashboard, configuration templates, scripts
- Self-contained for portable deployments
- Available on GitHub Releases

### Downloading from GitHub Releases

All binary variants are available on the [GitHub Releases](https://github.com/signal18/replication-manager/releases) page.

Available artifacts:
- `replication-manager-{flavor}-{version}.tar.gz` - Tarball distributions (basedir variants)
- `replication-manager-{flavor}-{version}.{arch}.rpm` - RPM packages
- `replication-manager-{flavor}_{version}_{arch}.deb` - Debian packages
- `replication-manager-{version}.{arch}` - Embedded binary with dashboard
- `replication-manager-cli-{version}` - Standalone CLI binary
- `sbom-{version}.json` - Software Bill of Materials (CycloneDX format)

Replace `{flavor}` with: `osc`, `tst`, `pro`, or `arb`

Replace `{version}` with the release version

Replace `{arch}` with your architecture (e.g., `x86_64`, `amd64`, `arm64`)

### Installation from our repository

>__Upgrading from 2.x?__: Version 3.x introduced configuration file location changes and parameter renames. See the [Migration Guide](/installation/migration) for deprecated parameters and upgrade procedures.

#### CentOS/RHEL

Configure the repository:

```bash
cat>/etc/yum.repos.d/signal18.repo <<'EOL'
[signal18]
name=Signal18 repositories
baseurl=http://repo.signal18.io/centos/${releasever}/${basearch}/3.1/
gpgcheck=0
enabled=1
EOL
```

Install the package:

```bash
yum install replication-manager-osc
```

**Upgrading from 2.x with epoch conflict:**

If upgrading from 2.x and encountering this error:
```
file /usr/bin/replication-manager-cli from install of replication-manager-client-3.1.xx-1.x86_64 conflicts with file from package replication-manager-pro-1730721861:2.3.53-1.x86_64
```

This occurs due to epoch removal in 3.x packages. Resolve by:

1. Stop the replication-manager service
2. Backup configuration files from `/var/lib/replication-manager/` and `/etc/replication-manager/`
3. Uninstall all previous replication-manager packages (pro, osc, client)
4. Clean cached metadata:
   ```bash
   sudo dnf clean metadata --disablerepo="*" --enablerepo=signal18
   ```
5. Update repository configuration to 3.1 (recommended)
6. Install the new version
7. Review configuration files and restore previous settings if replaced 

#### Debian/Ubuntu

Configure the repository, install the GPG key, and install the package:

```bash
# Set version (3.1 is current stable)
version="3.1"

# Import GPG key
gpg --recv-keys --keyserver keyserver.ubuntu.com FAE20E50
gpg --export FAE20E50 > /etc/apt/trusted.gpg.d/signal18.gpg

# Add repository
echo "deb [signed-by=/etc/apt/trusted.gpg.d/signal18.gpg] http://repo.signal18.io/deb $(lsb_release -sc) $version" > /etc/apt/sources.list.d/signal18.list

# Install package
apt-get update
apt-get install replication-manager-osc
```

### Installation from tarball

Tarball distributions use basedir variants with embedded static assets. Download the archive from [GitHub Releases](https://github.com/signal18/replication-manager/releases).

Unpack the tarball:

```bash
sudo tar zxvf replication-manager-osc-{version}.tar.gz -C /usr/local/
```

Create a symlink:

```bash
sudo ln -s /usr/local/replication-manager-osc-{version} /usr/local/replication-manager
```

Copy the systemd or init files:

```bash
sudo cp /usr/local/replication-manager/share/replication-manager.init /etc/init.d/replication-manager
```

### Building from source

Building from source requires [Go 1.24.6 or later](https://golang.org/dl/).

Clone the repository and build:

```bash
git clone https://github.com/signal18/replication-manager.git ~/go/src/github.com/signal18/replication-manager
cd ~/go/src/github.com/signal18/replication-manager
make bin
```

This builds all binary variants.

Build specific flavors:

```bash
make osc        # Open source core
make pro        # Production version
make tst        # Test version
make arb        # Arbitrator
make cli        # CLI client only
```

Build packages:

```bash
make package
```

[Proceed with the configuration step](/installation/configuration)


### Docker Images

[Docker Hub](https://hub.docker.com/r/signal18/replication-manager/)

[Docker Image documentation](/installation/setup-instructions/docker)
