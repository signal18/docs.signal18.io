---
title: Archiving
taxonomy:
    category: docs
---
### Archiving Backups

##### `backup-restic` (2.1)                                            

| Item | Value |
| ---- | ----- |
| Description | Turn on Backup store and restore with restic |
| Type | boolean |
| Default Value | false |

##### `backup-restic-repository` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  Restic AWS or Minio backend repository |
| Type | string |
| Default Value | "s3:https://s3.signal18.io/backups" |


##### `backup-restic-aws-access-key-id` (2.1)    

| Item | Value |
| ---- | ----- |
| Description | AWS or Minio key id |
| Type | string |
| Default Value | "admin" |

##### `backup-restic-aws-access-secret` (2.1)    

| Item | Value |
| ---- | ----- |
| Description | AWS or Minio key secret |
| Type | string |
| Default Value | "secret" |


##### `backup-restic-binary-path` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  Path to restic binary  |
| Type | string |
| Default Value | "/usr/bin/restic" |

##### `backup-restic-password` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  Restic backend password  |
| Type | string |
| Default Value | "secret" |

##### `backup-keep-hourly` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  Keep this number of hourly backup  |
| Type | integer |
| Default Value | 1 |

##### `backup-keep-daily` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  Keep this number of daily backup |
| Type | integer |
| Default Value | 1 |

##### `backup-keep-weekly` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  Keep this number of weekly backup |
| Type | integer |
| Default Value | 4 |

##### `backup-keep-monthly` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  Keep this number of monthly backup |
| Type | integer |
| Default Value | 12 |

##### `backup-keep-yearly` (2.1)    

| Item | Value |
| ---- | ----- |
| Description |  Keep this number of monthly yearly |
| Type | integer |
| Default Value | 2 |
