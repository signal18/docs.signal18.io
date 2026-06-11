---
title: OnPremise
taxonomy:
    category: docs
---

**replication-manager (2.2)** can connect via SSH using it's own user SSH private key and connect to the db or proxy host on a given sudoer root user to bootstrap the configuration files and start stop each service

We plan to extend this to deploy tar.gz binary distribution of each software in a way it does not conflict with repository distribution this would enable to upgrade and downgrade versions out of regular OS packaging  

### When are configurations pushed?

| Action | Database | HAProxy | ProxySQL |
|--------|----------|---------|----------|
| **Provision** | Config downloaded via bootstrap script | Config copied via SCP (`/etc/haproxy/haproxy.cfg`) | Config pushed via bootstrap |
| **Start** | Start script executed via SSH (no config push) | `systemctl start haproxy` via SSH (no config push) | Start via SSH (no config push) |
| **Stop** | Stop script executed via SSH | `systemctl stop haproxy` via SSH | Stop via SSH |
| **Configurator** (`prov-db-config=true`) | Config files pushed continuously via dbjobs/SSH during monitoring | Not applicable — HAProxy managed via runtime API only | Not applicable |

> **Important**: For databases with `prov-db-config = true`, the configurator actively pushes configuration files (my.cnf snippets) to database servers during every monitoring cycle via the dbjobs mechanism. This requires `monitoring-scheduler = true`, `scheduler-jobs-ssh = true`, and `onpremise-ssh = true`. Use `prov-db-config-preserve = true` and `prov-db-config-preserve-vars` to protect dynamic variable changes from being overwritten.
>
> For HAProxy and ProxySQL, configuration is only pushed during initial provisioning. Ongoing management uses the runtime API (`haproxy-mode = runtimeapi`).


##### `onpremise-ssh` (2.2)

| Item | Value |
| ---- | ----- |
| Description | Connect to host via SSH using user private key |
| Type | Boolean |
| Default | false  |
| Example | true |


##### `OnPremiseSSHPort` (2.2)

| Item | Value |
| ---- | ----- |
| Description | Connect to host via SSH using ssh port |
| Type | Integer |
| Default | 22  |
| Example |2222 |

##### `onpremise-ssh-credential` (2.2)

| Item | Value |
| ---- | ----- |
| Description | Connect to host via SSH using user private key |
| Type | String |
| Default | "root"  |
| Example | "admin" |
