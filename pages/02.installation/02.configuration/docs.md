---
title: Configuration
taxonomy:
    category: docs
---

**replication-manager** bundles some example configuration files located in etc places based on type of package installation.  

Reuse a sample config or create a new `config.toml` file.

**replication-manager** binaries  search for a default configuration file named `config.toml` in /etc/replication-manager or /usr/local/replication-manager/etc, any other file name can be specified with `--config` command line flag.

A config file is split in multiple sections [Default], [cluster1], [cluster2] ... each section represent a cluster.

[Default] is a special section: it is required, all variables in default section are applied and enforce to all clusters and it is the place to put replication-manager only variables like "monitoring-datadir, include ..."  most variables are cluster based, so please define most variables under a cluster section unless you would like to force an infrastructure value.

Note that it's possible to have a single [Default] section that hold config for a unique one cluster deployment

Because a single config file is not convenient when managing many clusters, an include variable from the  [Default] section : include = "/etc/replication-manager/cluster.d" can point an extra config directory . All *.toml files in include directory will be merge with your main config file

From **replication-manager 2.1 ** User may wan't to change settings via API or GUIs and get such changes saved. To enable this the [Default] section should include the variable

monitoring-save-config=true

Once enabled, setting changes will be persisted in the replication-manager working directory /var/lib/replication-manager/cluster_name/config.toml

## Minimal configuration

**Version 3.x** uses a two-part configuration structure with a `[Default]` section for global settings and separate cluster sections.

### Minimal configuration for single cluster

```
[Default]
monitoring-save-config = true
include = "/etc/replication-manager/cluster.d"

[cluster1]
title = "cluster1"
prov-orchestrator = "onpremise"
db-servers-hosts = "127.0.0.1:3306,127.0.0.1:3307"
db-servers-prefered-master = "127.0.0.1:3306"
db-servers-credential = "root:password"
replication-credential = "repl_user:repl_password"
```

### Alternative: Single cluster in Default section (legacy style)

For simple single-cluster deployments, cluster parameters can be placed directly in `[Default]`:

```
[Default]
title = "ClusterTest"
db-servers-hosts = "127.0.0.1:3306,127.0.0.1:3307"
db-servers-credential = "root:password"
replication-credential = "repl_user:repl_password"
failover-mode = "manual"
```

### Multi-cluster configuration with includes

For managing multiple clusters, use the include directory:

**Main config: /etc/replication-manager/config.toml**
```
[Default]
monitoring-save-config = true
include = "/etc/replication-manager/cluster.d"
http-server = true
http-bind-address = "0.0.0.0"
```

**Cluster config: /etc/replication-manager/cluster.d/cluster1.toml**
```
[cluster1]
title = "Production Cluster"
prov-orchestrator = "onpremise"
db-servers-hosts = "10.0.1.10:3306,10.0.1.11:3306,10.0.1.12:3306"
db-servers-prefered-master = "10.0.1.10:3306"
db-servers-credential = "root:password"
replication-credential = "repl_user:repl_password"
```

**Cluster config: /etc/replication-manager/cluster.d/cluster2.toml**
```
[cluster2]
title = "Development Cluster"
prov-orchestrator = "onpremise"
db-servers-hosts = "10.0.2.10:3306,10.0.2.11:3306"
db-servers-credential = "root:password"
replication-credential = "repl_user:repl_password"
```

Copy a sample configuration file to config.toml auto loaded configuration:

## Sample configuration for package

`sudo cp /etc/replication-manager/etc/config.toml.sample.masterslave-haproxy  /etc/replication-manager/config.toml`

## Sample configuration for archive  

`sudo cp /usr/local/replication-manager/etc/config.toml.sample.masterslave-haproxy  /usr/local/replication-manager/etc/config.toml`
