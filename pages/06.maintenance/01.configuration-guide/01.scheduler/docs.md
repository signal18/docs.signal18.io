---
title: Scheduler
taxonomy:
    category: docs
---
## 6.2.1.1 Scheduler

##### `monitoring-scheduler` (2.1)

| Item | Value |
| ---- | ----- |
| Description | "Enable task scheduler" |
| Type | boolean |
| Default Value | false |

> **Note:** The scheduler is disabled by default. On a fresh install, no maintenance jobs (backups, optimize, analyze, schema monitoring, etc.) will run until `monitoring-scheduler` is explicitly set to `true` in the configuration. The individual cron toggles (e.g. `scheduler-db-servers-logical-backup`) only take effect when the global scheduler is enabled.

## 6.2.1.2 CRON Expression Format

A cron expression represents a set of times, using 6 space-separated fields.

Field name   | Mandatory? | Allowed values  | Allowed special characters
----------   | ---------- | --------------  | --------------------------
Seconds      | Yes        | 0-59            | * / , -
Minutes      | Yes        | 0-59            | * / , -
Hours        | Yes        | 0-23            | * / , -
Day of month | Yes        | 1-31            | * / , - ?
Month        | Yes        | 1-12 or JAN-DEC | * / , -
Day of week  | Yes        | 0-6 or SUN-SAT  | * / , - ?
Note: Month and Day-of-week field values are case insensitive. "SUN", "Sun", and "sun" are equally accepted.

Special Characters
Asterisk ( * )

The asterisk indicates that the cron expression will match for all values of the field; e.g., using an asterisk in the 5th field (month) would indicate every month.

Slash ( / )

Slashes are used to describe increments of ranges. For example 3-59/15 in the 1st field (minutes) would indicate the 3rd minute of the hour and every 15 minutes thereafter. The form "*\/..." is equivalent to the form "first-last/...", that is, an increment over the largest possible range of the field. The form "N/..." is accepted as meaning "N-MAX/...", that is, starting at N, use the increment until the end of that specific range. It does not wrap around.

Comma ( , )

Commas are used to separate items of a list. For example, using "MON,WED,FRI" in the 5th field (day of week) would mean Mondays, Wednesdays and Fridays.

Hyphen ( - )

Hyphens are used to define ranges. For example, 9-17 would indicate every hour between 9am and 5pm inclusive.

Question mark ( ? )

Question mark may be used instead of '*' for leaving either day-of-month or day-of-week blank.

## 6.2.1.3 Default Schedule Summary

The following table summarizes all scheduler defaults:

| Task | Flag | Default Cron | Runs | Enabled |
| ---- | ---- | ------------ | ---- | ------- |
| Schema monitoring | `monitoring-schema-scheduler-cron` | `0 0 2 * * *` | Every day at 2:00 AM | yes |
| Checksum all tables | `monitoring-checksum-scheduler-cron` | `0 0 2 * * 5` | Every Friday at 2:00 AM | no |
| Logical backup | `scheduler-db-servers-logical-backup-cron` | `0 0 1 * * 6` | Every Saturday at 1:00 AM | yes |
| Physical backup | `scheduler-db-servers-physical-backup-cron` | `0 0 0 * * 0-4` | Sunday to Thursday at midnight | yes |
| Logs backup | `scheduler-db-servers-logs-cron` | `0 0/10 * * * *` | Every 10 minutes | yes |
| Optimize tables | `scheduler-db-servers-optimize-cron` | `0 0 3 1 * *` | 1st of every month at 3:00 AM | yes |
| Analyze tables | `scheduler-db-servers-analyze-cron` | `0 0 4 2 * *` | 2nd of every month at 4:00 AM | yes |
| Logs table rotate | `scheduler-db-servers-logs-table-rotate-cron` | `0 0 0/6 * * *` | Every 6 hours | yes |
| Rolling restart | `scheduler-rolling-restart-cron` | `0 30 11 * * *` | Every day at 11:30 AM | no |
| Rolling reprovision | `scheduler-rolling-reprov-cron` | `0 30 10 * * 5` | Every Friday at 10:30 AM | no |
| DB jobs SSH | `scheduler-jobs-ssh-cron` | `0 * * * * *` | Every minute | yes |
| SLA rotate | `scheduler-sla-rotate-cron` | `0 0 0 1 * *` | 1st of every month at midnight | yes |
| Alert disable | `scheduler-alert-disable-cron` | `0 0 0 * * 0-4` | Sunday to Thursday at midnight | no |

On startup, if no cached table metadata exists (e.g. after a fresh upgrade), a one-time schema scan is triggered automatically regardless of the cron schedule.

## 6.2.1.4 CRON Jobs Configuration

##### `scheduler-db-servers-logical-backup` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Schedule logical backup |
| Type | boolean |
| Default Value | true |

Logical backup in **Replication-manager (2.1)** are done via mysqldump client from the replication-manager host to the master database with non blocking and replication position options.

It's alway safer to backup the master despite it was advice to backup slaves, Signal18 don't think this a best practice to backup slave.

Yes Logical backup are slow, but for good reason it's supposed not to get all your databases CPU and IO so that you may take it without trouble to your workload!

Logical backup are needed for long time archiving of your data, it's the only way to prevent physical data corruption that can happen with  hardware issues, databases bugs, Kernel and FS bugs.


##### `scheduler-db-servers-logical-backup-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  Logical backup cron expression represents a set of times, using 6 space-separated fields.|
| Type | string |
| Default Value | "0 0 1 * * 6" Every Saturday at 1:00 AM |


##### `scheduler-db-servers-physical-backup` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Schedule physical backup |
| Type | boolean |
| Default Value | true |


##### `scheduler-db-servers-physical-backup-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  Physical backup cron expression represents a set of times, using 6 space-separated fields.|
| Type | string |
| Default Value | "0 0 0 * * 0-4" Sunday to Thursday at midnight |

Physical backups are day to day backups in MySQL & MariaDB and works like FS snapshots,  tracking database changes while copying physical pages. It is non blocking when InnoDB storage engine is mostly used for most tables , big non transactional table will be lock for WRITES a (READ LOCK) will still enable to READ non transactional tables.

Physical backups like innodbhotbackup, xtrabackup and mariabackup need to take place on the database host itself but can be stream to **replication-manager** for archiving or reused for re-seeding new slaves or old died masters.    

##### `scheduler-db-servers-logs` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Schedule database logs fetching |
| Type | boolean |
| Default Value | true |

##### `scheduler-db-servers-logs-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  Logs backup cron expression represents a set of times, using 6 space-separated fields.|
| Type | string |
| Default Value | "0 0/10 * * * *" Every 10 minutes |

**replication-manager** take care of monitoring replication but can be extend to a central point for database error logs and query log analyze, using this option the logs of the database are requested on this schedule to replication-manager for a central point of analyze and alerting. We will provide the tools to classify the queries and understand databases miss configuration and workload     

Our donor script will purge the log on the database server after sent out so that growing logs don't consume the disk space and memory of the db host itself.   

##### `scheduler-db-servers-optimize` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Schedule database optimize |
| Type | boolean |
| Default Value | true |

##### `scheduler-db-servers-optimize-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  Optimize cron expression represents a set of times, using 6 space-separated fields.|
| Type | string |
| Default Value | "0 0 3 1 * *" 1st of every month at 3:00 AM |

##### `scheduler-db-servers-analyze` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Schedule database analyze |
| Type | boolean |
| Default Value | true |

##### `scheduler-db-servers-analyze-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  Analyze cron expression represents a set of times, using 6 space-separated fields.|
| Type | string |
| Default Value | "0 0 4 2 * *" 2nd of every month at 4:00 AM |

##### `scheduler-db-servers-logs-table-rotate` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Schedule database logs table rotation |
| Type | boolean |
| Default Value | true |

##### `scheduler-db-servers-logs-table-rotate-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  Logs table rotate cron expression represents a set of times, using 6 space-separated fields.|
| Type | string |
| Default Value | "0 0 0/6 * * *" Every 6 hours (midnight, 6 AM, noon, 6 PM) |

##### `monitoring-schema-scheduler` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Enable scheduled schema monitoring |
| Type | boolean |
| Default Value | true |

##### `monitoring-schema-scheduler-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  Schema monitoring cron expression represents a set of times, using 6 space-separated fields.|
| Type | string |
| Default Value | "0 0 2 * * *" Every day at 2:00 AM |

Schema monitoring detects table additions, removals, and structural changes (columns, indexes). On startup, if no cached table metadata exists (e.g. after a fresh upgrade), a one-time schema scan is triggered automatically regardless of the cron schedule.

##### `monitoring-checksum-scheduler` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Enable scheduled checksum of all tables |
| Type | boolean |
| Default Value | false |

##### `monitoring-checksum-scheduler-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  Checksum cron expression represents a set of times, using 6 space-separated fields.|
| Type | string |
| Default Value | "0 0 2 * * 5" Every Friday at 2:00 AM |

##### `scheduler-rolling-restart` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Enable scheduled rolling restart of database servers |
| Type | boolean |
| Default Value | false |

##### `scheduler-rolling-restart-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  Rolling restart cron expression represents a set of times, using 6 space-separated fields.|
| Type | string |
| Default Value | "0 30 11 * * *" Every day at 11:30 AM |

##### `scheduler-rolling-reprov` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Enable scheduled rolling reprovision of database servers |
| Type | boolean |
| Default Value | false |

##### `scheduler-rolling-reprov-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  Rolling reprovision cron expression represents a set of times, using 6 space-separated fields.|
| Type | string |
| Default Value | "0 30 10 * * 5" Every Friday at 10:30 AM |

##### `scheduler-jobs-ssh` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Enable scheduled remote execution of database jobs via SSH |
| Type | boolean |
| Default Value | true |

##### `scheduler-jobs-ssh-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  DB jobs SSH cron expression represents a set of times, using 6 space-separated fields.|
| Type | string |
| Default Value | "0 * * * * *" Every minute |

##### `scheduler-sla-rotate` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Enable scheduled SLA rotation |
| Type | boolean |
| Default Value | true |

##### `scheduler-sla-rotate-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  SLA rotate cron expression represents a set of times, using 6 space-separated fields.|
| Type | string |
| Default Value | "0 0 0 1 * *" 1st of every month at midnight |

##### `scheduler-alert-disable` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Enable scheduled alert disable window |
| Type | boolean |
| Default Value | false |

##### `scheduler-alert-disable-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  Alert disable cron expression represents a set of times, using 6 space-separated fields.|
| Type | string |
| Default Value | "0 0 0 * * 0-4" Sunday to Thursday at midnight |
