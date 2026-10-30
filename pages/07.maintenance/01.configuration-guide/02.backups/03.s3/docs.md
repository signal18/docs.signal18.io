---
title: S3
taxonomy:
    category: docs
---
### 7.2.2.3.0.1 Streaming Backups

**replication-manager 2.1** can fuse mount the entire backup directory to an S3 bucket  

#### How the mount runs (3.1.47)

Available from replication-manager 3.1.47. The cluster's backups folder (`<working-dir>/backups/<cluster>`) is mounted on the bucket by a separate process that replication-manager starts from its own binary and supervises. In `ps` it shows as `replication-manager backup-folder-mount`, a child of the monitor; the kernel may name it `exe`. Nothing has to be installed or started by hand: turning `backup-streaming` on or off is the only switch.

Why a separate process: a crash of the S3 file system can never stop the monitor. If the mount process dies, what was waiting on the mount gets an error, the monitor keeps monitoring, and the mount is made again on its own.

| Event | What you see | What replication-manager does |
| ----- | ------------ | ----------------------------- |
| The mount process ends (crash, kill) | **WARN0261** "Backups folder mount of &lt;cluster&gt; not mounted", with the exit status and the process's last output line | Detaches the dead mount, uses the local folder meanwhile, and mounts again after 1, 2, 4, 8 then 10 minutes for failures in a row (at once after a change of the mount settings) |
| S3 slow or unreachable | **WARN0262** "Backups folder mount of &lt;cluster&gt; slow", with the number of requests answered with an I/O error after their 2-minute deadline | Keeps the mount; a backup writing into it ends in error instead of hanging the monitor |
| Backups written while the mount was down | The local files of the backups folder | Uploads them into the bucket before mounting again, the local copy kept aside as `<cluster>.local-<time>` |
| replication-manager stops | Nothing to do | Flushes and unmounts; the mount process ends with replication-manager, also when replication-manager is killed |

The S3 keys reach the mount process through a private channel, never its command line, its environment or a log. Keys that expire (the Cloud18 storage) are renewed while mounted.

Requirements: FUSE in the replication-manager host or container (`/dev/fuse`; in a container also the `SYS_ADMIN` capability and an AppArmor profile that allows mounts, such as `apparmor=unconfined`).


##### `backup-streaming` (2.1)  

| Item | Value |
| ---- | ----- |
| Description | Backup streaming to cloud |
| Type | Boolean |
| Default Value | false |


##### `backup-streaming-aws-access-key-id` (2.1)  

| Item | Value |
| ---- | ----- |
| Description |  Backup AWS key identity  |
| Type | string |
| Default Value | "admin" |

##### `backup-streaming-aws-access-secret` (2.1)  

| Item | Value |
| ---- | ----- |
| Description |  Backup AWS key secret |
| Type | string |
| Default Value | "secret" |

##### `backup-streaming-bucket` (2.1)  

| Item | Value |
| ---- | ----- |
| Description | Backup S3 bucket  |
| Type | string |
| Default Value | "repman" |


##### `backup-streaming-endpoint` (2.1)  

| Item | Value |
| ---- | ----- |
| Description | Backup AWS endpoint |
| Type | string |
| Default Value | "https://s3.signal18.io/" |


##### `backup-streaming-region` (2.1)  

| Item | Value |
| ---- | ----- |
| Description | Backup AWS region |
| Type | string |
| Default Value | "fr-1" |

##### `backup-streaming-debug` (2.1)  

| Item | Value |
| ---- | ----- |
| Description | Debug mode for streaming to cloud |
| Type | Boolean |
| Default Value | false |
