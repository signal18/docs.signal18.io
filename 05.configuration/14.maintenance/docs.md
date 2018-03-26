---
title: Maintenance Configuration
---

**Replication-manager (2.1)**  embedded an internal cluster scheduler.

The [Robfig](https://godoc.org/github.com/robfig/cron) scheduler is used to plan database maintenance operations like backups and log files fetching.

For some operations it's required that some TCP communication takes place from the monitored database to the replication-manager.

To trigger such remote actions **replication-manager** open a TCP connection with a timeout of 120s and create or populate a database table acting as a message queue named replication_manager_scehma.jobs.

A donor scripts should dequeue the task and send the requested stream via socat to the temporary replication-manager TCP port, for those familiar with Galera Cluster it works in a similar way to SST but can be done concurrently.          

##### `monitoring-scheduler` (2.1)

| Item | Value |
| ---- | ----- |
| Description | "Enable task scheduler" |
| Type | boolean |
| Default Value | true |

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
| optimize | Defragment all databases |


## CRON Expression Format

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

## CRON jobs configuration

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
| Default Value | "0 1 * * 0" Once every week |


##### `scheduler-db-servers-physical-backup` (2.1)

| Item | Value |
| ---- | ----- |
| Description | Schedule physical backup |
| Type | boolean |
| Default Value | true |


##### `scheduler-db-servers-physical-backup-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  Logical backup cron expression represents a set of times, using 6 space-separated fields or expression.|
| Type | string |
| Default Value | "0 0 0 * * *" Once per day at midnight |

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
| Description |  Logical backup cron expression represents a set of times, using 6 space-separated fields or expression.|
| Type | string |
| Default Value | "@every 10m" Every 10 minutes |

**replication-manager** take care of monitoring replication but can be extend to a central point for database error logs and query log analyze, using this option the logs of the database are requested on this schedule to replication-manager for a central point of analyze and alerting. We will provide the tools to classify the queries and understand databases miss configuration and workload     

Our donor script will purge the log on the database server after sent out so that growing logs don't consume the disk space and memory of the db host itself.   

##### `scheduler-db-servers-optimize` (2.1)

| Item | Value |
| ---- | ----- |
| Description | "Schedule database optimize" |
| Type | boolean |
| Default Value | true |

##### `scheduler-db-servers-optimize-cron` (2.1)

Item | Value |
| ---- | ----- |
| Description |  Logical backup cron expression represents a set of times, using 6 space-separated fields or expression.|
| Type | string |
| Default Value | ""0 0 3 1 * 5"" Every month the first at 3AM on Saturday |
scheduler-db-servers-optimize-cron",


## Donor script sample

In **replication-manager-pro** we push the donor script in services compliance but if you are using   **replication-manager-osc** you can customize similar script in crontab schedule every minutes.

```
#!/bin/bash
USER=root
PASSWORD=%%ENV:SVC_CONF_ENV_MYSQL_ROOT_PASSWORD%%
ERROLOG=/var/lib/mysql/.system/logs/errors.log
SLOWLOG=/var/lib/mysql/.system/logs/sql-slow
BACKUPDIR=/var/lib/mysql/.system/backup
DATADIR=/var/lib/mysql/
JOBS=( "xtrabackup" "error" "slowquery" "zfssnapback" "optimize" "reseedxtrabackup" "reseedmysqldump" "flashbackxtrabackup" "flashbackmysqldump" )

doneJob()
{
 /usr/bin/mysql -u$USER -p$PASSWORD -e "set sql_log_bin=0;UPDATE replication_manager_schema.jobs set end=NOW(), result=LOAD_FILE('/tmp/dbjob.out') WHERE id='$ID';" &
}

pauseJob()
{
 /usr/bin/mysql -u$USER -p$PASSWORD -e "select sleep(6);set sql_log_bin=0;UPDATE replication_manager_schema.jobs set result=LOAD_FILE('/tmp/dbjob.out') WHERE id='$ID';" &
}

partialRestore()
{
 /usr/bin/mysql -p$PASSWORD -u$USER -e "install plugin BLACKHOLE soname 'ha_blackhole.so'"
 for dir in $(ls -d $BACKUPDIR/*/ | xargs -n 1 basename | grep -vE 'mysql|performance_schema') ; do
 /usr/bin/mysql -p$PASSWORD -u$USER -e "drop database IF EXISTS $dir; CREATE DATABASE $dir;"
 chown -R mysql:mysql $BACKUPDIR

  for file in $(find $BACKUPDIR/$dir/ -name "*.exp" | xargs -n 1 basename | cut -d'.' --complement -f2-) ; do
   cat $BACKUPDIR/$dir/$file.frm | sed -e 's/\x06\x00\x49\x6E\x6E\x6F\x44\x42\x00\x00\x00/\x09\x00\x42\x4C\x41\x43\x4B\x48\x4F\x4C\x45/g' > $DATADIR/$dir/mrm_pivo.frm
   /usr/bin/mysql -p$PASSWORD -u$USER -e "ALTER TABLE $dir.mrm_pivo  engine=innodb;RENAME TABLE $dir.mrm_pivo TO $dir.$file; ALTER TABLE $dir.$file DISCARD TABLESPACE;"
   mv $BACKUPDIR/$dir/$file.ibd $DATADIR/$dir/$file.ibd
   mv $BACKUPDIR/$dir/$file.exp $DATADIR/$dir/$file.exp
   mv $BACKUPDIR/$dir/$file.cfg $DATADIR/$dir/$file.cfg
   mv $BACKUPDIR/$dir/$file.TRG $DATADIR/$dir/$file.TRG
   /usr/bin/mysql -p$PASSWORD -u$USER -e "ALTER TABLE $dir.$file IMPORT TABLESPACE"
  done
  for file in $(find $BACKUPDIR/$dir/ -name "*.MYD" | xargs -n 1 basename | cut -d'.' --complement -f2-) ; do
   mv $BACKUPDIR/$dir/$file.* $DATADIR/$dir/
  done
  for file in $(find $BACKUPDIR/$dir/ -name "*.CSV" | xargs -n 1 basename | cut -d'.' --complement -f2-) ; do
   mv $BACKUPDIR/$dir/$file.* $DATADIR/$dir/
  done
 done
 for file in $(find $BACKUPDIR/mysql/ -name "*.MYD" | xargs -n 1 basename | cut -d'.' --complement -f2-) ; do
  mv $BACKUPDIR/mysql/$file.* $DATADIR/mysql/
 done
 cat $BACKUPDIR/xtrabackup_info | grep binlog_pos | awk  -F, '{ print $3 }' | sed -e 's/GTID of the last change/set global gtid_slave_pos=/g' | /usr/bin/mysql -p$PASSWORD -u$USER
 /usr/bin/mysql -p$PASSWORD -u$USER  -e"start slave;"
}

for job in "${JOBS[@]}"
do

 TASK=($(echo "select concat(id,'@',server,':',port) from replication_manager_schema.jobs WHERE task='$job' and done=0 order by task desc limit 1" | /usr/bin/mysql -p$PASSWORD -u$USER -N))

 ADDRESS=($(echo $TASK | awk -F@ '{ print $2 }'))
 ID=($(echo $TASK | awk -F@ '{ print $1 }'))
 /usr/bin/mysql -uroot -p$PASSWORD -e "set sql_log_bin=0;UPDATE replication_manager_schema.jobs set done=1 WHERE task='$job';"

  if [ "$ADDRESS" == "" ]; then
    echo "No $job needed"
  else
    echo "Processing $job"
    case "$job" in
      reseedmysqldump)
       echo "Waiting backup." >  /tmp/dbjob.out
       pauseJob
       socat -u TCP-LISTEN:4444,reuseaddr STDOUT | gunzip | /usr/bin/mysql -p$PASSWORD -u$USER > /tmp/dbjob.out 2>&1
        /usr/bin/mysql -p$PASSWORD -u$USER -e 'start slave;'
      ;;
      flashbackmysqldump)
       echo "Waiting backup." >  /tmp/dbjob.out
       pauseJob
       socat -u TCP-LISTEN:4444,reuseaddr STDOUT | gunzip | /usr/bin/mysql -p$PASSWORD -u$USER > /tmp/dbjob.out 2>&1
        /usr/bin/mysql -p$PASSWORD -u$USER -e 'start slave;'
      ;;
      reseedxtrabackup)
       rm -rf $BACKUPDIR
       mkdir $BACKUPDIR
       echo "Waiting backup." >  /tmp/dbjob.out
       pauseJob
       socat -u TCP-LISTEN:4444,reuseaddr STDOUT | xbstream -x -C $BACKUPDIR
       xtrabackup --prepare --export --target-dir=$BACKUPDIR
       partialRestore
      ;;
      flashbackxtrabackup)
       rm -rf $BACKUPDIR
       mkdir $BACKUPDIR
       echo "Waiting backup." >  /tmp/dbjob.out
       pauseJob
       socat -u TCP-LISTEN:4444,reuseaddr STDOUT | xbstream -x -C $BACKUPDIR
       xtrabackup --prepare --export --target-dir=$BACKUPDIR
       partialRestore
      ;;
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
       LASTSNAP=`zfs list -r -t all |grep zp%%ENV:SERVICES_SVCNAME%%_pod01 | grep daily | sort -r | head -n 1  | cut -d" " -f1`
       %%ENV:SERVICES_SVCNAME%% stop
       zfs rollback $LASTSNAP
       %%ENV:SERVICES_SVCNAME%% start
      ;;
      optimize)
       /usr/bin/mysqloptimize -u$USER -p$PASSWORD --all-databases &>/tmp/dbjob.out
      ;;
  esac
  doneJob
  fi

done

```

##  Backup streaming for archive

--backup                                             Turn on Backup
