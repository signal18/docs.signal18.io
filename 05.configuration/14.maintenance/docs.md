---
title: Maintenance
taxonomy:
    category: docs
---
## Maintenance

**Replication-manager (2.1)**  embedded an internal cluster scheduler to help automate database maintenance.


For some maintenance operations it's required that some communication takes place from the monitored database to the replication-manager.

![mrmconsole](/images/dbjobnodetorepman.png)

1. The [Robfig](https://godoc.org/github.com/robfig/cron) scheduler is used to plan database maintenance operations like backups, optimize, and error log files fetching or rotation. **Replication-manager** initialize the DbJob scheduler and defines the interval at which the tasks are performed. Every task can be enable or disable, please refer to [scheduler](https://docs.signal18.io/configuration/maintenance/scheduler) section to configure it. By default, the scheduler is not enable.



2. To trigger such remote actions **replication-manager** open an available TCP connection with a timeout of 120s and create or populate a database table acting as a message queue named replication_manager_scehma.jobs on each database server. Those request does not produce binlogs. The port can be choosen by consuming a pool of ports defined with the variable `scheduler-db-servers-sender-ports` if no more ports are available than it start picking some server available port.
If you are using multiple clusters monitored in a replication-manager, make sure the port list does't overlap between two clusters.

 ##### `scheduler-db-servers-sender-ports` (2.3.5)

 | Item          | Value |
| ----          | ----- |
| Description   | Comma separated list of port |
| Type          | string |
| Default Value | "" |

3. **Replication-manager** prepare an envelop for the tracking the task by inserting a row inside the destination database job table, replcation_manager_schema.jobs. It will discover that the job is finish when the done field will be set to 1 and that the job is running if result is set to processing. **Replication-manager** make sure that no binlogs are created when updating the job table.


 #### Jobs table description

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


4. One specific task is for OnPremise orchestration to execute the script via SSH that trigger the task. For other orchestration, **replication-manager** just wait that the task is execute or via a job container that share the same namespace with the databases to execute the task and populate the job table.

 ##### `onpremise-ssh` (2.2)

 | Item          | Value |
| ----          | ----- |
| Description   | Connect to host via SSH using user private key |
| Type          | Boolean |
| Default Value | false |

 ##### `onpremise-ssh-credential` (2.2)

 | Item          | Value |
| ----          | ----- |
| Description   | User:password for ssh if no password using current user private key |
| Type          | String |
| Default Value | root: |

 ##### `--onpremise-ssh-private-key` (2.2)

 | Item          | Value |
| ----          | ----- |
| Description   | Private key for ssh if none use the replication-manager user HOME directory key |
| Type          | String |
| Default Value | root: |

 ##### `onpremise-ssh-port` (2.2)

 | Item          | Value |
| ----          | ----- |
| Description   | Connect to host via SSH using ssh port (default 22) |
| Type          | Integer |
| Default Value | 22 |


5. In the case of SSH execution the script is unpack in the data directory under the database directory /init/init/dbjobs_XXX. In other orchestration mode, it is deliver via the config tag.gz file that can be download by so call "init container".

6. The DbJob script is running on the database node and loop over all task by reading the job table.

7. It will select the last inserted task undone and unprocessed. If found, it will first set the result field to processing.

8. From the task execute, it will stream the result via socat to the **replication-manager** address and port find in the task. The address is define by `monitoring-address` tag.

 ##### `monitoring-address` (2.3.5)

 | Item          | Value |
| ----          | ----- |
| Description   | TCP address of replication manager reachable by DB and proxies nodes |
| Type          | string |
| Default Value | "localhost" |


9. When finished will report STDOUT into the result field and mark the task as done.

10. Error and slow query logs will be stream in files located inside the [data_directory]/[cluster_name]/[database_host_name]. For backups, **replication-manager** will store the file inside the [data_directory]/Backups/[cluster_name]/[database_host_name]. In the last step, the backups optionnaly stream inside restic for archiving, refer to the [backups](https://docs.signal18.io/configuration/maintenance/backups) section for more information.


## Cron Jobs donor task

| Task   | Desc          |
|--------|---------------|
| xtarbackup | Full physical backup xbstream format  |
| mariadbbackup | Full physical backup xbstream format  |
| errors | RDBMS error log|
| slowquery | RDBMS slow query log |
| zfssnapback | Request rewind to previous ZFS snapshot |
| optimize | Defragment all databases |


A donor scripts should dequeue the task and send the requested stream via socat to the temporary replication-manager TCP port, for those familiar with Galera Cluster it works in a similar way to SST but can be done concurrently and without stoping the receiver.



## Donor script sample

In **replication-manager-pro** we push the donor script in a Job container if you are using   **replication-manager-osc** you can customize similar script in crontab schedule every minutes.

```
#!/bin/bash
set -x
USER=root
PASSWORD=$MYSQL_ROOT_PASSWORD
MYSQL_PORT=3314
MYSQL_SERVER=127.0.0.1
CLUSTER_NAME=emma
REPLICATION_MANAGER_ADDR=127.0.0.1:10001
MYSQL_CONF=/home/emma/repdata/emma/127.0.0.1_3314/init/etc/mysql
DATADIR=/home/emma/repdata/emma/127.0.0.1_3314/var
MYSQL_CLIENT_PARAMETERS="-u$USER -h$MYSQL_SERVER -p$PASSWORD -P$MYSQL_PORT"
MYSQL_CLIENT="/usr/bin/mysql $MYSQL_CLIENT_PARAMETERS"
MYSQL_CHECK=/usr/bin/mysqlcheck
MYSQL_DUMP=/usr/bin/mysqldump
SST_RECEIVER_PORT=4444
SOCAT_BIND=0.0.0.0
MARIADB_BACKUP=/usr/bin/mariabackup
XTRABACKUP=/usr/bin/xtrabackup
INNODBACKUPEX=/usr/bin/innobackupex

ERROLOG=$DATADIR/.system/logs/error.log
SLOWLOG=$DATADIR/.system/logs/slow-query.log
BACKUPDIR=$DATADIR/.system/backup

JOBS=( "xtrabackup" "mariabackup" "error" "slowquery" "zfssnapback" "optimize" "reseedxtrabackup" "reseedmariabackup" "reseedmysqldump" "flashbackxtrabackup" "flashbackmariadbackup" "flashbackmysqldump" "stop" "restart" "start")

# OSX need socat extra path
export PATH=$PATH:/usr/local/bin

socatCleaner()
{
 lsof -t -i:$SST_RECEIVER_PORT -sTCP:LISTEN | kill -9
}

doneJob()
{
 $MYSQL_CLIENT -e "set sql_log_bin=0;UPDATE replication_manager_schema.jobs set end=NOW(), result=LOAD_FILE('/tmp/dbjob.out'), done=1  WHERE id='$ID';" &
}

pauseJob()
{
 $MYSQL_CLIENT  -e "select sleep(20);set sql_log_bin=0;UPDATE replication_manager_schema.jobs set set done=1,result=LOAD_FILE('/tmp/dbjob.out') WHERE id='$ID';" &
}

[...]

for job in "${JOBS[@]}"
do

 TASK=($(echo "SELECT concat(id,'@',server,':',port) FROM replication_manager_schema.jobs WHERE task='$job' and done=0   AND (result is NULL OR result<>'processing') order by id desc limit 1" | $MYSQL_CLIENT -N))

 ADDRESS=($(echo $TASK | awk -F@ '{ print $2 }'))
 ID=($(echo $TASK | awk -F@ '{ print $1 }'))

  if [ "$ADDRESS" == "" ]; then
    echo "No $job needed"
    case "$job" in
    start)
       if [ "curl -so /dev/null -w '%{response_code}'   http://$REPLICATION_MANAGER_ADDR/api/clusters/$CLUSTER_NAME/servers/$MYSQL_SERVER/$MYSQL_PORT/need-start" == "200" ]; then
          curl http://$REPLICATION_MANAGER_ADDR/api/clusters/$CLUSTER_NAME/servers/$MYSQL_SERVER/$MYSQL_PORT/config|tar xzvf etc/* - -C $CONFDIR/../..
    systemctl start mysql
       fi
    ;;
   esac
  else
    echo "Processing $job"
    #purge de past
    $MYSQL_CLIENT -e "set sql_log_bin=0;UPDATE replication_manager_schema.jobs set done=1 WHERE done=0 AND task='$job' AND ID<>$ID;"
    $MYSQL_CLIENT -e "set sql_log_bin=0;UPDATE replication_manager_schema.jobs set result='processing' WHERE task='$job' AND ID=$ID;"
    case "$job" in

      [...]

      reseedmariabackup)
       rm -rf $BACKUPDIR
       mkdir $BACKUPDIR
       echo "Waiting backup." >  /tmp/dbjob.out
       pauseJob
       socatCleaner
       socat -u TCP-LISTEN:$SST_RECEIVER_PORT,reuseaddr,bind=$SOCAT_BIND STDOUT | mbstream -x -C $BACKUPDIR
       # mbstream -p, --parallel
       $MARIADB_BACKUP --prepare --export --target-dir=$BACKUPDIR
       partialRestore
      ;;
      flashbackxtrabackup)
       rm -rf $BACKUPDIR
       mkdir $BACKUPDIR
       echo "Waiting backup." >  /tmp/dbjob.out
       pauseJob
       socatCleaner
       socat -u TCP-LISTEN:$SST_RECEIVER_PORT,reuseaddr,bind=$SOCAT_BIND STDOUT | xbstream -x -C $BACKUPDIR
       $XTRABACKUP --prepare --export --target-dir=$BACKUPDIR
       partialRestore
      ;;
      flashbackmariadbackup)
       rm -rf $BACKUPDIR
       mkdir $BACKUPDIR
       echo "Waiting backup." >  /tmp/dbjob.out
       pauseJob
       socatCleaner
       socat -u TCP-LISTEN:$SST_RECEIVER_PORT,reuseaddr,bind=$SOCAT_BIND STDOUT | xbstream -x -C $BACKUPDIR
       $MARIADB_BACKUP --prepare --export --target-dir=$BACKUPDIR
       partialRestore
      ;;
      xtrabackup)
       cd /docker-entrypoint-initdb.d
       $XTRABACKUP  --defaults-file=$MYSQL_CONF/my.cnf --backup -u$USER -H$MYSQL_SERVER -p$PASSWORD -P$MYSQL_PORT --stream=xbstream --target-dir=/tmp/ | socat -u stdio TCP:$ADDRESS &>/tmp/dbjob.out
      ;;
      mariabackup)
       cd /docker-entrypoint-initdb.d
       $MARIADB_BACKUP --innobackupex --defaults-file=$MYSQL_CONF/my.cnf --protocol=TCP  $MYSQL_CLIENT_PARAMETERS --stream=xbstream  | socat -u stdio TCP:$ADDRESS &>/tmp/dbjob.out
      ;;
      error)
       cat $ERROLOG| socat -u stdio TCP:$ADDRESS &>/tmp/dbjob.out
       > $ERROLOG
      ;;
      slowquery)
       cat $SLOWLOG| socat -u stdio TCP:$ADDRESS &>/tmp/dbjob.out
       > $SLOWLOG
      ;;

      [...]

  esac
  doneJob
  fi

done

```
