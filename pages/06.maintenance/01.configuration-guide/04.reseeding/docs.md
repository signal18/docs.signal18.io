---
title: Reseeding
taxonomy:
    category: docs
---

**replication-manager (2.1)** can stream backups out and in to a new or de-sync database node.

Streaming is organized around a jobs queue table on each database node that is dequeue with a cron script.
**replication-manager** is auto creating this job table on cluster nodes.

For donor node to stream  jobs **replication-manager (2.1)** open a TCP connection with a timeout of 120s and  populate the jobs (replication_manager_scehma.jobs) table with the action and the receiver port.

For Joiner replication manager use port 4444.

To organize streaming  you need to implement backup policy via replication-manager scheduler or do an on demand backup via the GUI or API.

Scheduler default collect daily physical backup. Extra package xtrabackup or mariadbbackup need to be install on the remote host.

Reseeding endpoint API get 3 methods:

| Method | Descrition |
| ---- | ------- |
| logicalmaster | Direct mysqldump of master via replication-manager piping host |
| logicalbackup | Last mysqldump backup |
| physicalbackup | Last xtrabackup or mariadb-backup backup |


## Donor and joiner cron script

In **replication-manager-pro** we push the donor script in services compliance but if you are using   **replication-manager-osc** you can customize similar script in crontab schedule every minutes.

[Job script](https://raw.githubusercontent.com/signal18/replication-manager/refs/heads/develop/share/dashboard/static/configurator/init/dbjobs_new)
[Bootstrap script is os dependent](https://github.com/signal18/replication-manager/blob/develop/share/dashboard/static/configurator/onpremise/repository/debian/mariadb/bootstrap)
