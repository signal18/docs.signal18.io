---
title: Install from Embedded Binary
taxonomy:
    category: docs
---

## Install from Embedded Binary

The embedded binary (`replication-manager`) includes the full React dashboard and all static assets compiled into a single executable. No package manager, no asset directory, no system paths required.

---

## One-Command Install

Install the latest release with a single command:

```bash
curl -fsSL https://signal18.io/get-repman | bash
```

The script auto-detects your operating system and architecture, downloads the binary from GitHub Releases, and places it in `/usr/local/bin` (or `~/.local/bin` if sudo is not available).

**System-wide install (with sudo):**

```bash
curl -fsSL https://signal18.io/get-repman | sudo bash
```

**Install specific version:**

```bash
curl -fsSL https://signal18.io/get-repman | REPMAN_VERSION=v3.1.16 bash
```

**Install server + CLI client together:**

```bash
curl -fsSL https://signal18.io/get-repman | REPMAN_INSTALL_CLI=true bash
```

**Custom install directory:**

```bash
curl -fsSL https://signal18.io/get-repman | REPMAN_INSTALL_DIR=/opt/repman bash
```

### Environment Variables

| Variable | Default | Description |
|---|---|---|
| `REPMAN_VERSION` | latest | Specific release version to install (e.g. `v3.1.16`) |
| `REPMAN_INSTALL_DIR` | `/usr/local/bin` or `~/.local/bin` | Installation directory |
| `REPMAN_INSTALL_CLI` | `false` | Also install `replication-manager-cli` |
| `REPMAN_SKIP_VERIFY` | `false` | Skip post-installation verification |

### Supported Platforms

| OS | Architectures |
|---|---|
| Linux | amd64, arm64 |
| macOS (Darwin) | amd64 (Intel), arm64 (Apple Silicon) |

---

## Manual Download from GitHub Releases

All release artifacts are available at [github.com/signal18/replication-manager/releases](https://github.com/signal18/replication-manager/releases).

### Embedded binary (recommended for portable use)

```
replication-manager-{version}.{arch}
```

Example — download and install manually:

```bash
VERSION=3.1.24
ARCH=x86_64

curl -LO https://github.com/signal18/replication-manager/releases/download/v${VERSION}/replication-manager-${VERSION}.${ARCH}
chmod +x replication-manager-${VERSION}.${ARCH}
sudo mv replication-manager-${VERSION}.${ARCH} /usr/local/bin/replication-manager
```

### Tarball (basedir variant)

Tarballs include the binary plus the `share/` asset directory. Use these when you want a versioned directory layout under `/usr/local/`:

```
replication-manager-{flavor}-{version}.tar.gz
```

```bash
sudo tar zxvf replication-manager-osc-3.1.24.tar.gz -C /usr/local/
sudo ln -s /usr/local/replication-manager-osc-3.1.24 /usr/local/replication-manager

# Copy systemd unit file
sudo cp /usr/local/replication-manager/share/replication-manager.service /etc/systemd/system/
sudo systemctl daemon-reload
sudo systemctl enable --now replication-manager
```

### Release Artifact Naming

| Artifact | Description |
|---|---|
| `replication-manager-{version}.{arch}` | Embedded binary (dashboard + assets built in) |
| `replication-manager-{flavor}-{version}.tar.gz` | Tarball (basedir variant) |
| `replication-manager-{flavor}-{version}.{arch}.rpm` | RPM package |
| `replication-manager-{flavor}_{version}_{arch}.deb` | DEB package |
| `replication-manager-cli-{version}` | Standalone CLI client |
| `sbom-{version}.json` | Software Bill of Materials (CycloneDX) |

Replace `{flavor}` with: `osc`, `tst`, `pro`, or `arb`

---

## Development Builds

Nightly builds from the `develop` branch are available on the [Continuous Integration Server](http://ci.signal18.io/mrm/builds/tags/).
