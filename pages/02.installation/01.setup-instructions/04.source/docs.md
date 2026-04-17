---
title: Install from Source
taxonomy:
    category: docs
---

## Install from Source

Building from source requires [Go 1.24 or later](https://golang.org/dl/) and Node.js 22 (for the React dashboard).

---

## Clone and Build

```bash
git clone https://github.com/signal18/replication-manager.git \
  ~/go/src/github.com/signal18/replication-manager

cd ~/go/src/github.com/signal18/replication-manager
```

Build all binary variants:

```bash
make bin
```

Build the React dashboard (required for `osc`, `pro`, and embedded binaries):

```bash
make react
```

---

## Build Specific Flavors

```bash
make osc        # Open Source Community — no provisioning
make pro        # Pro — with OpenSVC provisioning
make tst        # Test — with Sysbench and regression testing
make arb        # Arbitrator
make emb        # Embedded binary (dashboard built in)
make cli        # CLI client only
```

---

## Build Packages

```bash
make package
```

This runs `package_linux.sh` and produces RPM and DEB packages in `build/`.

---

## Build Tags

The build system uses Go compile-time feature flags (`-X` linker flags) to control which features are compiled into each binary. Key flags:

| Flag | Effect |
|---|---|
| `WithProvisioning` | Enable OpenSVC/Kubernetes cluster provisioning |
| `WithArbitration` | Enable arbitration client |
| `WithProxysql` | Enable ProxySQL integration |
| `WithHaproxy` | Enable HAProxy integration |
| `WithMaxscale` | Enable MaxScale integration |
| `WithOpenSVC` | Enable OpenSVC collector API |
| `WithEmbed` | Embed dashboard and assets in binary |

See the `Makefile` for the full flag sets used per flavor.

---

## Running from Source

After building, binaries are written to `build/binaries/`. The embedded binary can be run directly:

```bash
build/binaries/replication-manager monitor \
  --config ./etc/local/config.toml \
  --http-server
```

---

## Continuous Integration

Development builds from the `develop` branch are available on the [Signal18 CI Server](http://ci.signal18.io/mrm/builds/tags/).
