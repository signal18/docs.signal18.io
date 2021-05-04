---
title: OnPremise
taxonomy:
    category: Orchestrator
---

**replication-manager (2.2)** can connect via SSH using it's own user SSH private key and connect to the db or proxy host on a given sudoer root user to bootstrap the configuration files and start stop each service

We plan to extend this to deploy tar.gz binary distribution of each software in a way it does not conflict with repository distribution this would enable to upgrade and downgrade versions out of regular OS packaging  


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
