---
title: Maintenance Configuration
---

**Replication-manager (2.1)**  embedded an internal cluster scheduler.

The scheduler is used to plan database maintenance operations like backups and log files fetching.

For some operations it's required that some TCP communication takes place from the monitored database to the replication-manager.

To trigger such remote actions **replication-manager** open a TCP connection with a timeout of 120s and create or populate a database table acting as a message queue named replication_manager_scehma.jobs.

A donor scripts should dequeue the task and send the requested stream via socat to the temporary replication-manager TCP port, for those familiar with Galera Cluster it works in a similar way to SST but can be done concurrently.          


## Jobs table description

| Field  | Type          | Null | Key | Default | Extra          |
|--------|---------------|------|-----|---------|----------------|
| id     | int(11)       | NO   | PRI | NULL    | auto_increment |
| task   | varchar(20)   | YES  | MUL | NULL    |                |
| port   | int(11)       | YES  |     | NULL    |                |
| server | varchar(255)  | YES  |     | NULL    |                |
| done   | tinyint(4)    | NO   |     | 0       |                |
| result | varchar(1000) | YES  |     | NULL    |                |
| start  | datetime      | YES  |     | NULL    |                |
| end    | datetime      | YES  |     | NULL    |                |

## Cron Jobs donor task

| Task   | Desc          |
|--------|---------------|
| xtarbackup | Full physical backup xbstream format  |
| errors | RDBMS error log|
| slowquery | RDBMS slow query log |
| zfssnapback | Request rewind to previous ZFS snapshot |


## Cron Jobs configuration

##### `scheduler-db-servers-logical-backup-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  Logical backup cron expression represents a set of times, using 6 space-separated fields.|
| Type | string |
| Default Value | "0 1 * * 0" Once every week |

Logical backup in **Replication-manager (2.1)** are done via mysqldump client from the replication-manager host to the master database with non blocking and replication position options.

It's alway safer to backup the master despite it was advice to backup slaves, Signal18 don't think this a best practice to backup slave.

Yes Logical backup are slow, but for good reason it's supposed not to get all your databases CPU and IO so that you may take it without trouble to your workload!

Logical backup are needed for long time archiving of your data, it's the only way to prevent physical data corruption that can happen with  hardware issues, databases bugs, Kernel and FS bugs.    

##### `scheduler-db-servers-physical-backup-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  Logical backup cron expression represents a set of times, using 6 space-separated fields or expression.|
| Type | string |
| Default Value | "0 0 0 * * *" Once per day at midnight |

Physical backups are day to day backups in MySQL & MariaDB and works like FS snapshots,  tracking database changes while copying physical pages. It is non blocking when InnoDB storage engine is mostly used for most tables , big non transactional table will be lock for WRITES a (READ LOCK) will still enable to READ non transactional tables.

Physical backups like innodbhotbackup, xtrabackup and mariabackup need to take place on the database host itself but can be stream to **replication-manager** for archiving or reused for re-seeding new slaves or old died masters.    


##### `scheduler-db-servers-logs-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  Logical backup cron expression represents a set of times, using 6 space-separated fields or expression.|
| Type | string |
| Default Value | "@every 10m" Every 10 minutes |

**replication-manager** take care of monitoring replication but can be extend to a central point for database error logs and query log analyze, using this option the logs of the database are requested on this schedule to replication-manager for a central point of analyze and alerting. We will provide the tools to classify the queries and understand databases miss configuration and workload     

Our donor script will purge the log on the database server after sent out so that growing logs don't consume the disk space and memory of the db host itself.   

## Donor script sample

In **replication-manager-pro** we push the donor script in services compliance but if you are using   **replication-manager-osc** you can customize similar script in crontab schedule every minutes.

```
#!/bin/bash
USER=root
PASSWORD=mariadb  
ERROLOG=/var/lib/mysql/.system/logs/errors.log
SLOWLOG=/var/lib/mysql/.system/logs/slow-query.log
STARTDBSCRIPT="17311646700765639015 start"
STOPDBSCRIPT="17311646700765639015 stop"

jobs=( "xtrabackup" "error" "slowquery" "zfssnapback" "optimize" )

for job in "${jobs[@]}"
do

 TASK=($(echo "select concat(id,'@',server,':',port) from replication_manager_schema.jobs WHERE task='$job' and done=0 order by task desc limit 1" | /usr/bin/mysql -p$PASSWORD -uroot -N))

 ADDRESS=($(echo $TASK | awk -F@ '{ print $2 }'))
 ID=($(echo $TASK | awk -F@ '{ print $1 }'))
 /usr/bin/mysql -uroot -p$PASSWORD -e "set sql_log_bin=0;UPDATE replication_manager_schema.jobs set done=1 WHERE task='$job';"

  if [ "$ADDRESS" == "" ]; then
    echo "No $job needed"
  else
    echo "Processing $job"
    case "$job" in    
     xtrabackup)
      cd /docker-entrypoint-initdb.d
      /usr/bin/innobackupex  --defaults-file=/etc/mysql/my.cnf --socket='/var/run/mysqld/mysqld.sock' --slave-info --no-version-check  --user=$USER --password=$PASSWORD --stream=xbstream /tmp/ | socat -u stdio TCP:$ADDRESS &>/tmp/dbjob.out
     ;;

     error)
      cat $ERROLOG| socat -u stdio TCP:$ADDRESS &>/tmp/dbjob.out
      > $ERROLOG
    ;;

    slowquery)
     cat $SLOWLOG| socat -u stdio TCP:$ADDRESS &>/tmp/dbjob.out
     > $SLOWLOG
     ;;
     zfssnapback)
     LASTSNAP=`zfs list -r -t all |grep zp17311646700765639015_pod01 | grep daily | sort -r | head -n 1  | cut -d" " -f1`
     eval $STOPDBSCRIPT
     zfs rollback $LASTSNAP
     eval $STARTDBSCRIPT
    ;;
  esac
 /usr/bin/mysql -uroot -p$PASSWORD -e "set sql_log_bin=0;UPDATE replication_manager_schema.jobs set end=NOW(), result=LOAD_FILE('/tmp/dbjob.out') WHERE id='$ID';"
fi

done
```
