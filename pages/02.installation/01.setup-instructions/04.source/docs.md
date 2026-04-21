---
title: Install from Source
taxonomy:
    category: docs
---

## 2.1.9.1 Install from Source

Building from source requires [Go 1.24 or later](https://golang.org/dl/) and Node.js 22 (for the React dashboard).

---

## 2.1.9.2 Clone and Build

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

## 2.1.9.3 Build Specific Flavors

```bash
make osc        # Open Source Community — no provisioning
make pro        # Pro — with OpenSVC provisioning
make tst        # Test — with Sysbench and regression testing
make arb        # Arbitrator
make emb        # Embedded binary (dashboard built in)
make cli        # CLI client only
```

---

## 2.1.9.4 Build Packages

```bash
make package
```

This runs `package_linux.sh` and produces RPM and DEB packages in `build/`.

---

## 2.1.9.5 Build Tags

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

## 2.1.9.6 Running from Source

After building, binaries are written to `build/binaries/`. The embedded binary can be run directly:

```bash
build/binaries/replication-manager monitor \
  --config ./etc/local/config.toml \
  --http-server
```

---

## 2.1.9.7 Continuous Integration

Development builds from the `develop` branch are available on the [Signal18 CI Server](http://ci.signal18.io/mrm/builds/tags/).
