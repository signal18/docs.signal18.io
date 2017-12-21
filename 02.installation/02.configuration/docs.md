---
title: Configuration
taxonomy:
    category: docs
---

**replication-manager** bundles some example configuration files located in in different places based on the previously selected package installation.  

Use or create a `config.toml` file in the location explained in following section.

**replication-manager** binaries are looking for `config.toml` in ./ ./etc/replication-manager or /usr/local/replication-manager when nothing is specified in `--config` command line flag.

## Minimal configuration

This is a minimal configuration sample required to run `replication-manager`:

```
[Default]
title = "ClusterTest"
db-servers-hosts = "127.0.0.1:5055,127.0.0.1:5056"
db-servers-credential = "skysql:skyvodka"
replication-credential = "skysql:skyvodka"
failover-mode = "manual"
```

Copy a sample configuration file to config.toml auto loaded configuration:

## Sample configuration for package

`sudo cp /etc/replication-manager/etc/config.toml.sample.masterslave-haproxy  /etc/replication-manager/etc/config.toml`

## Sample configuration for archive  

`sudo cp /usr/local/replication-manager/etc/config.toml.sample.masterslave-haproxy  /usr/local/replication-manager/etc/config.toml`

It is possible to define multiple clusters via adding sections in the  config.toml. A cluster section can also be defined in a seperate file and loaded via the include directive pointing to an extra directory from the main config file.   
