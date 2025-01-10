---
title: Staging Cluster
taxonomy:
    category: docs
---
| Support Status  | Test Case |  
| ----------------|-----------|
| Production      | 1 |
| Version      | 3.0.13 |       

A staging cluster is a child cluster of a production cluster containing 3 nodes, one replica is detached from replication and proxies track the the status of the standalone node to enable dev team to test on production data  

## Staging config important setup :

```
## To be change when proxy tracking state is enable
haproxy = false

## Specify an other domain for staging
prov-db-domain = "3"

##  Specify an other source of replication for the staging cluster
replication-source-name = "staging"

## Point the production cluster to enable mutti-tiers cluster master prod to master staging extra replication source
replication-multisource-head-cluster="prod-cluster"

## Proxy Upgrade script
db-servers-state-change-script = "/data/repman/script/database_state_change.sh"

## To enable ssh to database and proxy hosts, use ssh-keygen ssh-copy-id for first deployment
onpremise-ssh = true
onpremise-ssh-credential = "root:"
onpremise-ssh-private-key = "/root/.ssh/id_rsa_preprod"
scheduler-jobs-ssh = true

##  To automate logical backup  
monitoring-scheduler = true
```

## Lesson learns:  
- Production and staging monitoring and replication users should have same name same password so that 2 similar users does not get different password in staging after an initial restore from production  
- Mydumper dist version test your restore

## Todo :
- New cluster variable --topology-staging bool  
- New cluster variable --topology-staging-refresh-script
- New cluster variable --topology-staging-post-detach-script ( obfuscation data do whatever have to be done in db)  
- Cluster menu api call to trigger: Staging refresh  

Api call should trigger similar script that will comes embedded in share
```
#!/bin/bash

# Variables
REPLICATION_MANAGER_USER="admin"
REPLICATION_MANAGER_PASSWORD="xxx"
REPLICATION_MANAGER_URL="https://repman-01:10005"
REPLICATION_MANAGER_CLUSTER_NAME="staging"
REPLICATION_MANAGER_HOST_NAME=$1
REPLICATION_MANAGER_HOST_PORT="3306"
NB_SLAVES=0

##### This bloc is for getting the replication-manager token
GET="wget -q --no-check-certificate -O- --header Content-Type:application/json"
AUTH_DATA="{\"username\": \"$REPLICATION_MANAGER_USER\", \"password\": \"$REPLICATION_MANAGER_PASSWORD\"}"
TOKEN=$($GET --post-data "$AUTH_DATA" --header Accept:text/html $REPLICATION_MANAGER_URL/api/login)

function get {
	$GET --header Accept:application/json --header "Authorization: Bearer $TOKEN" "$@"
}

# Counting the slaves, depending, we will play one of the two following scenarios
NB_SLAVES=$(get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/topology/slaves | jq length)
echo $NB_SLAVES

# Scenario 1 : 2 slaves, then we will stop the replication on one that will be the "staging"  
if [ $NB_SLAVES -eq 2 ]; then
  echo "picking first slave \n"
  ID=$(get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/topology/slaves | jq '.[1].id' | sed 's/"//g' )
  PORT=$(get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/topology/slaves | jq '.[1].port')
  echo "$ID:$PORT"
  echo "Reseting first slave \n"
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/servers/$ID/actions/reset-slave-all
  sleep 2
  echo "Stopping first server slave replication \n"
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/servers/$ID/actions/stop-slave
  sleep 20
  echo "Stopping first mariadb server \n"
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/servers/$ID/actions/stop
  echo "Starting first mariadb server \n"
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/servers/$ID/actions/start
  loop=true
  while $loop; do
    sleep 1
    IO_THREADS=$(get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/topology/slaves | jq '.[1].replications[0].slaveIoRunning.String')
    SQL_THREADS=$(get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/topology/slaves | jq '.[1].replications[0].slaveSqlRunning.String')
    echo $IO_THREADS
    echo $SQL_THREADS
    if [ "$IO_THREADS" == "\"No\"" ] || [ "$SQL_THREADS" == "\"No\"" ]; then
      loop=false
    fi
  done

  echo "Getting and saving slaves initial statuses \n"
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/topology/slaves | jq '.[1].replications'>replications.save

  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/servers/$ID/actions/reset-slave-all
  sleep 2
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/servers/$ID/actions/stop
  echo "Waiting for database server $ID to status failed \n"
  loop=true
  while $loop; do
    ID_CHECK=$(get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/topology/servers | jq -c '.[] | select(.state == "Failed").id' | sed 's/"//g')
    if [ "$ID_CHECK" == "$ID" ]; then
      loop=false
    fi
  done
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/servers/$ID/actions/start
fi

if [ $NB_SLAVES -eq 1 ]; then
  echo "picking last slave and founding standalone \n"
  ID=$(get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/topology/servers | jq -c '.[] | select( .state == "StandAlone" ).id' | sed 's/"//g')
  echo "found standalone server $ID \n"
  echo "reseting master position on standalone \n"
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/servers/$ID/actions/reset-master
  echo "setup replication manager for reseeding \n"
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/settings/actions/switch/autoseed
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/settings/actions/switch/autorejoin-logical-backup
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/settings/actions/switch/autorejoin-force-restore
  sleep 2
  echo "Stopping database server $ID \n"
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/servers/$ID/actions/stop
  echo "Waiting for database server $ID to status failed \n"
  loop=true
  while $loop; do
    ID_CHECK=$(get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/topology/servers | jq -c '.[] | select(.state == "Failed").id' | sed 's/"//g')
    if [ "$ID_CHECK" == "$ID" ]; then
      loop=false
    fi
  done
  echo "Start database server $ID \n"
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/servers/$ID/actions/start
  sleep 20
  echo "Reseting replciation manager settings for reseed = false \n"
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/settings/actions/switch/autoseed
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/settings/actions/switch/autorejoin-logical-backup
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/settings/actions/switch/autorejoin-force-restore

###### Now set last slave as standalone



# Get the last available slave
ID_SLAVE=$(get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/topology/servers | jq -c '.[] | select(.state == "Slave").id' | sed 's/"//g')
  echo "last slave found for staging $ID_SLAVE \n"
  echo "Stopping replication on last slave \n"
  get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/servers/$ID_SLAVE/actions/stop-slave
  loop=true
  while $loop; do
    echo "Waiting replication to stop \n"
    sleep 5
    IO_THREADS=$(get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/topology/slaves | jq --arg id "$ID_SLAVE" '.[] | select(.id == $id).replications[0].slaveIoRunning.String')
    SQL_THREADS=$(get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/topology/slaves | jq --arg id "$ID_SLAVE" '.[] | select(.id == $id).replications[0].slaveSqlRunning.String')
#    IO_THREADS=$(get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/topology/slaves | jq '.[] | select( .id == $ID_SLAVE ).replications[0].slaveIoRunning.String')
#    SQL_THREADS=$(get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/topology/slaves | jq '.[] | select( .id == $ID_SLAVE ).replications[0].slaveSqlRunning.String')
    echo $IO_THREADS
    echo $SQL_THREADS
    if [ "$IO_THREADS" == "\"No\"" ] || [ "$SQL_THREADS" == "\"No\"" ]; then
      loop=false
    fi
    done
    echo "Saving replication info in replication.save \n"
    get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/topology/slaves | jq --arg id "$ID_SLAVE" '.[] | select(.id == $id).replications' > replications.save
#    get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/topology/slaves | jq '.[] | select( .id == $ID_SLAVE ).replications'>replications.save
    echo "Reset all replication information \n"
    get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/servers/$ID_SLAVE/actions/reset-slave-all
    sleep 2
    get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/servers/$ID_SLAVE/actions/stop
    sleep 2
    get $REPLICATION_MANAGER_URL/api/clusters/$REPLICATION_MANAGER_CLUSTER_NAME/servers/$ID_SLAVE/actions/start
fi
```
- Adapt the script to not stop start the database nodes
- Adapt the script to use env variables deploy in the bash instead of hardcoded variables

Today we can customize status change script to trigger some dedicated proxy  config  reload   bu need native proxy integration

## To be removed after integration
database_state_change.sh
```
#!/bin/bash
# This script is given as sample and will be overwrite on upgrade
# db-servers-state-change-script
echo "Database state change script args"
echo "Script:$0, Cluster:$1, Host:$2, Port:$3, State:$4, OldState:$5"
if [ "$4" = "StandAlone" ]; then
        scp /data/repman/script/ha.cfg.$2 root@staging-proxy:/etc/haproxy/haproxy.cfg
        ssh root@staging-proxy "systemctl reload haproxy"
fi
```
## Todo
- New cluster variables  haproxy more variables  haproxy-staging-port, haproxy-staging-bind,  haproxy-staging-backend 
- New cluster variable  proxysql-write-track-state="master|standalone" ,  proxysql-read-track-state="slave|standalone"      
