---
title: Command Line Flags
taxonomy:
    category: docs
---

By default **replication-manager** read a the config file  /etc/replication-manager/config.toml  
It load the default section in the file and all the cluster sections.

The default init.d or systemd scripts can be changed to pick specific configuration file or to load only some of the clusters define in the config.     

The following command line flags are also used to manually run the monitoring daemon in shell and get the output printed to terminal.  

> Command line flags are overwrite by variables found in the config.toml file.

## Command Line Flags

##### `config`

| Item | Value |
| ---- | ----- |
| Description | Full path to configuration file. |
| Type | string |
| Default | "/etc/replication-manager/config.toml" |

##### `cluster` (2.0), `config-group` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Only monitor the given cluster list. cluster are define with sections like [cluster1], every parameters from the [default] section apply to all cluster, and are overwrite by the cluster section, any command line parameters will be ignored if they are define in a section.  |
| Type | list |
| Example | "cluster1,cluster2" |
