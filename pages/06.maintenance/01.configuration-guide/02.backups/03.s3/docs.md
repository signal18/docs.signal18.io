---
title: S3
taxonomy:
    category: docs
---
### Streaming Backups

**replication-manager 2.1** can fuse mount the entire backup directory to an S3 bucket  


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
