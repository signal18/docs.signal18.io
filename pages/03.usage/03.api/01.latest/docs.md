---
title: API Client Usage (v3)
taxonomy:
    category: docs
---

## 1. All endpoints

### 1.1  auth

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| POST | /api/login | [post API login](#post-api-login) | User login |
  


### 1.2  cloud18

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/for-sale | [get API clusters for sale](#get-api-clusters-for-sale) | Retrieve peer clusters for sale |
| GET | /api/clusters/peers | [get API clusters peers](#get-api-clusters-peers) | Retrieve peer clusters for a user |
| GET | /api/terms | [get API terms](#get-api-terms) | Retrieves terms |
| POST | /api/clusters/{clusterName}/sales/accept-subscription | [post API clusters cluster name sales accept subscription](#post-api-clusters-cluster-name-sales-accept-subscription) | Accept a subscription for a specific cluster |
| POST | /api/clusters/{clusterName}/sales/end-subscription | [post API clusters cluster name sales end subscription](#post-api-clusters-cluster-name-sales-end-subscription) | Remove a sponsor from a specific cluster |
| POST | /api/clusters/{clusterName}/sales/refuse-subscription | [post API clusters cluster name sales refuse subscription](#post-api-clusters-cluster-name-sales-refuse-subscription) | Reject a subscription for a specific cluster |
| POST | /api/clusters/{clusterName}/subscribe | [post API clusters cluster name subscribe](#post-api-clusters-cluster-name-subscribe) | Subscribe a user to a cluster |
  


### 1.3  cluster

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| DELETE | /api/clusters/actions/delete/{clusterName} | [delete API clusters actions delete cluster name](#delete-api-clusters-actions-delete-cluster-name) | Delete a cluster |
| GET | /api/clusters | [get API clusters](#get-api-clusters) | Fetch clusters |
| GET | /api/clusters/{clusterName} | [get API clusters cluster name](#get-api-clusters-cluster-name) | Retrieve details of a cluster |
| GET | /api/clusters/{clusterName}/diffvariables | [get API clusters cluster name diffvariables](#get-api-clusters-cluster-name-diffvariables) | Retrieve variable differences for a specific cluster |
| GET | /api/clusters/{clusterName}/jobs | [get API clusters cluster name jobs](#get-api-clusters-cluster-name-jobs) | Retrieve job entries for a specific cluster |
| GET | /api/clusters/{clusterName}/queryrules | [get API clusters cluster name queryrules](#get-api-clusters-cluster-name-queryrules) | Retrieve query rules for a specific cluster |
| GET | /api/clusters/{clusterName}/status | [get API clusters cluster name status](#get-api-clusters-cluster-name-status) | Retrieve status of a cluster |
| GET | /api/clusters/{clusterName}/top | [get API clusters cluster name top](#get-api-clusters-cluster-name-top) | Retrieve top metrics for a specific cluster |
| GET | /api/clusters/{clusterName}/topology/crashes | [get API clusters cluster name topology crashes](#get-api-clusters-cluster-name-topology-crashes) | Retrieve crashes for a specific cluster |
| POST | /api/clusters/actions/add/{clusterName} | [post API clusters actions add cluster name](#post-api-clusters-actions-add-cluster-name) | Add a new cluster |
| POST | /api/clusters/{clusterName}/actions/waitdatabases | [post API clusters cluster name actions waitdatabases](#post-api-clusters-cluster-name-actions-waitdatabases) | Wait for databases to be ready for a specific cluster |
  


### 1.4  cluster_actions

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| POST | /api/clusters/{clusterName}/actions/failover | [post API clusters cluster name actions failover](#post-api-clusters-cluster-name-actions-failover) | Handles the failover process for a given cluster. |
| POST | /api/clusters/{clusterName}/actions/optimize | [post API clusters cluster name actions optimize](#post-api-clusters-cluster-name-actions-optimize) | Optimize a specific cluster |
| POST | /api/clusters/{clusterName}/actions/reset-failover-control | [post API clusters cluster name actions reset failover control](#post-api-clusters-cluster-name-actions-reset-failover-control) | Reset failover control for a specific cluster |
| POST | /api/clusters/{clusterName}/actions/reset-sla | [post API clusters cluster name actions reset SLA](#post-api-clusters-cluster-name-actions-reset-sla) | Reset SLA for a specific cluster |
| POST | /api/clusters/{clusterName}/actions/rotate-passwords | [post API clusters cluster name actions rotate passwords](#post-api-clusters-cluster-name-actions-rotate-passwords) | Rotate passwords for a specific cluster |
| POST | /api/clusters/{clusterName}/actions/switchover | [post API clusters cluster name actions switchover](#post-api-clusters-cluster-name-actions-switchover) | Handles the switchover process for a given cluster. |
| POST | /api/clusters/settings/actions/reload-clusters-plans | [post API clusters settings actions reload clusters plans](#post-api-clusters-settings-actions-reload-clusters-plans) | Reload cluster plans |
  


### 1.5  cluster_backup

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| POST | /api/clusters/{clusterName}/actions/master-physical-backup | [post API clusters cluster name actions master physical backup](#post-api-clusters-cluster-name-actions-master-physical-backup) | Perform a physical backup for the master of a specific cluster |
  


### 1.6  cluster_backups

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/backups | [get API clusters cluster name backups](#get-api-clusters-cluster-name-backups) | Retrieve backups for a specific cluster |
  


### 1.7  cluster_certificates

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/certificates | [get API clusters cluster name certificates](#get-api-clusters-cluster-name-certificates) | Retrieve client certificates for a specific cluster |
| POST | /api/clusters/{clusterName}/actions/certificates-rotate | [post API clusters cluster name actions certificates rotate](#post-api-clusters-cluster-name-actions-certificates-rotate) | Rotate keys for a specific cluster |
  


### 1.8  cluster_graphite

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/graphite-filterlist | [get API clusters cluster name graphite filterlist](#get-api-clusters-cluster-name-graphite-filterlist) | Retrieve Graphite filter list for a specific cluster |
| POST | /api/clusters/{clusterName}/settings/actions/reload-graphite-filterlist | [post API clusters cluster name settings actions reload graphite filterlist](#post-api-clusters-cluster-name-settings-actions-reload-graphite-filterlist) | Reload Graphite filter list for a specific cluster |
| POST | /api/clusters/{clusterName}/settings/actions/reset-graphite-filterlist/{template} | [post API clusters cluster name settings actions reset graphite filterlist template](#post-api-clusters-cluster-name-settings-actions-reset-graphite-filterlist-template) | Reset Graphite filter list for a specific cluster |
| POST | /api/clusters/{clusterName}/settings/actions/set-graphite-filterlist/{filterType} | [post API clusters cluster name settings actions set graphite filterlist filter type](#post-api-clusters-cluster-name-settings-actions-set-graphite-filterlist-filter-type) | Set Graphite filter list for a specific cluster |
  


### 1.9  cluster_maintenance

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| POST | /api/clusters/{clusterName}/actions/cancel-rolling-restart | [post API clusters cluster name actions cancel rolling restart](#post-api-clusters-cluster-name-actions-cancel-rolling-restart) | Cancel rolling restart for a specific cluster |
| POST | /api/clusters/{clusterName}/actions/rolling | [post API clusters cluster name actions rolling](#post-api-clusters-cluster-name-actions-rolling) | Handles the rolling restart process for a given cluster. |
  


### 1.10  cluster_monitor

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| POST | /api/clusters/{clusterName}/actions/addserver/{host}/{port} | [post API clusters cluster name actions addserver host port](#post-api-clusters-cluster-name-actions-addserver-host-port) | Add a server to a specific cluster |
| POST | /api/clusters/{clusterName}/actions/addserver/{host}/{port}/{type} | [post API clusters cluster name actions addserver host port type](#post-api-clusters-cluster-name-actions-addserver-host-port-type) | Add a server to a specific cluster |
| POST | /cluster/{clusterName}/actions/dropserver/{host}/{port} | [post cluster cluster name actions dropserver host port](#post-cluster-cluster-name-actions-dropserver-host-port) | Drop a server monitor from a cluster |
| POST | /cluster/{clusterName}/actions/dropserver/{host}/{port}/{type} | [post cluster cluster name actions dropserver host port type](#post-cluster-cluster-name-actions-dropserver-host-port-type) | Drop a server monitor from a cluster |
  


### 1.11  cluster_provision

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| POST | /api/clusters/{clusterName}/actions/cancel-rolling-reprov | [post API clusters cluster name actions cancel rolling reprov](#post-api-clusters-cluster-name-actions-cancel-rolling-reprov) | Cancel rolling reprovision for a specific cluster |
| POST | /api/clusters/{clusterName}/services/actions/provision | [post API clusters cluster name services actions provision](#post-api-clusters-cluster-name-services-actions-provision) | Provision services for a specific cluster |
| POST | /api/clusters/{clusterName}/services/actions/unprovision | [post API clusters cluster name services actions unprovision](#post-api-clusters-cluster-name-services-actions-unprovision) | Unprovision services for a specific cluster |
  


### 1.12  cluster_replication

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| POST | /api/clusters/{clusterName}/actions/replication/bootstrap/{topology} | [post API clusters cluster name actions replication bootstrap topology](#post-api-clusters-cluster-name-actions-replication-bootstrap-topology) | Bootstrap replication for a specific cluster |
| POST | /api/clusters/{clusterName}/actions/replication/cleanup | [post API clusters cluster name actions replication cleanup](#post-api-clusters-cluster-name-actions-replication-cleanup) | Cleanup replication bootstrap for a specific cluster |
  


### 1.13  cluster_schema

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/schema | [get API clusters cluster name schema](#get-api-clusters-cluster-name-schema) | Retrieve schema information for a specific cluster |
| POST | /api/clusters/{clusterName}/actions/checksum-all-tables | [post API clusters cluster name actions checksum all tables](#post-api-clusters-cluster-name-actions-checksum-all-tables) | Calculate checksum for all tables in a specific cluster |
| POST | /api/clusters/{clusterName}/schema/{schemaName}/{tableName}/actions/checksum-table | [post API clusters cluster name schema schema name table name actions checksum table](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-checksum-table) | Calculate checksum for a specific table in a specific cluster |
| POST | /api/clusters/{clusterName}/schema/{schemaName}/{tableName}/actions/move-table/{clusterShard} | [post API clusters cluster name schema schema name table name actions move table cluster shard](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-move-table-cluster-shard) | Move a table to a different shard cluster |
| POST | /api/clusters/{clusterName}/schema/{schemaName}/{tableName}/actions/reshard-table | [post API clusters cluster name schema schema name table name actions reshard table](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table) | Reshard a table for a specific cluster |
| POST | /api/clusters/{clusterName}/schema/{schemaName}/{tableName}/actions/reshard-table/{clusterList} | [post API clusters cluster name schema schema name table name actions reshard table cluster list](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-cluster-list) | Reshard a table for a specific cluster |
| POST | /api/clusters/{clusterName}/schema/{schemaName}/{tableName}/actions/universal-table | [post API clusters cluster name schema schema name table name actions universal table](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-universal-table) | Set a universal table for a specific cluster |
  


### 1.14  cluster_settings

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/settings | [get API clusters cluster name settings](#get-api-clusters-cluster-name-settings) | Retrieve settings for a specific cluster |
| POST | /api/clusters/{clusterName}/settings/actions/certificates-reload | [post API clusters cluster name settings actions certificates reload](#post-api-clusters-cluster-name-settings-actions-certificates-reload) | Reload client certificates for a specific cluster |
| POST | /api/clusters/{clusterName}/settings/actions/discover | [post API clusters cluster name settings actions discover](#post-api-clusters-cluster-name-settings-actions-discover) | Discover settings for a specific cluster |
| POST | /api/clusters/{clusterName}/settings/actions/reload | [post API clusters cluster name settings actions reload](#post-api-clusters-cluster-name-settings-actions-reload) | Reload cluster settings |
| POST | /api/clusters/{clusterName}/settings/actions/set-cron/{settingName}/{settingValue} | [post API clusters cluster name settings actions set cron setting name setting value](#post-api-clusters-cluster-name-settings-actions-set-cron-setting-name-setting-value) | Set cron jobs for a specific cluster |
| POST | /api/clusters/{clusterName}/settings/actions/set/{settingName}/{settingValue} | [post API clusters cluster name settings actions set setting name setting value](#post-api-clusters-cluster-name-settings-actions-set-setting-name-setting-value) | Set settings for a specific cluster |
| POST | /api/clusters/{clusterName}/settings/actions/switch/{settingName} | [post API clusters cluster name settings actions switch setting name](#post-api-clusters-cluster-name-settings-actions-switch-setting-name) | Switch settings for a specific cluster |
| POST | /api/clusters/settings/actions/set/{settingName}/{settingValue} | [post API clusters settings actions set setting name setting value](#post-api-clusters-settings-actions-set-setting-name-setting-value) | Set global settings for the server |
  


### 1.15  cluster_tags

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/tags | [get API clusters cluster name tags](#get-api-clusters-cluster-name-tags) | Retrieve tags for a specific cluster |
| POST | /api/clusters/{clusterName}/settings/actions/add-db-tag/{tagValue} | [post API clusters cluster name settings actions add db tag tag value](#post-api-clusters-cluster-name-settings-actions-add-db-tag-tag-value) | Add a tag to a specific cluster |
| POST | /api/clusters/{clusterName}/settings/actions/add-proxy-tag/{tagValue} | [post API clusters cluster name settings actions add proxy tag tag value](#post-api-clusters-cluster-name-settings-actions-add-proxy-tag-tag-value) | Add a proxy tag to a specific cluster |
| POST | /api/clusters/{clusterName}/settings/actions/apply-dynamic-config | [post API clusters cluster name settings actions apply dynamic config](#post-api-clusters-cluster-name-settings-actions-apply-dynamic-config) | Apply dynamic configuration for a specific cluster |
| POST | /api/clusters/{clusterName}/settings/actions/drop-db-tag/{tagValue} | [post API clusters cluster name settings actions drop db tag tag value](#post-api-clusters-cluster-name-settings-actions-drop-db-tag-tag-value) | Remove a tag from a specific cluster |
| POST | /api/clusters/{clusterName}/settings/actions/drop-proxy-tag/{tagValue} | [post API clusters cluster name settings actions drop proxy tag tag value](#post-api-clusters-cluster-name-settings-actions-drop-proxy-tag-tag-value) | Remove a proxy tag from a specific cluster |
  


### 1.16  cluster_test

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| POST | /api/clusters/{clusterName}/actions/sysbench | [post API clusters cluster name actions sysbench](#post-api-clusters-cluster-name-actions-sysbench) | Run sysbench for a specific cluster |
| POST | /api/clusters/{clusterName}/tests/actions/run/all | [post API clusters cluster name tests actions run all](#post-api-clusters-cluster-name-tests-actions-run-all) | Run all tests for a given cluster |
| POST | /api/clusters/{clusterName}/tests/actions/run/{testName} | [post API clusters cluster name tests actions run test name](#post-api-clusters-cluster-name-tests-actions-run-test-name) | Run a specific test for a given cluster |
  


### 1.17  cluster_topology

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/shardclusters | [get API clusters cluster name shardclusters](#get-api-clusters-cluster-name-shardclusters) | Retrieve shard clusters for a specific cluster |
| GET | /api/clusters/{clusterName}/topology/alerts | [get API clusters cluster name topology alerts](#get-api-clusters-cluster-name-topology-alerts) | Shows the alerts for that specific named cluster |
| GET | /api/clusters/{clusterName}/topology/logs | [get API clusters cluster name topology logs](#get-api-clusters-cluster-name-topology-logs) | Retrieve logs for a specific cluster |
| GET | /api/clusters/{clusterName}/topology/master | [get API clusters cluster name topology master](#get-api-clusters-cluster-name-topology-master) | Retrieve master of a cluster |
| GET | /api/clusters/{clusterName}/topology/proxies | [get API clusters cluster name topology proxies](#get-api-clusters-cluster-name-topology-proxies) | Shows the proxies for that specific named cluster |
| GET | /api/clusters/{clusterName}/topology/servers | [get API clusters cluster name topology servers](#get-api-clusters-cluster-name-topology-servers) | Retrieve servers for a specific cluster |
| GET | /api/clusters/{clusterName}/topology/slaves | [get API clusters cluster name topology slaves](#get-api-clusters-cluster-name-topology-slaves) | Shows the slaves for that specific named cluster |
| POST | /api/clusters/{clusterName}/actions/add/{clusterShardingName} | [post API clusters cluster name actions add cluster sharding name](#post-api-clusters-cluster-name-actions-add-cluster-sharding-name) | Add a sharding cluster to an existing cluster |
  


### 1.18  cluster_traffics

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| POST | /api/clusters/{clusterName}/actions/start-traffic | [post API clusters cluster name actions start traffic](#post-api-clusters-cluster-name-actions-start-traffic) | Start traffic for a specific cluster |
| POST | /api/clusters/{clusterName}/actions/stop-traffic | [post API clusters cluster name actions stop traffic](#post-api-clusters-cluster-name-actions-stop-traffic) | Stop traffic for a specific cluster |
  


### 1.19  cluster_vault

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| POST | /api/clusters/{clusterName}/send-vault-token | [post API clusters cluster name send vault token](#post-api-clusters-cluster-name-send-vault-token) | Send Vault token to a specific cluster |
  


### 1.20  database

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/need-rolling-reprov | [get API clusters cluster name need rolling reprov](#get-api-clusters-cluster-name-need-rolling-reprov) | Check if a cluster needs a rolling reprovision |
| GET | /api/clusters/{clusterName}/need-rolling-restart | [get API clusters cluster name need rolling restart](#get-api-clusters-cluster-name-need-rolling-restart) | Check if a cluster needs a rolling restart |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-innodb-monitor | [get API clusters cluster name servers server name actions toogle innodb monitor](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-innodb-monitor) | Toggle InnoDB monitor on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/all-slaves-status | [get API clusters cluster name servers server name all slaves status](#get-api-clusters-cluster-name-servers-server-name-all-slaves-status) | Get status of all slaves of a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/is-master | [get API clusters cluster name servers server name is master](#get-api-clusters-cluster-name-servers-server-name-is-master) | Check if a server is a master |
| GET | /api/clusters/{clusterName}/servers/{serverName}/is-slave | [get API clusters cluster name servers server name is slave](#get-api-clusters-cluster-name-servers-server-name-is-slave) | Check if a server is a slave |
| GET | /api/clusters/{clusterName}/servers/{serverName}/master-status | [get API clusters cluster name servers server name master status](#get-api-clusters-cluster-name-servers-server-name-master-status) | Get master status of a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/meta-data-locks | [get API clusters cluster name servers server name meta data locks](#get-api-clusters-cluster-name-servers-server-name-meta-data-locks) | Get metadata locks of a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/processlist | [get API clusters cluster name servers server name processlist](#get-api-clusters-cluster-name-servers-server-name-processlist) | Get process list of a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/config | [get API clusters cluster name servers server name server port config](#get-api-clusters-cluster-name-servers-server-name-server-port-config) | Get server port configuration |
| GET | /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/is-master | [get API clusters cluster name servers server name server port is master](#get-api-clusters-cluster-name-servers-server-name-server-port-is-master) | Check if a server port is a master |
| GET | /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/is-slave | [get API clusters cluster name servers server name server port is slave](#get-api-clusters-cluster-name-servers-server-name-server-port-is-slave) | Check if a server port is a slave |
| GET | /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/need-config-change | [get API clusters cluster name servers server name server port need config change](#get-api-clusters-cluster-name-servers-server-name-server-port-need-config-change) | Check if a server needs a config change |
| GET | /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/need-prov | [get API clusters cluster name servers server name server port need prov](#get-api-clusters-cluster-name-servers-server-name-server-port-need-prov) | Check if a server needs provisioning |
| GET | /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/need-reprov | [get API clusters cluster name servers server name server port need reprov](#get-api-clusters-cluster-name-servers-server-name-server-port-need-reprov) | Check if a server needs re-provisioning |
| GET | /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/need-restart | [get API clusters cluster name servers server name server port need restart](#get-api-clusters-cluster-name-servers-server-name-server-port-need-restart) | Check if a server needs a restart |
| GET | /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/need-start | [get API clusters cluster name servers server name server port need start](#get-api-clusters-cluster-name-servers-server-name-server-port-need-start) | Check if a server needs to start |
| GET | /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/need-stop | [get API clusters cluster name servers server name server port need stop](#get-api-clusters-cluster-name-servers-server-name-server-port-need-stop) | Check if a server needs to stop |
| GET | /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/need-unprov | [get API clusters cluster name servers server name server port need unprov](#get-api-clusters-cluster-name-servers-server-name-server-port-need-unprov) | Check if a server needs unprovisioning |
| GET | /api/clusters/{clusterName}/servers/{serverName}/service-opensvc | [get API clusters cluster name servers server name service opensvc](#get-api-clusters-cluster-name-servers-server-name-service-opensvc) | Get database service configuration of a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/status | [get API clusters cluster name servers server name status](#get-api-clusters-cluster-name-servers-server-name-status) | Get status of a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/status-delta | [get API clusters cluster name servers server name status delta](#get-api-clusters-cluster-name-servers-server-name-status-delta) | Get status delta of a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/status-innodb | [get API clusters cluster name servers server name status innodb](#get-api-clusters-cluster-name-servers-server-name-status-innodb) | Get InnoDB status of a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/tables | [get API clusters cluster name servers server name tables](#get-api-clusters-cluster-name-servers-server-name-tables) | Get tables of a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/variables | [get API clusters cluster name servers server name variables](#get-api-clusters-cluster-name-servers-server-name-variables) | Get variables of a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/vtables | [get API clusters cluster name servers server name vtables](#get-api-clusters-cluster-name-servers-server-name-vtables) | Get virtual tables of a server |
  


### 1.21  database_actions

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/optimize | [get API clusters cluster name servers server name actions optimize](#get-api-clusters-cluster-name-servers-server-name-actions-optimize) | Optimize a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/start | [get API clusters cluster name servers server name actions start](#get-api-clusters-cluster-name-servers-server-name-actions-start) | Start a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/stop | [get API clusters cluster name servers server name actions stop](#get-api-clusters-cluster-name-servers-server-name-actions-stop) | Stop a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-meta-data-locks | [get API clusters cluster name servers server name actions toogle meta data locks](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-meta-data-locks) | Toggle metadata locks on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-query-response-time | [get API clusters cluster name servers server name actions toogle query response time](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-query-response-time) | Toggle query response time on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-read-only | [get API clusters cluster name servers server name actions toogle read only](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-read-only) | Toggle read-only mode on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/wait-innodb-purge | [get API clusters cluster name servers server name actions wait innodb purge](#get-api-clusters-cluster-name-servers-server-name-actions-wait-innodb-purge) | Wait for InnoDB purge on a server |
  


### 1.22  database_backup

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/backup-logical | [get API clusters cluster name servers server name actions backup logical](#get-api-clusters-cluster-name-servers-server-name-actions-backup-logical) | Perform a logical backup on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/backup-physical | [get API clusters cluster name servers server name actions backup physical](#get-api-clusters-cluster-name-servers-server-name-actions-backup-physical) | Perform a physical backup on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/reseed/{backupMethod} | [get API clusters cluster name servers server name actions reseed backup method](#get-api-clusters-cluster-name-servers-server-name-actions-reseed-backup-method) | Reseed a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/reseed-cancel/{task} | [get API clusters cluster name servers server name actions reseed cancel task](#get-api-clusters-cluster-name-servers-server-name-actions-reseed-cancel-task) | Cancel a reseed task on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/backup | [get API clusters cluster name servers server name server port backup](#get-api-clusters-cluster-name-servers-server-name-server-port-backup) | Perform a physical backup on a server port |
| POST | /api/clusters/{clusterName}/servers/{serverName}/actions/pitr | [post API clusters cluster name servers server name actions pitr](#post-api-clusters-cluster-name-servers-server-name-actions-pitr) | Perform a point-in-time recovery on a server |
  


### 1.23  database_logs

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/backup-error-log | [get API clusters cluster name servers server name actions backup error log](#get-api-clusters-cluster-name-servers-server-name-actions-backup-error-log) | Perform a backup of the error log on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/backup-slowquery-log | [get API clusters cluster name servers server name actions backup slowquery log](#get-api-clusters-cluster-name-servers-server-name-actions-backup-slowquery-log) | Perform a backup of the slow query log on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/flush-logs | [get API clusters cluster name servers server name actions flush logs](#get-api-clusters-cluster-name-servers-server-name-actions-flush-logs) | Flush logs on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-sql-error-log | [get API clusters cluster name servers server name actions toogle SQL error log](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-sql-error-log) | Toggle SQL error log on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/errorlog | [get API clusters cluster name servers server name errorlog](#get-api-clusters-cluster-name-servers-server-name-errorlog) | Get error log of a server |
  


### 1.24  database_maintenance

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/del-maintenance | [get API clusters cluster name servers server name actions del maintenance](#get-api-clusters-cluster-name-servers-server-name-actions-del-maintenance) | Delete maintenance mode on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/maintenance | [get API clusters cluster name servers server name actions maintenance](#get-api-clusters-cluster-name-servers-server-name-actions-maintenance) | Toggle maintenance mode on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/set-maintenance | [get API clusters cluster name servers server name actions set maintenance](#get-api-clusters-cluster-name-servers-server-name-actions-set-maintenance) | Set a server to maintenance mode |
  


### 1.25  database_provision

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/provision | [get API clusters cluster name servers server name actions provision](#get-api-clusters-cluster-name-servers-server-name-actions-provision) | Provision a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/unprovision | [get API clusters cluster name servers server name actions unprovision](#get-api-clusters-cluster-name-servers-server-name-actions-unprovision) | Unprovision a server |
  


### 1.26  database_queries

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/reset-pfs-queries | [get API clusters cluster name servers server name actions reset pfs queries](#get-api-clusters-cluster-name-servers-server-name-actions-reset-pfs-queries) | Reset PFS queries on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/set-long-query-time/{queryTime} | [get API clusters cluster name servers server name actions set long query time query time](#get-api-clusters-cluster-name-servers-server-name-actions-set-long-query-time-query-time) | Set long query time on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-pfs-slow-query | [get API clusters cluster name servers server name actions toogle pfs slow query](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-pfs-slow-query) | Toggle PFS slow query capture on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-slow-query | [get API clusters cluster name servers server name actions toogle slow query](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query) | Toggle slow query on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-slow-query-capture | [get API clusters cluster name servers server name actions toogle slow query capture](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-capture) | Toggle slow query capture on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-slow-query-table | [get API clusters cluster name servers server name actions toogle slow query table](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-table) | Toggle slow query table mode on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/digest-statements-pfs | [get API clusters cluster name servers server name digest statements pfs](#get-api-clusters-cluster-name-servers-server-name-digest-statements-pfs) | Get PFS statements of a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/digest-statements-slow | [get API clusters cluster name servers server name digest statements slow](#get-api-clusters-cluster-name-servers-server-name-digest-statements-slow) | Get PFS statements from the slow log of a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/queries/{queryDigest}/actions/analyze-pfs | [get API clusters cluster name servers server name queries query digest actions analyze pfs](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-pfs) | Analyze a query using PFS on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/queries/{queryDigest}/actions/analyze-slowlog | [get API clusters cluster name servers server name queries query digest actions analyze slowlog](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-slowlog) | Analyze a query using the slow log on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/queries/{queryDigest}/actions/explain-pfs | [get API clusters cluster name servers server name queries query digest actions explain pfs](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-pfs) | Explain a query using PFS on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/queries/{queryDigest}/actions/explain-slowlog | [get API clusters cluster name servers server name queries query digest actions explain slowlog](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-slowlog) | Explain a query using the slow log on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/queries/{queryDigest}/actions/kill-query | [get API clusters cluster name servers server name queries query digest actions kill query](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-query) | Kill a query on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/queries/{queryDigest}/actions/kill-thread | [get API clusters cluster name servers server name queries query digest actions kill thread](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-thread) | Kill a thread on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/query-response-time | [get API clusters cluster name servers server name query response time](#get-api-clusters-cluster-name-servers-server-name-query-response-time) | Get query response time of a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/slow-queries | [get API clusters cluster name servers server name slow queries](#get-api-clusters-cluster-name-servers-server-name-slow-queries) | Get slow log of a server |
  


### 1.27  database_replication

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/reset-master | [get API clusters cluster name servers server name actions reset master](#get-api-clusters-cluster-name-servers-server-name-actions-reset-master) | Reset the master on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/reset-slave-all | [get API clusters cluster name servers server name actions reset slave all](#get-api-clusters-cluster-name-servers-server-name-actions-reset-slave-all) | Reset all slaves on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/skip-replication-event | [get API clusters cluster name servers server name actions skip replication event](#get-api-clusters-cluster-name-servers-server-name-actions-skip-replication-event) | Skip a replication event on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/start-slave | [get API clusters cluster name servers server name actions start slave](#get-api-clusters-cluster-name-servers-server-name-actions-start-slave) | Start the slave on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/stop-slave | [get API clusters cluster name servers server name actions stop slave](#get-api-clusters-cluster-name-servers-server-name-actions-stop-slave) | Stop the slave on a server |
  


### 1.28  database_schema

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/servers/{serverName}/schemas | [get API clusters cluster name servers server name schemas](#get-api-clusters-cluster-name-servers-server-name-schemas) | Get schemas of a server |
  


### 1.29  database_tasks

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/job-cancel/{task} | [get API clusters cluster name servers server name actions job cancel task](#get-api-clusters-cluster-name-servers-server-name-actions-job-cancel-task) | Cancel a task on a server |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/run-jobs | [get API clusters cluster name servers server name actions run jobs](#get-api-clusters-cluster-name-servers-server-name-actions-run-jobs) | Run jobs on a server |
| POST | /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/write-log/{task} | [post API clusters cluster name servers server name server port write log task](#post-api-clusters-cluster-name-servers-server-name-server-port-write-log-task) | Write logs for a server |
  


### 1.30  database_topology

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/set-ignored | [get API clusters cluster name servers server name actions set ignored](#get-api-clusters-cluster-name-servers-server-name-actions-set-ignored) | Set a server as ignored |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/set-prefered | [get API clusters cluster name servers server name actions set prefered](#get-api-clusters-cluster-name-servers-server-name-actions-set-prefered) | Set a server as preferred |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/set-unrated | [get API clusters cluster name servers server name actions set unrated](#get-api-clusters-cluster-name-servers-server-name-actions-set-unrated) | Set a server as unrated |
| GET | /api/clusters/{clusterName}/servers/{serverName}/actions/switchover | [get API clusters cluster name servers server name actions switchover](#get-api-clusters-cluster-name-servers-server-name-actions-switchover) | Perform a switchover on a server |
  


### 1.31  global_setting

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| POST | /api/clusters/settings/actions/switch/{settingName} | [post API clusters settings actions switch setting name](#post-api-clusters-settings-actions-switch-setting-name) | Switch global settings for the server |
  


### 1.32  proxies

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/clusters/{clusterName}/proxies/{proxyName}/actions/need-reprov | [get API clusters cluster name proxies proxy name actions need reprov](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov) | Check if Proxy Needs Reprovision |
| GET | /api/clusters/{clusterName}/proxies/{proxyName}/actions/need-restart | [get API clusters cluster name proxies proxy name actions need restart](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart) | Check if Proxy Needs Restart |
| GET | /api/clusters/{clusterName}/sphinx/indexes | [get API clusters cluster name sphinx indexes](#get-api-clusters-cluster-name-sphinx-indexes) | Get Sphinx Indexes |
| POST | /api/clusters/{clusterName}/proxies/{proxyName}/actions/provision | [post API clusters cluster name proxies proxy name actions provision](#post-api-clusters-cluster-name-proxies-proxy-name-actions-provision) | Provision Proxy Service |
| POST | /api/clusters/{clusterName}/proxies/{proxyName}/actions/start | [post API clusters cluster name proxies proxy name actions start](#post-api-clusters-cluster-name-proxies-proxy-name-actions-start) | Start Proxy Service |
| POST | /api/clusters/{clusterName}/proxies/{proxyName}/actions/stop | [post API clusters cluster name proxies proxy name actions stop](#post-api-clusters-cluster-name-proxies-proxy-name-actions-stop) | Stop Proxy Service |
| POST | /api/clusters/{clusterName}/proxies/{proxyName}/actions/unprovision | [post API clusters cluster name proxies proxy name actions unprovision](#post-api-clusters-cluster-name-proxies-proxy-name-actions-unprovision) | Unprovision Proxy Service |
  


### 1.33  public

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| GET | /api/configs/grafana | [get API configs grafana](#get-api-configs-grafana) | List Grafana files |
| GET | /api/heartbeat | [get API heartbeat](#get-api-heartbeat) | Monitor Heartbeat |
| GET | /api/monitor | [get API monitor](#get-api-monitor) | Handles replication manager requests |
| GET | /api/prometheus | [get API prometheus](#get-api-prometheus) | Fetch Prometheus metrics |
| GET | /api/repocomp/current | [get API repocomp current](#get-api-repocomp-current) | Retrieve current repository component |
| GET | /api/status | [get API status](#get-api-status) | Get Replication Manager Status |
| GET | /api/timeout | [get API timeout](#get-api-timeout) | Check if the replication manager is running |
  


### 1.34  user

| Method  | URI     | Name   | Summary |
|---------|---------|--------|---------|
| POST | /api/clusters/{clusterName}/users/add | [post API clusters cluster name users add](#post-api-clusters-cluster-name-users-add) | Add a new user to a cluster |
| POST | /api/clusters/{clusterName}/users/drop | [post API clusters cluster name users drop](#post-api-clusters-cluster-name-users-drop) | Drop a cluster user |
| POST | /api/clusters/{clusterName}/users/send-credentials | [post API clusters cluster name users send credentials](#post-api-clusters-cluster-name-users-send-credentials) | Send credentials to a specific user |
| POST | /api/clusters/{clusterName}/users/update | [post API clusters cluster name users update](#post-api-clusters-cluster-name-users-update) | Update a cluster user |
  


## 2. Paths

### 2.1 <span id="delete-api-clusters-actions-delete-cluster-name"></span> Delete a cluster (*DeleteAPIClustersActionsDeleteClusterName*)

```
DELETE /api/clusters/actions/delete/{clusterName}
```

Deletes a cluster identified by its name.

#### 2.1.1 Produces
  * application/json

#### 2.1.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.1.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#delete-api-clusters-actions-delete-cluster-name-200) | OK | Cluster deleted successfully |  | [schema](#delete-api-clusters-actions-delete-cluster-name-200-schema) |
| [400](#delete-api-clusters-actions-delete-cluster-name-400) | Bad Request | Invalid cluster name |  | [schema](#delete-api-clusters-actions-delete-cluster-name-400-schema) |
| [500](#delete-api-clusters-actions-delete-cluster-name-500) | Internal Server Error | Internal server error |  | [schema](#delete-api-clusters-actions-delete-cluster-name-500-schema) |

#### 2.1.4 Responses


##### <span id="delete-api-clusters-actions-delete-cluster-name-200"></span> 200 - Cluster deleted successfully
Status: OK

###### <span id="delete-api-clusters-actions-delete-cluster-name-200-schema"></span> Schema
   
  



##### <span id="delete-api-clusters-actions-delete-cluster-name-400"></span> 400 - Invalid cluster name
Status: Bad Request

###### <span id="delete-api-clusters-actions-delete-cluster-name-400-schema"></span> Schema
   
  



##### <span id="delete-api-clusters-actions-delete-cluster-name-500"></span> 500 - Internal server error
Status: Internal Server Error

###### <span id="delete-api-clusters-actions-delete-cluster-name-500-schema"></span> Schema
   
  



### 2.2 <span id="get-api-clusters"></span> Fetch clusters (*GetAPIClusters*)

```
GET /api/clusters
```

Fetches the list of clusters that the user has access to based on ACL.

#### 2.2.1 Produces
  * application/json

#### 2.2.2 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-200) | OK | List of clusters |  | [schema](#get-api-clusters-200-schema) |
| [401](#get-api-clusters-401) | Unauthorized | Unauthenticated resource |  | [schema](#get-api-clusters-401-schema) |
| [500](#get-api-clusters-500) | Internal Server Error | Internal server error |  | [schema](#get-api-clusters-500-schema) |

#### 2.2.3 Responses


##### <span id="get-api-clusters-200"></span> 200 - List of clusters
Status: OK

###### <span id="get-api-clusters-200-schema"></span> Schema
   
  

[][ClusterCluster](#cluster-cluster)

##### <span id="get-api-clusters-401"></span> 401 - Unauthenticated resource
Status: Unauthorized

###### <span id="get-api-clusters-401-schema"></span> Schema
   
  



##### <span id="get-api-clusters-500"></span> 500 - Internal server error
Status: Internal Server Error

###### <span id="get-api-clusters-500-schema"></span> Schema
   
  



### 2.3 <span id="get-api-clusters-cluster-name"></span> Retrieve details of a cluster (*GetAPIClustersClusterName*)

```
GET /api/clusters/{clusterName}
```

This endpoint retrieves the details of a specified cluster and returns it in JSON format.

#### 2.3.1 Produces
  * application/json

#### 2.3.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.3.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-200) | OK | Cluster details |  | [schema](#get-api-clusters-cluster-name-200-schema) |
| [403](#get-api-clusters-cluster-name-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-403-schema) |
| [500](#get-api-clusters-cluster-name-500) | Internal Server Error | No cluster |  | [schema](#get-api-clusters-cluster-name-500-schema) |

#### 2.3.4 Responses


##### <span id="get-api-clusters-cluster-name-200"></span> 200 - Cluster details
Status: OK

###### <span id="get-api-clusters-cluster-name-200-schema"></span> Schema
   
  

[ClusterCluster](#cluster-cluster)

##### <span id="get-api-clusters-cluster-name-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-500-schema"></span> Schema
   
  



### 2.4 <span id="get-api-clusters-cluster-name-backups"></span> Retrieve backups for a specific cluster (*GetAPIClustersClusterNameBackups*)

```
GET /api/clusters/{clusterName}/backups
```

This endpoint retrieves the backups for the specified cluster.

#### 2.4.1 Produces
  * application/json

#### 2.4.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.4.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-backups-200) | OK | List of backups |  | [schema](#get-api-clusters-cluster-name-backups-200-schema) |
| [403](#get-api-clusters-cluster-name-backups-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-backups-403-schema) |
| [500](#get-api-clusters-cluster-name-backups-500) | Internal Server Error | No cluster |  | [schema](#get-api-clusters-cluster-name-backups-500-schema) |

#### 2.4.4 Responses


##### <span id="get-api-clusters-cluster-name-backups-200"></span> 200 - List of backups
Status: OK

###### <span id="get-api-clusters-cluster-name-backups-200-schema"></span> Schema
   
  

[][interface{}](#interface)

##### <span id="get-api-clusters-cluster-name-backups-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-backups-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-backups-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-backups-500-schema"></span> Schema
   
  



### 2.5 <span id="get-api-clusters-cluster-name-certificates"></span> Retrieve client certificates for a specific cluster (*GetAPIClustersClusterNameCertificates*)

```
GET /api/clusters/{clusterName}/certificates
```

This endpoint retrieves the client certificates for the specified cluster.

#### 2.5.1 Produces
  * application/json

#### 2.5.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.5.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-certificates-200) | OK | List of client certificates |  | [schema](#get-api-clusters-cluster-name-certificates-200-schema) |
| [500](#get-api-clusters-cluster-name-certificates-500) | Internal Server Error | Internal Server Error |  | [schema](#get-api-clusters-cluster-name-certificates-500-schema) |

#### 2.5.4 Responses


##### <span id="get-api-clusters-cluster-name-certificates-200"></span> 200 - List of client certificates
Status: OK

###### <span id="get-api-clusters-cluster-name-certificates-200-schema"></span> Schema
   
  

[][interface{}](#interface)

##### <span id="get-api-clusters-cluster-name-certificates-500"></span> 500 - Internal Server Error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-certificates-500-schema"></span> Schema
   
  



### 2.6 <span id="get-api-clusters-cluster-name-diffvariables"></span> Retrieve variable differences for a specific cluster (*GetAPIClustersClusterNameDiffvariables*)

```
GET /api/clusters/{clusterName}/diffvariables
```

This endpoint retrieves the variable differences for the specified cluster.

#### 2.6.1 Produces
  * application/json

#### 2.6.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.6.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-diffvariables-200) | OK | List of variable differences |  | [schema](#get-api-clusters-cluster-name-diffvariables-200-schema) |
| [403](#get-api-clusters-cluster-name-diffvariables-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-diffvariables-403-schema) |
| [500](#get-api-clusters-cluster-name-diffvariables-500) | Internal Server Error | No cluster |  | [schema](#get-api-clusters-cluster-name-diffvariables-500-schema) |

#### 2.6.4 Responses


##### <span id="get-api-clusters-cluster-name-diffvariables-200"></span> 200 - List of variable differences
Status: OK

###### <span id="get-api-clusters-cluster-name-diffvariables-200-schema"></span> Schema
   
  

[][ClusterVariableDiff](#cluster-variable-diff)

##### <span id="get-api-clusters-cluster-name-diffvariables-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-diffvariables-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-diffvariables-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-diffvariables-500-schema"></span> Schema
   
  



### 2.7 <span id="get-api-clusters-cluster-name-graphite-filterlist"></span> Retrieve Graphite filter list for a specific cluster (*GetAPIClustersClusterNameGraphiteFilterlist*)

```
GET /api/clusters/{clusterName}/graphite-filterlist
```

This endpoint retrieves the Graphite filter list for the specified cluster.

#### 2.7.1 Produces
  * application/json

#### 2.7.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.7.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-graphite-filterlist-200) | OK | List of Graphite filters |  | [schema](#get-api-clusters-cluster-name-graphite-filterlist-200-schema) |
| [500](#get-api-clusters-cluster-name-graphite-filterlist-500) | Internal Server Error | Internal Server Error |  | [schema](#get-api-clusters-cluster-name-graphite-filterlist-500-schema) |

#### 2.7.4 Responses


##### <span id="get-api-clusters-cluster-name-graphite-filterlist-200"></span> 200 - List of Graphite filters
Status: OK

###### <span id="get-api-clusters-cluster-name-graphite-filterlist-200-schema"></span> Schema
   
  

[]string

##### <span id="get-api-clusters-cluster-name-graphite-filterlist-500"></span> 500 - Internal Server Error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-graphite-filterlist-500-schema"></span> Schema
   
  



### 2.8 <span id="get-api-clusters-cluster-name-jobs"></span> Retrieve job entries for a specific cluster (*GetAPIClustersClusterNameJobs*)

```
GET /api/clusters/{clusterName}/jobs
```

This endpoint retrieves the job entries for the specified cluster.

#### 2.8.1 Produces
  * application/json

#### 2.8.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.8.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-jobs-200) | OK | List of job entries |  | [schema](#get-api-clusters-cluster-name-jobs-200-schema) |
| [403](#get-api-clusters-cluster-name-jobs-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-jobs-403-schema) |
| [500](#get-api-clusters-cluster-name-jobs-500) | Internal Server Error | Cluster Not Found |  | [schema](#get-api-clusters-cluster-name-jobs-500-schema) |

#### 2.8.4 Responses


##### <span id="get-api-clusters-cluster-name-jobs-200"></span> 200 - List of job entries
Status: OK

###### <span id="get-api-clusters-cluster-name-jobs-200-schema"></span> Schema
   
  

[][interface{}](#interface)

##### <span id="get-api-clusters-cluster-name-jobs-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-jobs-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-jobs-500"></span> 500 - Cluster Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-jobs-500-schema"></span> Schema
   
  



### 2.9 <span id="get-api-clusters-cluster-name-need-rolling-reprov"></span> Check if a cluster needs a rolling reprovision (*GetAPIClustersClusterNameNeedRollingReprov*)

```
GET /api/clusters/{clusterName}/need-rolling-reprov
```

Checks if a specified cluster needs a rolling reprovision.

#### 2.9.1 Produces
  * text/plain

#### 2.9.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.9.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-need-rolling-reprov-200) | OK | 200 -Need rolling reprov! |  | [schema](#get-api-clusters-cluster-name-need-rolling-reprov-200-schema) |
| [500](#get-api-clusters-cluster-name-need-rolling-reprov-500) | Internal Server Error | 503 -No rolling reprov needed!" or "500 -No cluster |  | [schema](#get-api-clusters-cluster-name-need-rolling-reprov-500-schema) |

#### 2.9.4 Responses


##### <span id="get-api-clusters-cluster-name-need-rolling-reprov-200"></span> 200 - 200 -Need rolling reprov!
Status: OK

###### <span id="get-api-clusters-cluster-name-need-rolling-reprov-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-need-rolling-reprov-500"></span> 500 - 503 -No rolling reprov needed!" or "500 -No cluster
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-need-rolling-reprov-500-schema"></span> Schema
   
  



### 2.10 <span id="get-api-clusters-cluster-name-need-rolling-restart"></span> Check if a cluster needs a rolling restart (*GetAPIClustersClusterNameNeedRollingRestart*)

```
GET /api/clusters/{clusterName}/need-rolling-restart
```

Checks if a specified cluster needs a rolling restart.

#### 2.10.1 Produces
  * text/plain

#### 2.10.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.10.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-need-rolling-restart-200) | OK | 200 -Need rolling restart! |  | [schema](#get-api-clusters-cluster-name-need-rolling-restart-200-schema) |
| [500](#get-api-clusters-cluster-name-need-rolling-restart-500) | Internal Server Error | 503 -No rolling restart needed!" or "500 -No cluster |  | [schema](#get-api-clusters-cluster-name-need-rolling-restart-500-schema) |

#### 2.10.4 Responses


##### <span id="get-api-clusters-cluster-name-need-rolling-restart-200"></span> 200 - 200 -Need rolling restart!
Status: OK

###### <span id="get-api-clusters-cluster-name-need-rolling-restart-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-need-rolling-restart-500"></span> 500 - 503 -No rolling restart needed!" or "500 -No cluster
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-need-rolling-restart-500-schema"></span> Schema
   
  



### 2.11 <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov"></span> Check if Proxy Needs Reprovision (*GetAPIClustersClusterNameProxiesProxyNameActionsNeedReprov*)

```
GET /api/clusters/{clusterName}/proxies/{proxyName}/actions/need-reprov
```

Check if the proxy service for a given cluster and proxy needs reprovisioning

#### 2.11.1 Consumes
  * application/json

#### 2.11.2 Produces
  * application/json

#### 2.11.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| proxyName | `path` | string | `string` |  | ✓ |  | Proxy Name |

#### 2.11.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov-200) | OK | Need reprov! |  | [schema](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov-200-schema) |
| [403](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov-403-schema) |
| [500](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov-500) | Internal Server Error | No cluster |  | [schema](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov-500-schema) |
| [503](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov-503) | Service Unavailable | No reprov needed!" "Not a Valid Server! |  | [schema](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov-503-schema) |

#### 2.11.5 Responses


##### <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov-200"></span> 200 - Need reprov!
Status: OK

###### <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov-500-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov-503"></span> 503 - No reprov needed!" "Not a Valid Server!
Status: Service Unavailable

###### <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-reprov-503-schema"></span> Schema
   
  



### 2.12 <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart"></span> Check if Proxy Needs Restart (*GetAPIClustersClusterNameProxiesProxyNameActionsNeedRestart*)

```
GET /api/clusters/{clusterName}/proxies/{proxyName}/actions/need-restart
```

Check if the proxy service for a given cluster and proxy needs a restart

#### 2.12.1 Consumes
  * application/json

#### 2.12.2 Produces
  * application/json

#### 2.12.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| proxyName | `path` | string | `string` |  | ✓ |  | Proxy Name |

#### 2.12.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart-200) | OK | Need restart! |  | [schema](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart-200-schema) |
| [403](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart-403-schema) |
| [500](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart-500) | Internal Server Error | No cluster |  | [schema](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart-500-schema) |
| [503](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart-503) | Service Unavailable | No restart needed!" "Not a Valid Server! |  | [schema](#get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart-503-schema) |

#### 2.12.5 Responses


##### <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart-200"></span> 200 - Need restart!
Status: OK

###### <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart-500-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart-503"></span> 503 - No restart needed!" "Not a Valid Server!
Status: Service Unavailable

###### <span id="get-api-clusters-cluster-name-proxies-proxy-name-actions-need-restart-503-schema"></span> Schema
   
  



### 2.13 <span id="get-api-clusters-cluster-name-queryrules"></span> Retrieve query rules for a specific cluster (*GetAPIClustersClusterNameQueryrules*)

```
GET /api/clusters/{clusterName}/queryrules
```

This endpoint retrieves the query rules for the specified cluster.

#### 2.13.1 Produces
  * application/json

#### 2.13.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.13.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-queryrules-200) | OK | List of query rules |  | [schema](#get-api-clusters-cluster-name-queryrules-200-schema) |
| [403](#get-api-clusters-cluster-name-queryrules-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-queryrules-403-schema) |
| [500](#get-api-clusters-cluster-name-queryrules-500) | Internal Server Error | No cluster |  | [schema](#get-api-clusters-cluster-name-queryrules-500-schema) |

#### 2.13.4 Responses


##### <span id="get-api-clusters-cluster-name-queryrules-200"></span> 200 - List of query rules
Status: OK

###### <span id="get-api-clusters-cluster-name-queryrules-200-schema"></span> Schema
   
  

[][interface{}](#interface)

##### <span id="get-api-clusters-cluster-name-queryrules-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-queryrules-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-queryrules-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-queryrules-500-schema"></span> Schema
   
  



### 2.14 <span id="get-api-clusters-cluster-name-schema"></span> Retrieve schema information for a specific cluster (*GetAPIClustersClusterNameSchema*)

```
GET /api/clusters/{clusterName}/schema
```

This endpoint retrieves the schema information for the specified cluster.

#### 2.14.1 Produces
  * application/json

#### 2.14.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.14.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-schema-200) | OK | Schema information |  | [schema](#get-api-clusters-cluster-name-schema-200-schema) |
| [403](#get-api-clusters-cluster-name-schema-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-schema-403-schema) |
| [500](#get-api-clusters-cluster-name-schema-500) | Internal Server Error | No cluster |  | [schema](#get-api-clusters-cluster-name-schema-500-schema) |

#### 2.14.4 Responses


##### <span id="get-api-clusters-cluster-name-schema-200"></span> 200 - Schema information
Status: OK

###### <span id="get-api-clusters-cluster-name-schema-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-schema-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-schema-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-schema-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-schema-500-schema"></span> Schema
   
  



### 2.15 <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-error-log"></span> Perform a backup of the error log on a server (*GetAPIClustersClusterNameServersServerNameActionsBackupErrorLog*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/backup-error-log
```

Initiates a backup of the error log on a specified server within a cluster.

#### 2.15.1 Produces
  * application/json

#### 2.15.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.15.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-backup-error-log-200) | OK | Backup initiated successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-backup-error-log-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-backup-error-log-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-backup-error-log-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-backup-error-log-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-backup-error-log-500-schema) |

#### 2.15.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-error-log-200"></span> 200 - Backup initiated successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-error-log-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-error-log-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-error-log-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-error-log-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-error-log-500-schema"></span> Schema
   
  



### 2.16 <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-logical"></span> Perform a logical backup on a server (*GetAPIClustersClusterNameServersServerNameActionsBackupLogical*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/backup-logical
```

Initiates a logical backup on a specified server within a cluster.

#### 2.16.1 Produces
  * application/json

#### 2.16.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.16.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-backup-logical-200) | OK | Backup initiated successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-backup-logical-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-backup-logical-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-backup-logical-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-backup-logical-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-backup-logical-500-schema) |

#### 2.16.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-logical-200"></span> 200 - Backup initiated successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-logical-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-logical-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-logical-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-logical-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-logical-500-schema"></span> Schema
   
  



### 2.17 <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-physical"></span> Perform a physical backup on a server (*GetAPIClustersClusterNameServersServerNameActionsBackupPhysical*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/backup-physical
```

Initiates a physical backup on a specified server within a cluster.

#### 2.17.1 Produces
  * application/json

#### 2.17.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.17.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-backup-physical-200) | OK | Backup initiated successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-backup-physical-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-backup-physical-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-backup-physical-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-backup-physical-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-backup-physical-500-schema) |

#### 2.17.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-physical-200"></span> 200 - Backup initiated successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-physical-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-physical-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-physical-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-physical-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-physical-500-schema"></span> Schema
   
  



### 2.18 <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-slowquery-log"></span> Perform a backup of the slow query log on a server (*GetAPIClustersClusterNameServersServerNameActionsBackupSlowqueryLog*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/backup-slowquery-log
```

Initiates a backup of the slow query log on a specified server within a cluster.

#### 2.18.1 Produces
  * application/json

#### 2.18.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.18.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-backup-slowquery-log-200) | OK | Backup initiated successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-backup-slowquery-log-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-backup-slowquery-log-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-backup-slowquery-log-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-backup-slowquery-log-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-backup-slowquery-log-500-schema) |

#### 2.18.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-slowquery-log-200"></span> 200 - Backup initiated successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-slowquery-log-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-slowquery-log-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-slowquery-log-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-slowquery-log-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-backup-slowquery-log-500-schema"></span> Schema
   
  



### 2.19 <span id="get-api-clusters-cluster-name-servers-server-name-actions-del-maintenance"></span> Delete maintenance mode on a server (*GetAPIClustersClusterNameServersServerNameActionsDelMaintenance*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/del-maintenance
```

Deletes the maintenance mode on a specified server within a cluster.

#### 2.19.1 Produces
  * application/json

#### 2.19.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.19.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-del-maintenance-200) | OK | Maintenance mode deleted successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-del-maintenance-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-del-maintenance-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-del-maintenance-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-del-maintenance-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-del-maintenance-500-schema) |

#### 2.19.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-del-maintenance-200"></span> 200 - Maintenance mode deleted successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-del-maintenance-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-del-maintenance-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-del-maintenance-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-del-maintenance-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-del-maintenance-500-schema"></span> Schema
   
  



### 2.20 <span id="get-api-clusters-cluster-name-servers-server-name-actions-flush-logs"></span> Flush logs on a server (*GetAPIClustersClusterNameServersServerNameActionsFlushLogs*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/flush-logs
```

Flushes the logs on a specified server within a cluster.

#### 2.20.1 Produces
  * application/json

#### 2.20.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.20.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-flush-logs-200) | OK | Logs flushed successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-flush-logs-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-flush-logs-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-flush-logs-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-flush-logs-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-flush-logs-500-schema) |

#### 2.20.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-flush-logs-200"></span> 200 - Logs flushed successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-flush-logs-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-flush-logs-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-flush-logs-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-flush-logs-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-flush-logs-500-schema"></span> Schema
   
  



### 2.21 <span id="get-api-clusters-cluster-name-servers-server-name-actions-job-cancel-task"></span> Cancel a task on a server (*GetAPIClustersClusterNameServersServerNameActionsJobCancelTask*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/job-cancel/{task}
```

Cancels a task identified by its name on a specified server within a cluster.

#### 2.21.1 Produces
  * application/json

#### 2.21.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |
| task | `path` | string | `string` |  | ✓ |  | Task Name |

#### 2.21.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-job-cancel-task-200) | OK | Task canceled successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-job-cancel-task-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-job-cancel-task-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-job-cancel-task-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-job-cancel-task-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Error canceling task |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-job-cancel-task-500-schema) |

#### 2.21.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-job-cancel-task-200"></span> 200 - Task canceled successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-job-cancel-task-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-job-cancel-task-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-job-cancel-task-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-job-cancel-task-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Error canceling task
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-job-cancel-task-500-schema"></span> Schema
   
  



### 2.22 <span id="get-api-clusters-cluster-name-servers-server-name-actions-maintenance"></span> Toggle maintenance mode on a server (*GetAPIClustersClusterNameServersServerNameActionsMaintenance*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/maintenance
```

Toggles the maintenance mode on a specified server within a cluster.

#### 2.22.1 Produces
  * application/json

#### 2.22.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.22.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-maintenance-200) | OK | Maintenance mode toggled successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-maintenance-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-maintenance-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-maintenance-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-maintenance-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-maintenance-500-schema) |

#### 2.22.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-maintenance-200"></span> 200 - Maintenance mode toggled successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-maintenance-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-maintenance-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-maintenance-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-maintenance-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-maintenance-500-schema"></span> Schema
   
  



### 2.23 <span id="get-api-clusters-cluster-name-servers-server-name-actions-optimize"></span> Optimize a server (*GetAPIClustersClusterNameServersServerNameActionsOptimize*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/optimize
```

Optimizes a specified server within a cluster.

#### 2.23.1 Produces
  * application/json

#### 2.23.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.23.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-optimize-200) | OK | Server optimized successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-optimize-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-optimize-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-optimize-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-optimize-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-optimize-500-schema) |

#### 2.23.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-optimize-200"></span> 200 - Server optimized successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-optimize-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-optimize-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-optimize-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-optimize-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-optimize-500-schema"></span> Schema
   
  



### 2.24 <span id="get-api-clusters-cluster-name-servers-server-name-actions-provision"></span> Provision a server (*GetAPIClustersClusterNameServersServerNameActionsProvision*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/provision
```

Provisions a specified server within a cluster.

#### 2.24.1 Produces
  * application/json

#### 2.24.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.24.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-provision-200) | OK | Server provisioned successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-provision-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-provision-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-provision-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-provision-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-provision-500-schema) |

#### 2.24.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-provision-200"></span> 200 - Server provisioned successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-provision-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-provision-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-provision-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-provision-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-provision-500-schema"></span> Schema
   
  



### 2.25 <span id="get-api-clusters-cluster-name-servers-server-name-actions-reseed-backup-method"></span> Reseed a server (*GetAPIClustersClusterNameServersServerNameActionsReseedBackupMethod*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/reseed/{backupMethod}
```

Reseeds a specified server within a cluster using the specified backup method.

#### 2.25.1 Produces
  * application/json

#### 2.25.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| backupMethod | `path` | string | `string` |  | ✓ |  | Backup Method |
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.25.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-reseed-backup-method-200) | OK | Reseed initiated successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-reseed-backup-method-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-reseed-backup-method-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-reseed-backup-method-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-reseed-backup-method-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Error reseed logical backup" or "Error reseed physical backup |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-reseed-backup-method-500-schema) |

#### 2.25.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reseed-backup-method-200"></span> 200 - Reseed initiated successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reseed-backup-method-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reseed-backup-method-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reseed-backup-method-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reseed-backup-method-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Error reseed logical backup" or "Error reseed physical backup
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reseed-backup-method-500-schema"></span> Schema
   
  



### 2.26 <span id="get-api-clusters-cluster-name-servers-server-name-actions-reseed-cancel-task"></span> Cancel a reseed task on a server (*GetAPIClustersClusterNameServersServerNameActionsReseedCancelTask*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/reseed-cancel/{task}
```

Cancels a reseed task identified by its name on a specified server within a cluster.

#### 2.26.1 Produces
  * application/json

#### 2.26.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |
| task | `path` | string | `string` |  | ✓ |  | Task Name |

#### 2.26.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-reseed-cancel-task-200) | OK | Task canceled successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-reseed-cancel-task-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-reseed-cancel-task-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-reseed-cancel-task-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-reseed-cancel-task-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Error canceling task |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-reseed-cancel-task-500-schema) |

#### 2.26.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reseed-cancel-task-200"></span> 200 - Task canceled successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reseed-cancel-task-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reseed-cancel-task-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reseed-cancel-task-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reseed-cancel-task-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Error canceling task
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reseed-cancel-task-500-schema"></span> Schema
   
  



### 2.27 <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-master"></span> Reset the master on a server (*GetAPIClustersClusterNameServersServerNameActionsResetMaster*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/reset-master
```

Resets the master on a specified server within a cluster.

#### 2.27.1 Produces
  * application/json

#### 2.27.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.27.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-reset-master-200) | OK | Master reset successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-reset-master-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-reset-master-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-reset-master-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-reset-master-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-reset-master-500-schema) |

#### 2.27.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-master-200"></span> 200 - Master reset successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-master-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-master-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-master-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-master-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-master-500-schema"></span> Schema
   
  



### 2.28 <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-pfs-queries"></span> Reset PFS queries on a server (*GetAPIClustersClusterNameServersServerNameActionsResetPfsQueries*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/reset-pfs-queries
```

Resets PFS queries on a specified server within a cluster.

#### 2.28.1 Produces
  * application/json

#### 2.28.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.28.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-reset-pfs-queries-200) | OK | PFS queries reset successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-reset-pfs-queries-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-reset-pfs-queries-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-reset-pfs-queries-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-reset-pfs-queries-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-reset-pfs-queries-500-schema) |

#### 2.28.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-pfs-queries-200"></span> 200 - PFS queries reset successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-pfs-queries-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-pfs-queries-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-pfs-queries-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-pfs-queries-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-pfs-queries-500-schema"></span> Schema
   
  



### 2.29 <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-slave-all"></span> Reset all slaves on a server (*GetAPIClustersClusterNameServersServerNameActionsResetSlaveAll*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/reset-slave-all
```

Resets all slaves on a specified server within a cluster.

#### 2.29.1 Produces
  * application/json

#### 2.29.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.29.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-reset-slave-all-200) | OK | Slaves reset successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-reset-slave-all-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-reset-slave-all-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-reset-slave-all-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-reset-slave-all-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-reset-slave-all-500-schema) |

#### 2.29.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-slave-all-200"></span> 200 - Slaves reset successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-slave-all-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-slave-all-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-slave-all-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-slave-all-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-reset-slave-all-500-schema"></span> Schema
   
  



### 2.30 <span id="get-api-clusters-cluster-name-servers-server-name-actions-run-jobs"></span> Run jobs on a server (*GetAPIClustersClusterNameServersServerNameActionsRunJobs*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/run-jobs
```

Runs jobs on a specified server within a cluster.

#### 2.30.1 Produces
  * application/json

#### 2.30.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.30.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-run-jobs-200) | OK | Jobs run successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-run-jobs-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-run-jobs-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-run-jobs-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-run-jobs-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Error running job |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-run-jobs-500-schema) |

#### 2.30.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-run-jobs-200"></span> 200 - Jobs run successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-run-jobs-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-run-jobs-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-run-jobs-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-run-jobs-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Error running job
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-run-jobs-500-schema"></span> Schema
   
  



### 2.31 <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-ignored"></span> Set a server as ignored (*GetAPIClustersClusterNameServersServerNameActionsSetIgnored*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/set-ignored
```

Sets a specified server within a cluster as ignored.

#### 2.31.1 Produces
  * application/json

#### 2.31.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.31.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-set-ignored-200) | OK | Server set as ignored successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-set-ignored-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-set-ignored-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-set-ignored-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-set-ignored-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-set-ignored-500-schema) |

#### 2.31.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-ignored-200"></span> 200 - Server set as ignored successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-ignored-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-ignored-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-ignored-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-ignored-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-ignored-500-schema"></span> Schema
   
  



### 2.32 <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-long-query-time-query-time"></span> Set long query time on a server (*GetAPIClustersClusterNameServersServerNameActionsSetLongQueryTimeQueryTime*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/set-long-query-time/{queryTime}
```

Sets the long query time on a specified server within a cluster.

#### 2.32.1 Produces
  * application/json

#### 2.32.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| queryTime | `path` | string | `string` |  | ✓ |  | Query Time |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.32.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-set-long-query-time-query-time-200) | OK | Long query time set successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-set-long-query-time-query-time-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-set-long-query-time-query-time-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-set-long-query-time-query-time-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-set-long-query-time-query-time-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-set-long-query-time-query-time-500-schema) |

#### 2.32.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-long-query-time-query-time-200"></span> 200 - Long query time set successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-long-query-time-query-time-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-long-query-time-query-time-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-long-query-time-query-time-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-long-query-time-query-time-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-long-query-time-query-time-500-schema"></span> Schema
   
  



### 2.33 <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-maintenance"></span> Set a server to maintenance mode (*GetAPIClustersClusterNameServersServerNameActionsSetMaintenance*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/set-maintenance
```

Sets a specified server within a cluster to maintenance mode.

#### 2.33.1 Produces
  * application/json

#### 2.33.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.33.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-set-maintenance-200) | OK | Server set to maintenance mode successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-set-maintenance-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-set-maintenance-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-set-maintenance-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-set-maintenance-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-set-maintenance-500-schema) |

#### 2.33.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-maintenance-200"></span> 200 - Server set to maintenance mode successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-maintenance-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-maintenance-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-maintenance-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-maintenance-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-maintenance-500-schema"></span> Schema
   
  



### 2.34 <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-prefered"></span> Set a server as preferred (*GetAPIClustersClusterNameServersServerNameActionsSetPrefered*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/set-prefered
```

Sets a specified server within a cluster as preferred.

#### 2.34.1 Produces
  * application/json

#### 2.34.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.34.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-set-prefered-200) | OK | Server set as preferred successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-set-prefered-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-set-prefered-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-set-prefered-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-set-prefered-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-set-prefered-500-schema) |

#### 2.34.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-prefered-200"></span> 200 - Server set as preferred successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-prefered-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-prefered-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-prefered-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-prefered-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-prefered-500-schema"></span> Schema
   
  



### 2.35 <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-unrated"></span> Set a server as unrated (*GetAPIClustersClusterNameServersServerNameActionsSetUnrated*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/set-unrated
```

Sets a specified server within a cluster as unrated.

#### 2.35.1 Produces
  * application/json

#### 2.35.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.35.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-set-unrated-200) | OK | Server set as unrated successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-set-unrated-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-set-unrated-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-set-unrated-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-set-unrated-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-set-unrated-500-schema) |

#### 2.35.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-unrated-200"></span> 200 - Server set as unrated successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-unrated-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-unrated-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-unrated-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-unrated-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-set-unrated-500-schema"></span> Schema
   
  



### 2.36 <span id="get-api-clusters-cluster-name-servers-server-name-actions-skip-replication-event"></span> Skip a replication event on a server (*GetAPIClustersClusterNameServersServerNameActionsSkipReplicationEvent*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/skip-replication-event
```

Skips a replication event on a specified server within a cluster.

#### 2.36.1 Produces
  * application/json

#### 2.36.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.36.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-skip-replication-event-200) | OK | Replication event skipped successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-skip-replication-event-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-skip-replication-event-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-skip-replication-event-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-skip-replication-event-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-skip-replication-event-500-schema) |

#### 2.36.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-skip-replication-event-200"></span> 200 - Replication event skipped successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-skip-replication-event-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-skip-replication-event-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-skip-replication-event-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-skip-replication-event-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-skip-replication-event-500-schema"></span> Schema
   
  



### 2.37 <span id="get-api-clusters-cluster-name-servers-server-name-actions-start"></span> Start a server (*GetAPIClustersClusterNameServersServerNameActionsStart*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/start
```

Starts a specified server within a cluster.

#### 2.37.1 Produces
  * application/json

#### 2.37.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.37.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-start-200) | OK | Server started successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-start-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-start-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-start-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-start-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-start-500-schema) |

#### 2.37.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-start-200"></span> 200 - Server started successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-start-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-start-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-start-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-start-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-start-500-schema"></span> Schema
   
  



### 2.38 <span id="get-api-clusters-cluster-name-servers-server-name-actions-start-slave"></span> Start the slave on a server (*GetAPIClustersClusterNameServersServerNameActionsStartSlave*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/start-slave
```

Starts the slave on a specified server within a cluster.

#### 2.38.1 Produces
  * application/json

#### 2.38.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.38.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-start-slave-200) | OK | Slave started successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-start-slave-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-start-slave-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-start-slave-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-start-slave-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-start-slave-500-schema) |

#### 2.38.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-start-slave-200"></span> 200 - Slave started successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-start-slave-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-start-slave-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-start-slave-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-start-slave-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-start-slave-500-schema"></span> Schema
   
  



### 2.39 <span id="get-api-clusters-cluster-name-servers-server-name-actions-stop"></span> Stop a server (*GetAPIClustersClusterNameServersServerNameActionsStop*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/stop
```

Stops a specified server within a cluster.

#### 2.39.1 Produces
  * application/json

#### 2.39.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.39.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-stop-200) | OK | Server stopped successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-stop-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-stop-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-stop-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-stop-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-stop-500-schema) |

#### 2.39.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-stop-200"></span> 200 - Server stopped successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-stop-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-stop-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-stop-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-stop-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-stop-500-schema"></span> Schema
   
  



### 2.40 <span id="get-api-clusters-cluster-name-servers-server-name-actions-stop-slave"></span> Stop the slave on a server (*GetAPIClustersClusterNameServersServerNameActionsStopSlave*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/stop-slave
```

Stops the slave on a specified server within a cluster.

#### 2.40.1 Produces
  * application/json

#### 2.40.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.40.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-stop-slave-200) | OK | Slave stopped successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-stop-slave-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-stop-slave-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-stop-slave-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-stop-slave-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-stop-slave-500-schema) |

#### 2.40.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-stop-slave-200"></span> 200 - Slave stopped successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-stop-slave-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-stop-slave-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-stop-slave-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-stop-slave-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-stop-slave-500-schema"></span> Schema
   
  



### 2.41 <span id="get-api-clusters-cluster-name-servers-server-name-actions-switchover"></span> Perform a switchover on a server (*GetAPIClustersClusterNameServersServerNameActionsSwitchover*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/switchover
```

Initiates a switchover on a specified server within a cluster.

#### 2.41.1 Produces
  * application/json

#### 2.41.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.41.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-switchover-200) | OK | Switchover initiated successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-switchover-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-switchover-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-switchover-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-switchover-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Master failed, cannot initiate switchover |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-switchover-500-schema) |

#### 2.41.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-switchover-200"></span> 200 - Switchover initiated successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-switchover-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-switchover-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-switchover-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-switchover-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Master failed, cannot initiate switchover
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-switchover-500-schema"></span> Schema
   
  



### 2.42 <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-innodb-monitor"></span> Toggle InnoDB monitor on a server (*GetAPIClustersClusterNameServersServerNameActionsToogleInnodbMonitor*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-innodb-monitor
```

Toggles the InnoDB monitor on a specified server within a cluster.

#### 2.42.1 Produces
  * application/json

#### 2.42.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.42.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-innodb-monitor-200) | OK | InnoDB monitor toggled successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-innodb-monitor-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-innodb-monitor-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-innodb-monitor-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-innodb-monitor-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-innodb-monitor-500-schema) |

#### 2.42.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-innodb-monitor-200"></span> 200 - InnoDB monitor toggled successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-innodb-monitor-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-innodb-monitor-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-innodb-monitor-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-innodb-monitor-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-innodb-monitor-500-schema"></span> Schema
   
  



### 2.43 <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-meta-data-locks"></span> Toggle metadata locks on a server (*GetAPIClustersClusterNameServersServerNameActionsToogleMetaDataLocks*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-meta-data-locks
```

Toggles the metadata locks on a specified server within a cluster.

#### 2.43.1 Produces
  * application/json

#### 2.43.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.43.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-meta-data-locks-200) | OK | Metadata locks toggled successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-meta-data-locks-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-meta-data-locks-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-meta-data-locks-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-meta-data-locks-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-meta-data-locks-500-schema) |

#### 2.43.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-meta-data-locks-200"></span> 200 - Metadata locks toggled successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-meta-data-locks-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-meta-data-locks-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-meta-data-locks-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-meta-data-locks-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-meta-data-locks-500-schema"></span> Schema
   
  



### 2.44 <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-pfs-slow-query"></span> Toggle PFS slow query capture on a server (*GetAPIClustersClusterNameServersServerNameActionsTooglePfsSlowQuery*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-pfs-slow-query
```

Toggles the PFS slow query capture on a specified server within a cluster.

#### 2.44.1 Produces
  * application/json

#### 2.44.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.44.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-pfs-slow-query-200) | OK | PFS slow query capture toggled successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-pfs-slow-query-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-pfs-slow-query-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-pfs-slow-query-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-pfs-slow-query-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-pfs-slow-query-500-schema) |

#### 2.44.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-pfs-slow-query-200"></span> 200 - PFS slow query capture toggled successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-pfs-slow-query-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-pfs-slow-query-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-pfs-slow-query-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-pfs-slow-query-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-pfs-slow-query-500-schema"></span> Schema
   
  



### 2.45 <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-query-response-time"></span> Toggle query response time on a server (*GetAPIClustersClusterNameServersServerNameActionsToogleQueryResponseTime*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-query-response-time
```

Toggles the query response time on a specified server within a cluster.

#### 2.45.1 Produces
  * application/json

#### 2.45.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.45.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-query-response-time-200) | OK | Query response time toggled successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-query-response-time-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-query-response-time-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-query-response-time-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-query-response-time-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-query-response-time-500-schema) |

#### 2.45.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-query-response-time-200"></span> 200 - Query response time toggled successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-query-response-time-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-query-response-time-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-query-response-time-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-query-response-time-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-query-response-time-500-schema"></span> Schema
   
  



### 2.46 <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-read-only"></span> Toggle read-only mode on a server (*GetAPIClustersClusterNameServersServerNameActionsToogleReadOnly*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-read-only
```

Toggles the read-only mode on a specified server within a cluster.

#### 2.46.1 Produces
  * application/json

#### 2.46.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.46.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-read-only-200) | OK | Read-only mode toggled successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-read-only-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-read-only-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-read-only-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-read-only-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-read-only-500-schema) |

#### 2.46.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-read-only-200"></span> 200 - Read-only mode toggled successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-read-only-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-read-only-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-read-only-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-read-only-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-read-only-500-schema"></span> Schema
   
  



### 2.47 <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-sql-error-log"></span> Toggle SQL error log on a server (*GetAPIClustersClusterNameServersServerNameActionsToogleSQLErrorLog*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-sql-error-log
```

Toggles the SQL error log on a specified server within a cluster.

#### 2.47.1 Produces
  * application/json

#### 2.47.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.47.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-sql-error-log-200) | OK | SQL error log toggled successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-sql-error-log-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-sql-error-log-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-sql-error-log-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-sql-error-log-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-sql-error-log-500-schema) |

#### 2.47.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-sql-error-log-200"></span> 200 - SQL error log toggled successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-sql-error-log-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-sql-error-log-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-sql-error-log-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-sql-error-log-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-sql-error-log-500-schema"></span> Schema
   
  



### 2.48 <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query"></span> Toggle slow query on a server (*GetAPIClustersClusterNameServersServerNameActionsToogleSlowQuery*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-slow-query
```

Toggles the slow query on a specified server within a cluster.

#### 2.48.1 Produces
  * application/json

#### 2.48.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.48.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-200) | OK | Slow query toggled successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-500-schema) |

#### 2.48.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-200"></span> 200 - Slow query toggled successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-500-schema"></span> Schema
   
  



### 2.49 <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-capture"></span> Toggle slow query capture on a server (*GetAPIClustersClusterNameServersServerNameActionsToogleSlowQueryCapture*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-slow-query-capture
```

Toggles the slow query capture on a specified server within a cluster.

#### 2.49.1 Produces
  * application/json

#### 2.49.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.49.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-capture-200) | OK | Slow query capture toggled successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-capture-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-capture-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-capture-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-capture-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-capture-500-schema) |

#### 2.49.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-capture-200"></span> 200 - Slow query capture toggled successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-capture-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-capture-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-capture-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-capture-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-capture-500-schema"></span> Schema
   
  



### 2.50 <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-table"></span> Toggle slow query table mode on a server (*GetAPIClustersClusterNameServersServerNameActionsToogleSlowQueryTable*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/toogle-slow-query-table
```

Toggles the slow query table mode on a specified server within a cluster.

#### 2.50.1 Produces
  * application/json

#### 2.50.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.50.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-table-200) | OK | Slow query table mode toggled successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-table-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-table-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-table-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-table-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-table-500-schema) |

#### 2.50.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-table-200"></span> 200 - Slow query table mode toggled successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-table-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-table-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-table-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-table-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-toogle-slow-query-table-500-schema"></span> Schema
   
  



### 2.51 <span id="get-api-clusters-cluster-name-servers-server-name-actions-unprovision"></span> Unprovision a server (*GetAPIClustersClusterNameServersServerNameActionsUnprovision*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/unprovision
```

Unprovisions a specified server within a cluster.

#### 2.51.1 Produces
  * application/json

#### 2.51.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.51.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-unprovision-200) | OK | Server unprovisioned successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-unprovision-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-unprovision-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-unprovision-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-unprovision-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-unprovision-500-schema) |

#### 2.51.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-unprovision-200"></span> 200 - Server unprovisioned successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-unprovision-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-unprovision-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-unprovision-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-unprovision-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-unprovision-500-schema"></span> Schema
   
  



### 2.52 <span id="get-api-clusters-cluster-name-servers-server-name-actions-wait-innodb-purge"></span> Wait for InnoDB purge on a server (*GetAPIClustersClusterNameServersServerNameActionsWaitInnodbPurge*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/actions/wait-innodb-purge
```

Waits for InnoDB purge on a specified server within a cluster.

#### 2.52.1 Produces
  * application/json

#### 2.52.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.52.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-actions-wait-innodb-purge-200) | OK | InnoDB purge completed successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-wait-innodb-purge-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-actions-wait-innodb-purge-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-wait-innodb-purge-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-actions-wait-innodb-purge-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Error waiting for InnoDB purge |  | [schema](#get-api-clusters-cluster-name-servers-server-name-actions-wait-innodb-purge-500-schema) |

#### 2.52.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-wait-innodb-purge-200"></span> 200 - InnoDB purge completed successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-wait-innodb-purge-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-wait-innodb-purge-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-wait-innodb-purge-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-actions-wait-innodb-purge-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Error waiting for InnoDB purge
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-actions-wait-innodb-purge-500-schema"></span> Schema
   
  



### 2.53 <span id="get-api-clusters-cluster-name-servers-server-name-all-slaves-status"></span> Get status of all slaves of a server (*GetAPIClustersClusterNameServersServerNameAllSlavesStatus*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/all-slaves-status
```

Retrieves the status of all slaves of a specified server within a cluster.

#### 2.53.1 Produces
  * application/json

#### 2.53.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.53.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-all-slaves-status-200) | OK | Status of all slaves retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-all-slaves-status-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-all-slaves-status-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-all-slaves-status-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-all-slaves-status-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-all-slaves-status-500-schema) |

#### 2.53.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-all-slaves-status-200"></span> 200 - Status of all slaves retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-all-slaves-status-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-all-slaves-status-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-all-slaves-status-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-all-slaves-status-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-all-slaves-status-500-schema"></span> Schema
   
  



### 2.54 <span id="get-api-clusters-cluster-name-servers-server-name-digest-statements-pfs"></span> Get PFS statements of a server (*GetAPIClustersClusterNameServersServerNameDigestStatementsPfs*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/digest-statements-pfs
```

Retrieves the PFS statements of a specified server within a cluster.

#### 2.54.1 Produces
  * application/json

#### 2.54.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.54.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-digest-statements-pfs-200) | OK | PFS statements retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-digest-statements-pfs-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-digest-statements-pfs-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-digest-statements-pfs-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-digest-statements-pfs-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-digest-statements-pfs-500-schema) |

#### 2.54.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-digest-statements-pfs-200"></span> 200 - PFS statements retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-digest-statements-pfs-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-digest-statements-pfs-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-digest-statements-pfs-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-digest-statements-pfs-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-digest-statements-pfs-500-schema"></span> Schema
   
  



### 2.55 <span id="get-api-clusters-cluster-name-servers-server-name-digest-statements-slow"></span> Get PFS statements from the slow log of a server (*GetAPIClustersClusterNameServersServerNameDigestStatementsSlow*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/digest-statements-slow
```

Retrieves the PFS statements from the slow log of a specified server within a cluster.

#### 2.55.1 Produces
  * application/json

#### 2.55.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.55.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-digest-statements-slow-200) | OK | PFS statements from slow log retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-digest-statements-slow-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-digest-statements-slow-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-digest-statements-slow-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-digest-statements-slow-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-digest-statements-slow-500-schema) |

#### 2.55.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-digest-statements-slow-200"></span> 200 - PFS statements from slow log retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-digest-statements-slow-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-digest-statements-slow-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-digest-statements-slow-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-digest-statements-slow-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-digest-statements-slow-500-schema"></span> Schema
   
  



### 2.56 <span id="get-api-clusters-cluster-name-servers-server-name-errorlog"></span> Get error log of a server (*GetAPIClustersClusterNameServersServerNameErrorlog*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/errorlog
```

Retrieves the error log of a specified server within a cluster.

#### 2.56.1 Produces
  * application/json

#### 2.56.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.56.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-errorlog-200) | OK | Error log retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-errorlog-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-errorlog-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-errorlog-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-errorlog-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-errorlog-500-schema) |

#### 2.56.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-errorlog-200"></span> 200 - Error log retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-errorlog-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-errorlog-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-errorlog-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-errorlog-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-errorlog-500-schema"></span> Schema
   
  



### 2.57 <span id="get-api-clusters-cluster-name-servers-server-name-is-master"></span> Check if a server is a master (*GetAPIClustersClusterNameServersServerNameIsMaster*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/is-master
```

Checks if a specified server within a cluster is a master.

#### 2.57.1 Produces
  * text/plain

#### 2.57.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.57.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-is-master-200) | OK | 200 -Valid Master! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-is-master-200-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-is-master-500) | Internal Server Error | No cluster |  | [schema](#get-api-clusters-cluster-name-servers-server-name-is-master-500-schema) |
| [503](#get-api-clusters-cluster-name-servers-server-name-is-master-503) | Service Unavailable | 503 -Not a Valid Master! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-is-master-503-schema) |

#### 2.57.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-is-master-200"></span> 200 - 200 -Valid Master!
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-is-master-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-is-master-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-is-master-500-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-is-master-503"></span> 503 - 503 -Not a Valid Master!
Status: Service Unavailable

###### <span id="get-api-clusters-cluster-name-servers-server-name-is-master-503-schema"></span> Schema
   
  



### 2.58 <span id="get-api-clusters-cluster-name-servers-server-name-is-slave"></span> Check if a server is a slave (*GetAPIClustersClusterNameServersServerNameIsSlave*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/is-slave
```

Checks if a specified server within a cluster is a slave.

#### 2.58.1 Produces
  * text/plain

#### 2.58.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.58.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-is-slave-200) | OK | 200 -Valid Slave! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-is-slave-200-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-is-slave-500) | Internal Server Error | No cluster |  | [schema](#get-api-clusters-cluster-name-servers-server-name-is-slave-500-schema) |
| [503](#get-api-clusters-cluster-name-servers-server-name-is-slave-503) | Service Unavailable | 503 -Not a Valid Slave! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-is-slave-503-schema) |

#### 2.58.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-is-slave-200"></span> 200 - 200 -Valid Slave!
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-is-slave-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-is-slave-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-is-slave-500-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-is-slave-503"></span> 503 - 503 -Not a Valid Slave!
Status: Service Unavailable

###### <span id="get-api-clusters-cluster-name-servers-server-name-is-slave-503-schema"></span> Schema
   
  



### 2.59 <span id="get-api-clusters-cluster-name-servers-server-name-master-status"></span> Get master status of a server (*GetAPIClustersClusterNameServersServerNameMasterStatus*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/master-status
```

Retrieves the master status of a specified server within a cluster.

#### 2.59.1 Produces
  * application/json

#### 2.59.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.59.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-master-status-200) | OK | Master status retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-master-status-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-master-status-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-master-status-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-master-status-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-master-status-500-schema) |

#### 2.59.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-master-status-200"></span> 200 - Master status retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-master-status-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-master-status-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-master-status-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-master-status-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-master-status-500-schema"></span> Schema
   
  



### 2.60 <span id="get-api-clusters-cluster-name-servers-server-name-meta-data-locks"></span> Get metadata locks of a server (*GetAPIClustersClusterNameServersServerNameMetaDataLocks*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/meta-data-locks
```

Retrieves the metadata locks of a specified server within a cluster.

#### 2.60.1 Produces
  * application/json

#### 2.60.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.60.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-meta-data-locks-200) | OK | Metadata locks retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-meta-data-locks-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-meta-data-locks-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-meta-data-locks-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-meta-data-locks-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-meta-data-locks-500-schema) |

#### 2.60.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-meta-data-locks-200"></span> 200 - Metadata locks retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-meta-data-locks-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-meta-data-locks-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-meta-data-locks-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-meta-data-locks-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-meta-data-locks-500-schema"></span> Schema
   
  



### 2.61 <span id="get-api-clusters-cluster-name-servers-server-name-processlist"></span> Get process list of a server (*GetAPIClustersClusterNameServersServerNameProcesslist*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/processlist
```

Retrieves the process list of a specified server within a cluster.

#### 2.61.1 Produces
  * application/json

#### 2.61.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.61.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-processlist-200) | OK | Process list retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-processlist-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-processlist-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-processlist-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-processlist-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-processlist-500-schema) |

#### 2.61.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-processlist-200"></span> 200 - Process list retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-processlist-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-processlist-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-processlist-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-processlist-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-processlist-500-schema"></span> Schema
   
  



### 2.62 <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-pfs"></span> Analyze a query using PFS on a server (*GetAPIClustersClusterNameServersServerNameQueriesQueryDigestActionsAnalyzePfs*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/queries/{queryDigest}/actions/analyze-pfs
```

Analyzes a query identified by its digest on a specified server within a cluster using PFS.

#### 2.62.1 Produces
  * application/json

#### 2.62.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| queryDigest | `path` | string | `string` |  | ✓ |  | Query Digest |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.62.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-pfs-200) | OK | Query analyzed successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-pfs-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-pfs-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-pfs-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-pfs-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-pfs-500-schema) |

#### 2.62.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-pfs-200"></span> 200 - Query analyzed successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-pfs-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-pfs-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-pfs-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-pfs-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-pfs-500-schema"></span> Schema
   
  



### 2.63 <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-slowlog"></span> Analyze a query using the slow log on a server (*GetAPIClustersClusterNameServersServerNameQueriesQueryDigestActionsAnalyzeSlowlog*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/queries/{queryDigest}/actions/analyze-slowlog
```

Analyzes a query identified by its digest on a specified server within a cluster using the slow log.

#### 2.63.1 Produces
  * application/json

#### 2.63.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| queryDigest | `path` | string | `string` |  | ✓ |  | Query Digest |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.63.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-slowlog-200) | OK | Query analyzed successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-slowlog-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-slowlog-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-slowlog-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-slowlog-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-slowlog-500-schema) |

#### 2.63.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-slowlog-200"></span> 200 - Query analyzed successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-slowlog-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-slowlog-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-slowlog-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-slowlog-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-analyze-slowlog-500-schema"></span> Schema
   
  



### 2.64 <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-pfs"></span> Explain a query using PFS on a server (*GetAPIClustersClusterNameServersServerNameQueriesQueryDigestActionsExplainPfs*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/queries/{queryDigest}/actions/explain-pfs
```

Explains a query identified by its digest on a specified server within a cluster using PFS.

#### 2.64.1 Produces
  * application/json

#### 2.64.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| queryDigest | `path` | string | `string` |  | ✓ |  | Query Digest |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.64.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-pfs-200) | OK | Query explained successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-pfs-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-pfs-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-pfs-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-pfs-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-pfs-500-schema) |

#### 2.64.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-pfs-200"></span> 200 - Query explained successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-pfs-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-pfs-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-pfs-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-pfs-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-pfs-500-schema"></span> Schema
   
  



### 2.65 <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-slowlog"></span> Explain a query using the slow log on a server (*GetAPIClustersClusterNameServersServerNameQueriesQueryDigestActionsExplainSlowlog*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/queries/{queryDigest}/actions/explain-slowlog
```

Explains a query identified by its digest on a specified server within a cluster using the slow log.

#### 2.65.1 Produces
  * application/json

#### 2.65.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| queryDigest | `path` | string | `string` |  | ✓ |  | Query Digest |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.65.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-slowlog-200) | OK | Query explained successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-slowlog-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-slowlog-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-slowlog-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-slowlog-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-slowlog-500-schema) |

#### 2.65.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-slowlog-200"></span> 200 - Query explained successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-slowlog-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-slowlog-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-slowlog-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-slowlog-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-explain-slowlog-500-schema"></span> Schema
   
  



### 2.66 <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-query"></span> Kill a query on a server (*GetAPIClustersClusterNameServersServerNameQueriesQueryDigestActionsKillQuery*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/queries/{queryDigest}/actions/kill-query
```

Kills a query identified by its digest on a specified server within a cluster.

#### 2.66.1 Produces
  * application/json

#### 2.66.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| queryDigest | `path` | string | `string` |  | ✓ |  | Query Digest |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.66.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-query-200) | OK | Query killed successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-query-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-query-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-query-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-query-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-query-500-schema) |

#### 2.66.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-query-200"></span> 200 - Query killed successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-query-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-query-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-query-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-query-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-query-500-schema"></span> Schema
   
  



### 2.67 <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-thread"></span> Kill a thread on a server (*GetAPIClustersClusterNameServersServerNameQueriesQueryDigestActionsKillThread*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/queries/{queryDigest}/actions/kill-thread
```

Kills a thread identified by its digest on a specified server within a cluster.

#### 2.67.1 Produces
  * application/json

#### 2.67.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| queryDigest | `path` | string | `string` |  | ✓ |  | Query Digest |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.67.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-thread-200) | OK | Query killed successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-thread-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-thread-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-thread-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-thread-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-thread-500-schema) |

#### 2.67.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-thread-200"></span> 200 - Query killed successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-thread-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-thread-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-thread-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-thread-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-queries-query-digest-actions-kill-thread-500-schema"></span> Schema
   
  



### 2.68 <span id="get-api-clusters-cluster-name-servers-server-name-query-response-time"></span> Get query response time of a server (*GetAPIClustersClusterNameServersServerNameQueryResponseTime*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/query-response-time
```

Retrieves the query response time of a specified server within a cluster.

#### 2.68.1 Produces
  * application/json

#### 2.68.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.68.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-query-response-time-200) | OK | Query response time retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-query-response-time-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-query-response-time-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-query-response-time-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-query-response-time-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-query-response-time-500-schema) |

#### 2.68.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-query-response-time-200"></span> 200 - Query response time retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-query-response-time-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-query-response-time-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-query-response-time-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-query-response-time-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-query-response-time-500-schema"></span> Schema
   
  



### 2.69 <span id="get-api-clusters-cluster-name-servers-server-name-schemas"></span> Get schemas of a server (*GetAPIClustersClusterNameServersServerNameSchemas*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/schemas
```

Retrieves the schemas of a specified server within a cluster.

#### 2.69.1 Produces
  * application/json

#### 2.69.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.69.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-schemas-200) | OK | Schemas retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-schemas-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-schemas-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-schemas-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-schemas-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-schemas-500-schema) |

#### 2.69.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-schemas-200"></span> 200 - Schemas retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-schemas-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-schemas-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-schemas-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-schemas-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-schemas-500-schema"></span> Schema
   
  



### 2.70 <span id="get-api-clusters-cluster-name-servers-server-name-server-port-backup"></span> Perform a physical backup on a server port (*GetAPIClustersClusterNameServersServerNameServerPortBackup*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/backup
```

Initiates a physical backup on a specified server port within a cluster.

#### 2.70.1 Produces
  * application/json

#### 2.70.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |
| serverPort | `path` | string | `string` |  | ✓ |  | Server Port |

#### 2.70.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-server-port-backup-200) | OK | Backup initiated successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-backup-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-server-port-backup-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-backup-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-server-port-backup-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-backup-500-schema) |

#### 2.70.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-backup-200"></span> 200 - Backup initiated successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-backup-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-backup-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-backup-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-backup-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-backup-500-schema"></span> Schema
   
  



### 2.71 <span id="get-api-clusters-cluster-name-servers-server-name-server-port-config"></span> Get server port configuration (*GetAPIClustersClusterNameServersServerNameServerPortConfig*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/config
```

Retrieves the configuration of a specified server port within a cluster.

#### 2.71.1 Produces
  * application/octet-stream

#### 2.71.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |
| serverPort | `path` | string | `string` |  | ✓ |  | Server Port |

#### 2.71.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-server-port-config-200) | OK | Configuration file |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-config-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-server-port-config-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-config-403-schema) |
| [404](#get-api-clusters-cluster-name-servers-server-name-server-port-config-404) | Not Found | File not found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-config-404-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-server-port-config-500) | Internal Server Error | No cluster" or "No server |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-config-500-schema) |

#### 2.71.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-config-200"></span> 200 - Configuration file
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-config-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-config-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-config-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-config-404"></span> 404 - File not found
Status: Not Found

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-config-404-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-config-500"></span> 500 - No cluster" or "No server
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-config-500-schema"></span> Schema
   
  



### 2.72 <span id="get-api-clusters-cluster-name-servers-server-name-server-port-is-master"></span> Check if a server port is a master (*GetAPIClustersClusterNameServersServerNameServerPortIsMaster*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/is-master
```

Checks if a specified server port within a cluster is a master.

#### 2.72.1 Produces
  * text/plain

#### 2.72.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |
| serverPort | `path` | string | `string` |  | ✓ |  | Server Port |

#### 2.72.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-server-port-is-master-200) | OK | 200 -Valid Master! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-is-master-200-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-server-port-is-master-500) | Internal Server Error | No cluster |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-is-master-500-schema) |
| [503](#get-api-clusters-cluster-name-servers-server-name-server-port-is-master-503) | Service Unavailable | 503 -Not a Valid Master! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-is-master-503-schema) |

#### 2.72.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-is-master-200"></span> 200 - 200 -Valid Master!
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-is-master-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-is-master-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-is-master-500-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-is-master-503"></span> 503 - 503 -Not a Valid Master!
Status: Service Unavailable

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-is-master-503-schema"></span> Schema
   
  



### 2.73 <span id="get-api-clusters-cluster-name-servers-server-name-server-port-is-slave"></span> Check if a server port is a slave (*GetAPIClustersClusterNameServersServerNameServerPortIsSlave*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/is-slave
```

Checks if a specified server port within a cluster is a slave.

#### 2.73.1 Produces
  * text/plain

#### 2.73.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |
| serverPort | `path` | string | `string` |  | ✓ |  | Server Port |

#### 2.73.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-server-port-is-slave-200) | OK | 200 -Valid Slave! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-is-slave-200-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-server-port-is-slave-500) | Internal Server Error | No cluster |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-is-slave-500-schema) |
| [503](#get-api-clusters-cluster-name-servers-server-name-server-port-is-slave-503) | Service Unavailable | 503 -Not a Valid Slave! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-is-slave-503-schema) |

#### 2.73.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-is-slave-200"></span> 200 - 200 -Valid Slave!
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-is-slave-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-is-slave-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-is-slave-500-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-is-slave-503"></span> 503 - 503 -Not a Valid Slave!
Status: Service Unavailable

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-is-slave-503-schema"></span> Schema
   
  



### 2.74 <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-config-change"></span> Check if a server needs a config change (*GetAPIClustersClusterNameServersServerNameServerPortNeedConfigChange*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/need-config-change
```

Checks if a specified server within a cluster needs a config change.

#### 2.74.1 Produces
  * text/plain

#### 2.74.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |
| serverPort | `path` | string | `string` |  | ✓ |  | Server Port |

#### 2.74.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-server-port-need-config-change-200) | OK | 200 -Need config change! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-config-change-200-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-server-port-need-config-change-500) | Internal Server Error | 500 -No config change needed!" or "500 -No valid server!" or "500 -No cluster! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-config-change-500-schema) |

#### 2.74.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-config-change-200"></span> 200 - 200 -Need config change!
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-config-change-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-config-change-500"></span> 500 - 500 -No config change needed!" or "500 -No valid server!" or "500 -No cluster!
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-config-change-500-schema"></span> Schema
   
  



### 2.75 <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-prov"></span> Check if a server needs provisioning (*GetAPIClustersClusterNameServersServerNameServerPortNeedProv*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/need-prov
```

Checks if a specified server within a cluster needs provisioning.

#### 2.75.1 Produces
  * text/plain

#### 2.75.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |
| serverPort | `path` | string | `string` |  | ✓ |  | Server Port |

#### 2.75.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-server-port-need-prov-200) | OK | 200 -Need provisioning! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-prov-200-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-server-port-need-prov-500) | Internal Server Error | 503 -Not a Valid Server! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-prov-500-schema) |
| [503](#get-api-clusters-cluster-name-servers-server-name-server-port-need-prov-503) | Service Unavailable | 503 -No provisioning needed! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-prov-503-schema) |

#### 2.75.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-prov-200"></span> 200 - 200 -Need provisioning!
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-prov-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-prov-500"></span> 500 - 503 -Not a Valid Server!
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-prov-500-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-prov-503"></span> 503 - 503 -No provisioning needed!
Status: Service Unavailable

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-prov-503-schema"></span> Schema
   
  



### 2.76 <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-reprov"></span> Check if a server needs re-provisioning (*GetAPIClustersClusterNameServersServerNameServerPortNeedReprov*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/need-reprov
```

Checks if a specified server within a cluster needs re-provisioning.

#### 2.76.1 Produces
  * text/plain

#### 2.76.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |
| serverPort | `path` | string | `string` |  | ✓ |  | Server Port |

#### 2.76.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-server-port-need-reprov-200) | OK | 200 -Need reprov! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-reprov-200-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-server-port-need-reprov-500) | Internal Server Error | 503 -Not a Valid Server! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-reprov-500-schema) |
| [503](#get-api-clusters-cluster-name-servers-server-name-server-port-need-reprov-503) | Service Unavailable | 503 -No reprov needed! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-reprov-503-schema) |

#### 2.76.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-reprov-200"></span> 200 - 200 -Need reprov!
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-reprov-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-reprov-500"></span> 500 - 503 -Not a Valid Server!
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-reprov-500-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-reprov-503"></span> 503 - 503 -No reprov needed!
Status: Service Unavailable

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-reprov-503-schema"></span> Schema
   
  



### 2.77 <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-restart"></span> Check if a server needs a restart (*GetAPIClustersClusterNameServersServerNameServerPortNeedRestart*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/need-restart
```

Checks if a specified server within a cluster needs a restart.

#### 2.77.1 Produces
  * text/plain

#### 2.77.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |
| serverPort | `path` | string | `string` |  | ✓ |  | Server Port |

#### 2.77.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-server-port-need-restart-200) | OK | 200 -Need restart! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-restart-200-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-server-port-need-restart-500) | Internal Server Error | 503 -Not a Valid Server! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-restart-500-schema) |
| [503](#get-api-clusters-cluster-name-servers-server-name-server-port-need-restart-503) | Service Unavailable | 503 -No restart needed! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-restart-503-schema) |

#### 2.77.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-restart-200"></span> 200 - 200 -Need restart!
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-restart-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-restart-500"></span> 500 - 503 -Not a Valid Server!
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-restart-500-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-restart-503"></span> 503 - 503 -No restart needed!
Status: Service Unavailable

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-restart-503-schema"></span> Schema
   
  



### 2.78 <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-start"></span> Check if a server needs to start (*GetAPIClustersClusterNameServersServerNameServerPortNeedStart*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/need-start
```

Checks if a specified server within a cluster needs to start.

#### 2.78.1 Produces
  * text/plain

#### 2.78.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |
| serverPort | `path` | string | `string` |  | ✓ |  | Server Port |

#### 2.78.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-server-port-need-start-200) | OK | 200 -Need start! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-start-200-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-server-port-need-start-500) | Internal Server Error | 500 -No start needed!" or "500 -No valid server!" or "500 -No cluster! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-start-500-schema) |

#### 2.78.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-start-200"></span> 200 - 200 -Need start!
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-start-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-start-500"></span> 500 - 500 -No start needed!" or "500 -No valid server!" or "500 -No cluster!
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-start-500-schema"></span> Schema
   
  



### 2.79 <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-stop"></span> Check if a server needs to stop (*GetAPIClustersClusterNameServersServerNameServerPortNeedStop*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/need-stop
```

Checks if a specified server within a cluster needs to stop.

#### 2.79.1 Produces
  * text/plain

#### 2.79.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |
| serverPort | `path` | string | `string` |  | ✓ |  | Server Port |

#### 2.79.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-server-port-need-stop-200) | OK | 200 -Need stop! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-stop-200-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-server-port-need-stop-500) | Internal Server Error | 500 -No stop needed!" or "500 -No valid server!" or "500 -No cluster! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-stop-500-schema) |

#### 2.79.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-stop-200"></span> 200 - 200 -Need stop!
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-stop-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-stop-500"></span> 500 - 500 -No stop needed!" or "500 -No valid server!" or "500 -No cluster!
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-stop-500-schema"></span> Schema
   
  



### 2.80 <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-unprov"></span> Check if a server needs unprovisioning (*GetAPIClustersClusterNameServersServerNameServerPortNeedUnprov*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/need-unprov
```

Checks if a specified server within a cluster needs unprovisioning.

#### 2.80.1 Produces
  * text/plain

#### 2.80.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |
| serverPort | `path` | string | `string` |  | ✓ |  | Server Port |

#### 2.80.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-server-port-need-unprov-200) | OK | 200 -Need unprov! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-unprov-200-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-server-port-need-unprov-500) | Internal Server Error | 503 -Not a Valid Server! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-unprov-500-schema) |
| [503](#get-api-clusters-cluster-name-servers-server-name-server-port-need-unprov-503) | Service Unavailable | 503 -No unprov needed! |  | [schema](#get-api-clusters-cluster-name-servers-server-name-server-port-need-unprov-503-schema) |

#### 2.80.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-unprov-200"></span> 200 - 200 -Need unprov!
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-unprov-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-unprov-500"></span> 500 - 503 -Not a Valid Server!
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-unprov-500-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-unprov-503"></span> 503 - 503 -No unprov needed!
Status: Service Unavailable

###### <span id="get-api-clusters-cluster-name-servers-server-name-server-port-need-unprov-503-schema"></span> Schema
   
  



### 2.81 <span id="get-api-clusters-cluster-name-servers-server-name-service-opensvc"></span> Get database service configuration of a server (*GetAPIClustersClusterNameServersServerNameServiceOpensvc*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/service-opensvc
```

Retrieves the database service configuration of a specified server within a cluster.

#### 2.81.1 Produces
  * application/json

#### 2.81.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.81.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-service-opensvc-200) | OK | Database service configuration retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-service-opensvc-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-service-opensvc-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-service-opensvc-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-service-opensvc-500) | Internal Server Error | Cluster Not Found" or "Server Not Found |  | [schema](#get-api-clusters-cluster-name-servers-server-name-service-opensvc-500-schema) |

#### 2.81.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-service-opensvc-200"></span> 200 - Database service configuration retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-service-opensvc-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-service-opensvc-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-service-opensvc-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-service-opensvc-500"></span> 500 - Cluster Not Found" or "Server Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-service-opensvc-500-schema"></span> Schema
   
  



### 2.82 <span id="get-api-clusters-cluster-name-servers-server-name-slow-queries"></span> Get slow log of a server (*GetAPIClustersClusterNameServersServerNameSlowQueries*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/slow-queries
```

Retrieves the slow log of a specified server within a cluster.

#### 2.82.1 Produces
  * application/json

#### 2.82.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.82.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-slow-queries-200) | OK | Slow log retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-slow-queries-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-slow-queries-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-slow-queries-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-slow-queries-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-slow-queries-500-schema) |

#### 2.82.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-slow-queries-200"></span> 200 - Slow log retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-slow-queries-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-slow-queries-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-slow-queries-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-slow-queries-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-slow-queries-500-schema"></span> Schema
   
  



### 2.83 <span id="get-api-clusters-cluster-name-servers-server-name-status"></span> Get status of a server (*GetAPIClustersClusterNameServersServerNameStatus*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/status
```

Retrieves the status of a specified server within a cluster.

#### 2.83.1 Produces
  * application/json

#### 2.83.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.83.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-status-200) | OK | Status retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-status-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-status-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-status-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-status-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-status-500-schema) |

#### 2.83.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-status-200"></span> 200 - Status retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-status-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-status-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-status-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-status-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-status-500-schema"></span> Schema
   
  



### 2.84 <span id="get-api-clusters-cluster-name-servers-server-name-status-delta"></span> Get status delta of a server (*GetAPIClustersClusterNameServersServerNameStatusDelta*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/status-delta
```

Retrieves the status delta of a specified server within a cluster.

#### 2.84.1 Produces
  * application/json

#### 2.84.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.84.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-status-delta-200) | OK | Status delta retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-status-delta-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-status-delta-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-status-delta-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-status-delta-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-status-delta-500-schema) |

#### 2.84.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-status-delta-200"></span> 200 - Status delta retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-status-delta-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-status-delta-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-status-delta-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-status-delta-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-status-delta-500-schema"></span> Schema
   
  



### 2.85 <span id="get-api-clusters-cluster-name-servers-server-name-status-innodb"></span> Get InnoDB status of a server (*GetAPIClustersClusterNameServersServerNameStatusInnodb*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/status-innodb
```

Retrieves the InnoDB status of a specified server within a cluster.

#### 2.85.1 Produces
  * application/json

#### 2.85.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.85.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-status-innodb-200) | OK | InnoDB status retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-status-innodb-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-status-innodb-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-status-innodb-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-status-innodb-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-status-innodb-500-schema) |

#### 2.85.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-status-innodb-200"></span> 200 - InnoDB status retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-status-innodb-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-status-innodb-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-status-innodb-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-status-innodb-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-status-innodb-500-schema"></span> Schema
   
  



### 2.86 <span id="get-api-clusters-cluster-name-servers-server-name-tables"></span> Get tables of a server (*GetAPIClustersClusterNameServersServerNameTables*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/tables
```

Retrieves the tables of a specified server within a cluster.

#### 2.86.1 Produces
  * application/json

#### 2.86.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.86.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-tables-200) | OK | Tables retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-tables-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-tables-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-tables-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-tables-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-tables-500-schema) |

#### 2.86.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-tables-200"></span> 200 - Tables retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-tables-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-tables-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-tables-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-tables-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-tables-500-schema"></span> Schema
   
  



### 2.87 <span id="get-api-clusters-cluster-name-servers-server-name-variables"></span> Get variables of a server (*GetAPIClustersClusterNameServersServerNameVariables*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/variables
```

Retrieves the variables of a specified server within a cluster.

#### 2.87.1 Produces
  * application/json

#### 2.87.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.87.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-variables-200) | OK | Variables retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-variables-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-variables-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-variables-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-variables-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-variables-500-schema) |

#### 2.87.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-variables-200"></span> 200 - Variables retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-variables-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-variables-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-variables-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-variables-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-variables-500-schema"></span> Schema
   
  



### 2.88 <span id="get-api-clusters-cluster-name-servers-server-name-vtables"></span> Get virtual tables of a server (*GetAPIClustersClusterNameServersServerNameVtables*)

```
GET /api/clusters/{clusterName}/servers/{serverName}/vtables
```

Retrieves the virtual tables of a specified server within a cluster.

#### 2.88.1 Produces
  * application/json

#### 2.88.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.88.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-servers-server-name-vtables-200) | OK | Virtual tables retrieved successfully |  | [schema](#get-api-clusters-cluster-name-servers-server-name-vtables-200-schema) |
| [403](#get-api-clusters-cluster-name-servers-server-name-vtables-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-servers-server-name-vtables-403-schema) |
| [500](#get-api-clusters-cluster-name-servers-server-name-vtables-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Encoding error |  | [schema](#get-api-clusters-cluster-name-servers-server-name-vtables-500-schema) |

#### 2.88.4 Responses


##### <span id="get-api-clusters-cluster-name-servers-server-name-vtables-200"></span> 200 - Virtual tables retrieved successfully
Status: OK

###### <span id="get-api-clusters-cluster-name-servers-server-name-vtables-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-servers-server-name-vtables-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-servers-server-name-vtables-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-servers-server-name-vtables-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Encoding error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-servers-server-name-vtables-500-schema"></span> Schema
   
  



### 2.89 <span id="get-api-clusters-cluster-name-settings"></span> Retrieve settings for a specific cluster (*GetAPIClustersClusterNameSettings*)

```
GET /api/clusters/{clusterName}/settings
```

This endpoint retrieves the settings for the specified cluster.

#### 2.89.1 Produces
  * application/json

#### 2.89.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.89.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-settings-200) | OK | Cluster settings |  | [schema](#get-api-clusters-cluster-name-settings-200-schema) |
| [403](#get-api-clusters-cluster-name-settings-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-settings-403-schema) |
| [500](#get-api-clusters-cluster-name-settings-500) | Internal Server Error | No cluster |  | [schema](#get-api-clusters-cluster-name-settings-500-schema) |

#### 2.89.4 Responses


##### <span id="get-api-clusters-cluster-name-settings-200"></span> 200 - Cluster settings
Status: OK

###### <span id="get-api-clusters-cluster-name-settings-200-schema"></span> Schema
   
  

[GithubComSignal18ReplicationManagerConfigConfig](#github-com-signal18-replication-manager-config-config)

##### <span id="get-api-clusters-cluster-name-settings-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-settings-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-settings-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-settings-500-schema"></span> Schema
   
  



### 2.90 <span id="get-api-clusters-cluster-name-shardclusters"></span> Retrieve shard clusters for a specific cluster (*GetAPIClustersClusterNameShardclusters*)

```
GET /api/clusters/{clusterName}/shardclusters
```

This endpoint retrieves the shard clusters for the specified cluster.

#### 2.90.1 Produces
  * application/json

#### 2.90.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.90.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-shardclusters-200) | OK | List of shard clusters |  | [schema](#get-api-clusters-cluster-name-shardclusters-200-schema) |
| [403](#get-api-clusters-cluster-name-shardclusters-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-shardclusters-403-schema) |
| [500](#get-api-clusters-cluster-name-shardclusters-500) | Internal Server Error | No cluster |  | [schema](#get-api-clusters-cluster-name-shardclusters-500-schema) |

#### 2.90.4 Responses


##### <span id="get-api-clusters-cluster-name-shardclusters-200"></span> 200 - List of shard clusters
Status: OK

###### <span id="get-api-clusters-cluster-name-shardclusters-200-schema"></span> Schema
   
  

[][interface{}](#interface)

##### <span id="get-api-clusters-cluster-name-shardclusters-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-shardclusters-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-shardclusters-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-shardclusters-500-schema"></span> Schema
   
  



### 2.91 <span id="get-api-clusters-cluster-name-sphinx-indexes"></span> Get Sphinx Indexes (*GetAPIClustersClusterNameSphinxIndexes*)

```
GET /api/clusters/{clusterName}/sphinx/indexes
```

Get the Sphinx indexes for a given cluster

#### 2.91.1 Consumes
  * application/json

#### 2.91.2 Produces
  * application/json

#### 2.91.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.91.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-sphinx-indexes-200) | OK | Sphinx Indexes |  | [schema](#get-api-clusters-cluster-name-sphinx-indexes-200-schema) |
| [403](#get-api-clusters-cluster-name-sphinx-indexes-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-sphinx-indexes-403-schema) |
| [404](#get-api-clusters-cluster-name-sphinx-indexes-404) | Not Found | Something went wrong |  | [schema](#get-api-clusters-cluster-name-sphinx-indexes-404-schema) |
| [500](#get-api-clusters-cluster-name-sphinx-indexes-500) | Internal Server Error | Cluster Not Found |  | [schema](#get-api-clusters-cluster-name-sphinx-indexes-500-schema) |

#### 2.91.5 Responses


##### <span id="get-api-clusters-cluster-name-sphinx-indexes-200"></span> 200 - Sphinx Indexes
Status: OK

###### <span id="get-api-clusters-cluster-name-sphinx-indexes-200-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-sphinx-indexes-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-sphinx-indexes-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-sphinx-indexes-404"></span> 404 - Something went wrong
Status: Not Found

###### <span id="get-api-clusters-cluster-name-sphinx-indexes-404-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-sphinx-indexes-500"></span> 500 - Cluster Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-sphinx-indexes-500-schema"></span> Schema
   
  



### 2.92 <span id="get-api-clusters-cluster-name-status"></span> Retrieve status of a cluster (*GetAPIClustersClusterNameStatus*)

```
GET /api/clusters/{clusterName}/status
```

This endpoint retrieves the status of a specified cluster and returns it in JSON format.

#### 2.92.1 Produces
  * application/json

#### 2.92.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.92.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-status-200) | OK | Cluster status |  | [schema](#get-api-clusters-cluster-name-status-200-schema) |
| [400](#get-api-clusters-cluster-name-status-400) | Bad Request | No cluster found |  | [schema](#get-api-clusters-cluster-name-status-400-schema) |

#### 2.92.4 Responses


##### <span id="get-api-clusters-cluster-name-status-200"></span> 200 - Cluster status
Status: OK

###### <span id="get-api-clusters-cluster-name-status-200-schema"></span> Schema
   
  

map of string

##### <span id="get-api-clusters-cluster-name-status-400"></span> 400 - No cluster found
Status: Bad Request

###### <span id="get-api-clusters-cluster-name-status-400-schema"></span> Schema
   
  



### 2.93 <span id="get-api-clusters-cluster-name-tags"></span> Retrieve tags for a specific cluster (*GetAPIClustersClusterNameTags*)

```
GET /api/clusters/{clusterName}/tags
```

This endpoint retrieves the tags for the specified cluster.

#### 2.93.1 Produces
  * application/json

#### 2.93.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.93.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-tags-200) | OK | List of tags |  | [schema](#get-api-clusters-cluster-name-tags-200-schema) |
| [500](#get-api-clusters-cluster-name-tags-500) | Internal Server Error | Internal Server Error |  | [schema](#get-api-clusters-cluster-name-tags-500-schema) |

#### 2.93.4 Responses


##### <span id="get-api-clusters-cluster-name-tags-200"></span> 200 - List of tags
Status: OK

###### <span id="get-api-clusters-cluster-name-tags-200-schema"></span> Schema
   
  

[]string

##### <span id="get-api-clusters-cluster-name-tags-500"></span> 500 - Internal Server Error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-tags-500-schema"></span> Schema
   
  



### 2.94 <span id="get-api-clusters-cluster-name-top"></span> Retrieve top metrics for a specific cluster (*GetAPIClustersClusterNameTop*)

```
GET /api/clusters/{clusterName}/top
```

This endpoint retrieves the top metrics for the specified cluster.

#### 2.94.1 Produces
  * application/json

#### 2.94.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `query` | string | `string` |  |  |  | Server Name |

#### 2.94.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-top-200) | OK | Top metrics |  | [schema](#get-api-clusters-cluster-name-top-200-schema) |
| [403](#get-api-clusters-cluster-name-top-403) | Forbidden | No valid ACL |  | [schema](#get-api-clusters-cluster-name-top-403-schema) |
| [500](#get-api-clusters-cluster-name-top-500) | Internal Server Error | No cluster |  | [schema](#get-api-clusters-cluster-name-top-500-schema) |

#### 2.94.4 Responses


##### <span id="get-api-clusters-cluster-name-top-200"></span> 200 - Top metrics
Status: OK

###### <span id="get-api-clusters-cluster-name-top-200-schema"></span> Schema
   
  

any

##### <span id="get-api-clusters-cluster-name-top-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="get-api-clusters-cluster-name-top-403-schema"></span> Schema
   
  



##### <span id="get-api-clusters-cluster-name-top-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-top-500-schema"></span> Schema
   
  



### 2.95 <span id="get-api-clusters-cluster-name-topology-alerts"></span> Shows the alerts for that specific named cluster (*GetAPIClustersClusterNameTopologyAlerts*)

```
GET /api/clusters/{clusterName}/topology/alerts
```

Shows the alerts for that specific named cluster

#### 2.95.1 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.95.2 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-topology-alerts-200) | OK | A list of alerts |  | [schema](#get-api-clusters-cluster-name-topology-alerts-200-schema) |
| [500](#get-api-clusters-cluster-name-topology-alerts-500) | Internal Server Error | Internal Server Error |  | [schema](#get-api-clusters-cluster-name-topology-alerts-500-schema) |

#### 2.95.3 Responses


##### <span id="get-api-clusters-cluster-name-topology-alerts-200"></span> 200 - A list of alerts
Status: OK

###### <span id="get-api-clusters-cluster-name-topology-alerts-200-schema"></span> Schema
   
  

[ClusterAlerts](#cluster-alerts)

##### <span id="get-api-clusters-cluster-name-topology-alerts-500"></span> 500 - Internal Server Error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-topology-alerts-500-schema"></span> Schema
   
  



### 2.96 <span id="get-api-clusters-cluster-name-topology-crashes"></span> Retrieve crashes for a specific cluster (*GetAPIClustersClusterNameTopologyCrashes*)

```
GET /api/clusters/{clusterName}/topology/crashes
```

This endpoint retrieves the crashes for the specified cluster.

#### 2.96.1 Produces
  * application/json

#### 2.96.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.96.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-topology-crashes-200) | OK | List of crashes |  | [schema](#get-api-clusters-cluster-name-topology-crashes-200-schema) |
| [500](#get-api-clusters-cluster-name-topology-crashes-500) | Internal Server Error | Cluster Not Found |  | [schema](#get-api-clusters-cluster-name-topology-crashes-500-schema) |

#### 2.96.4 Responses


##### <span id="get-api-clusters-cluster-name-topology-crashes-200"></span> 200 - List of crashes
Status: OK

###### <span id="get-api-clusters-cluster-name-topology-crashes-200-schema"></span> Schema
   
  

[]string

##### <span id="get-api-clusters-cluster-name-topology-crashes-500"></span> 500 - Cluster Not Found
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-topology-crashes-500-schema"></span> Schema
   
  



### 2.97 <span id="get-api-clusters-cluster-name-topology-logs"></span> Retrieve logs for a specific cluster (*GetAPIClustersClusterNameTopologyLogs*)

```
GET /api/clusters/{clusterName}/topology/logs
```

This endpoint retrieves the logs for the specified cluster.

#### 2.97.1 Produces
  * application/json

#### 2.97.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.97.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-topology-logs-200) | OK | List of logs |  | [schema](#get-api-clusters-cluster-name-topology-logs-200-schema) |
| [500](#get-api-clusters-cluster-name-topology-logs-500) | Internal Server Error | Internal Server Error |  | [schema](#get-api-clusters-cluster-name-topology-logs-500-schema) |

#### 2.97.4 Responses


##### <span id="get-api-clusters-cluster-name-topology-logs-200"></span> 200 - List of logs
Status: OK

###### <span id="get-api-clusters-cluster-name-topology-logs-200-schema"></span> Schema
   
  

[]string

##### <span id="get-api-clusters-cluster-name-topology-logs-500"></span> 500 - Internal Server Error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-topology-logs-500-schema"></span> Schema
   
  



### 2.98 <span id="get-api-clusters-cluster-name-topology-master"></span> Retrieve master of a cluster (*GetAPIClustersClusterNameTopologyMaster*)

```
GET /api/clusters/{clusterName}/topology/master
```

This endpoint retrieves the master of a specified cluster and returns it in JSON format.

#### 2.98.1 Produces
  * application/json

#### 2.98.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.98.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-topology-master-200) | OK | Master server |  | [schema](#get-api-clusters-cluster-name-topology-master-200-schema) |
| [500](#get-api-clusters-cluster-name-topology-master-500) | Internal Server Error | Internal Server Error |  | [schema](#get-api-clusters-cluster-name-topology-master-500-schema) |

#### 2.98.4 Responses


##### <span id="get-api-clusters-cluster-name-topology-master-200"></span> 200 - Master server
Status: OK

###### <span id="get-api-clusters-cluster-name-topology-master-200-schema"></span> Schema
   
  

[ClusterServerMonitor](#cluster-server-monitor)

##### <span id="get-api-clusters-cluster-name-topology-master-500"></span> 500 - Internal Server Error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-topology-master-500-schema"></span> Schema
   
  



### 2.99 <span id="get-api-clusters-cluster-name-topology-proxies"></span> Shows the proxies for that specific named cluster (*GetAPIClustersClusterNameTopologyProxies*)

```
GET /api/clusters/{clusterName}/topology/proxies
```

Shows the proxies for that specific named cluster

#### 2.99.1 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.99.2 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-topology-proxies-200) | OK | A list of proxies |  | [schema](#get-api-clusters-cluster-name-topology-proxies-200-schema) |
| [500](#get-api-clusters-cluster-name-topology-proxies-500) | Internal Server Error | Internal Server Error |  | [schema](#get-api-clusters-cluster-name-topology-proxies-500-schema) |

#### 2.99.3 Responses


##### <span id="get-api-clusters-cluster-name-topology-proxies-200"></span> 200 - A list of proxies
Status: OK

###### <span id="get-api-clusters-cluster-name-topology-proxies-200-schema"></span> Schema
   
  

[][interface{}](#interface)

##### <span id="get-api-clusters-cluster-name-topology-proxies-500"></span> 500 - Internal Server Error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-topology-proxies-500-schema"></span> Schema
   
  



### 2.100 <span id="get-api-clusters-cluster-name-topology-servers"></span> Retrieve servers for a specific cluster (*GetAPIClustersClusterNameTopologyServers*)

```
GET /api/clusters/{clusterName}/topology/servers
```

This endpoint retrieves the servers for the specified cluster.

#### 2.100.1 Produces
  * application/json

#### 2.100.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.100.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-topology-servers-200) | OK | List of servers |  | [schema](#get-api-clusters-cluster-name-topology-servers-200-schema) |
| [500](#get-api-clusters-cluster-name-topology-servers-500) | Internal Server Error | Internal Server Error |  | [schema](#get-api-clusters-cluster-name-topology-servers-500-schema) |

#### 2.100.4 Responses


##### <span id="get-api-clusters-cluster-name-topology-servers-200"></span> 200 - List of servers
Status: OK

###### <span id="get-api-clusters-cluster-name-topology-servers-200-schema"></span> Schema
   
  

[][interface{}](#interface)

##### <span id="get-api-clusters-cluster-name-topology-servers-500"></span> 500 - Internal Server Error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-topology-servers-500-schema"></span> Schema
   
  



### 2.101 <span id="get-api-clusters-cluster-name-topology-slaves"></span> Shows the slaves for that specific named cluster (*GetAPIClustersClusterNameTopologySlaves*)

```
GET /api/clusters/{clusterName}/topology/slaves
```

Shows the slaves for that specific named cluster

#### 2.101.1 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.101.2 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-cluster-name-topology-slaves-200) | OK | A list of slaves |  | [schema](#get-api-clusters-cluster-name-topology-slaves-200-schema) |
| [500](#get-api-clusters-cluster-name-topology-slaves-500) | Internal Server Error | Internal Server Error |  | [schema](#get-api-clusters-cluster-name-topology-slaves-500-schema) |

#### 2.101.3 Responses


##### <span id="get-api-clusters-cluster-name-topology-slaves-200"></span> 200 - A list of slaves
Status: OK

###### <span id="get-api-clusters-cluster-name-topology-slaves-200-schema"></span> Schema
   
  

[][interface{}](#interface)

##### <span id="get-api-clusters-cluster-name-topology-slaves-500"></span> 500 - Internal Server Error
Status: Internal Server Error

###### <span id="get-api-clusters-cluster-name-topology-slaves-500-schema"></span> Schema
   
  



### 2.102 <span id="get-api-clusters-for-sale"></span> Retrieve peer clusters for sale (*GetAPIClustersForSale*)

```
GET /api/clusters/for-sale
```

This endpoint returns a list of peer clusters that are available for sale, excluding those that are booked by the requesting user.

#### 2.102.1 Produces
  * application/json

#### 2.102.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| Authorization | `header` | string | `string` |  | ✓ |  | JWT token |

#### 2.102.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-for-sale-200) | OK | List of peer clusters available for sale |  | [schema](#get-api-clusters-for-sale-200-schema) |
| [401](#get-api-clusters-for-sale-401) | Unauthorized | Unauthenticated resource |  | [schema](#get-api-clusters-for-sale-401-schema) |
| [500](#get-api-clusters-for-sale-500) | Internal Server Error | Failed to get token claims or Error Marshal |  | [schema](#get-api-clusters-for-sale-500-schema) |

#### 2.102.4 Responses


##### <span id="get-api-clusters-for-sale-200"></span> 200 - List of peer clusters available for sale
Status: OK

###### <span id="get-api-clusters-for-sale-200-schema"></span> Schema
   
  

[][ConfigPeerCluster](#config-peer-cluster)

##### <span id="get-api-clusters-for-sale-401"></span> 401 - Unauthenticated resource
Status: Unauthorized

###### <span id="get-api-clusters-for-sale-401-schema"></span> Schema
   
  



##### <span id="get-api-clusters-for-sale-500"></span> 500 - Failed to get token claims or Error Marshal
Status: Internal Server Error

###### <span id="get-api-clusters-for-sale-500-schema"></span> Schema
   
  



### 2.103 <span id="get-api-clusters-peers"></span> Retrieve peer clusters for a user (*GetAPIClustersPeers*)

```
GET /api/clusters/peers
```

This endpoint retrieves the peer clusters that a user has access to.

#### 2.103.1 Produces
  * application/json

#### 2.103.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| Authorization | `header` | string | `string` |  | ✓ |  | Bearer token |

#### 2.103.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-clusters-peers-200) | OK | List of peer clusters |  | [schema](#get-api-clusters-peers-200-schema) |
| [401](#get-api-clusters-peers-401) | Unauthorized | Unauthenticated resource |  | [schema](#get-api-clusters-peers-401-schema) |
| [500](#get-api-clusters-peers-500) | Internal Server Error | Failed to get token claims or Error Marshal |  | [schema](#get-api-clusters-peers-500-schema) |

#### 2.103.4 Responses


##### <span id="get-api-clusters-peers-200"></span> 200 - List of peer clusters
Status: OK

###### <span id="get-api-clusters-peers-200-schema"></span> Schema
   
  

[][ConfigPeerCluster](#config-peer-cluster)

##### <span id="get-api-clusters-peers-401"></span> 401 - Unauthenticated resource
Status: Unauthorized

###### <span id="get-api-clusters-peers-401-schema"></span> Schema
   
  



##### <span id="get-api-clusters-peers-500"></span> 500 - Failed to get token claims or Error Marshal
Status: Internal Server Error

###### <span id="get-api-clusters-peers-500-schema"></span> Schema
   
  



### 2.104 <span id="get-api-configs-grafana"></span> List Grafana files (*GetAPIConfigsGrafana*)

```
GET /api/configs/grafana
```

Returns a list of Grafana files from the specified directory.

#### 2.104.1 Produces
  * application/json

#### 2.104.2 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-configs-grafana-200) | OK | List of Grafana files |  | [schema](#get-api-configs-grafana-200-schema) |
| [500](#get-api-configs-grafana-500) | Internal Server Error | Internal Server Error |  | [schema](#get-api-configs-grafana-500-schema) |

#### 2.104.3 Responses


##### <span id="get-api-configs-grafana-200"></span> 200 - List of Grafana files
Status: OK

###### <span id="get-api-configs-grafana-200-schema"></span> Schema
   
  

[]string

##### <span id="get-api-configs-grafana-500"></span> 500 - Internal Server Error
Status: Internal Server Error

###### <span id="get-api-configs-grafana-500-schema"></span> Schema
   
  



### 2.105 <span id="get-api-heartbeat"></span> Monitor Heartbeat (*GetAPIHeartbeat*)

```
GET /api/heartbeat
```

Returns the heartbeat status of the replication manager.

#### 2.105.1 Consumes
  * application/json

#### 2.105.2 Produces
  * application/json

#### 2.105.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-heartbeat-200) | OK | OK |  | [schema](#get-api-heartbeat-200-schema) |
| [500](#get-api-heartbeat-500) | Internal Server Error | Internal Server Error |  | [schema](#get-api-heartbeat-500-schema) |

#### 2.105.4 Responses


##### <span id="get-api-heartbeat-200"></span> 200 - OK
Status: OK

###### <span id="get-api-heartbeat-200-schema"></span> Schema
   
  

[ServerHeartbeat](#server-heartbeat)

##### <span id="get-api-heartbeat-500"></span> 500 - Internal Server Error
Status: Internal Server Error

###### <span id="get-api-heartbeat-500-schema"></span> Schema
   
  

map of string

### 2.106 <span id="get-api-monitor"></span> Handles replication manager requests (*GetAPIMonitor*)

```
GET /api/monitor
```

This endpoint processes the replication manager requests, validates cluster ACLs, and returns the cluster list in JSON format.

#### 2.106.1 Consumes
  * application/json

#### 2.106.2 Produces
  * application/json

#### 2.106.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-monitor-200) | OK | Successful response with replication manager details |  | [schema](#get-api-monitor-200-schema) |
| [500](#get-api-monitor-500) | Internal Server Error | Internal Server Error |  | [schema](#get-api-monitor-500-schema) |

#### 2.106.4 Responses


##### <span id="get-api-monitor-200"></span> 200 - Successful response with replication manager details
Status: OK

###### <span id="get-api-monitor-200-schema"></span> Schema
   
  

[ServerReplicationManager](#server-replication-manager)

##### <span id="get-api-monitor-500"></span> 500 - Internal Server Error
Status: Internal Server Error

###### <span id="get-api-monitor-500-schema"></span> Schema
   
  



### 2.107 <span id="get-api-prometheus"></span> Fetch Prometheus metrics (*GetAPIPrometheus*)

```
GET /api/prometheus
```

Fetches Prometheus metrics for all servers in all clusters managed by the replication manager.

#### 2.107.1 Produces
  * text/plain

#### 2.107.2 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-prometheus-200) | OK | Prometheus metrics |  | [schema](#get-api-prometheus-200-schema) |
| [500](#get-api-prometheus-500) | Internal Server Error | Internal Server Error |  | [schema](#get-api-prometheus-500-schema) |

#### 2.107.3 Responses


##### <span id="get-api-prometheus-200"></span> 200 - Prometheus metrics
Status: OK

###### <span id="get-api-prometheus-200-schema"></span> Schema
   
  



##### <span id="get-api-prometheus-500"></span> 500 - Internal Server Error
Status: Internal Server Error

###### <span id="get-api-prometheus-500-schema"></span> Schema
   
  



### 2.108 <span id="get-api-repocomp-current"></span> Retrieve current repository component (*GetAPIRepocompCurrent*)

```
GET /api/repocomp/current
```

Reads the current repository component from the specified directory and returns its content.

#### 2.108.1 Produces
  * text/plain

#### 2.108.2 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-repocomp-current-200) | OK | Current repository component content |  | [schema](#get-api-repocomp-current-200-schema) |
| [404](#get-api-repocomp-current-404) | Not Found | 404 Something went wrong - Not Found |  | [schema](#get-api-repocomp-current-404-schema) |

#### 2.108.3 Responses


##### <span id="get-api-repocomp-current-200"></span> 200 - Current repository component content
Status: OK

###### <span id="get-api-repocomp-current-200-schema"></span> Schema
   
  



##### <span id="get-api-repocomp-current-404"></span> 404 - 404 Something went wrong - Not Found
Status: Not Found

###### <span id="get-api-repocomp-current-404-schema"></span> Schema
   
  



### 2.109 <span id="get-api-status"></span> Get Replication Manager Status (*GetAPIStatus*)

```
GET /api/status
```

Returns the status of the replication manager indicating whether it is running or starting.

#### 2.109.1 Produces
  * application/json

#### 2.109.2 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-status-200) | OK | {"alive": "running"} or {"alive": "starting"} |  | [schema](#get-api-status-200-schema) |

#### 2.109.3 Responses


##### <span id="get-api-status-200"></span> 200 - {"alive": "running"} or {"alive": "starting"}
Status: OK

###### <span id="get-api-status-200-schema"></span> Schema
   
  

map of string

### 2.110 <span id="get-api-terms"></span> Retrieves terms (*GetAPITerms*)

```
GET /api/terms
```

This endpoint returns the terms managed by the replication manager.

#### 2.110.1 Produces
  * text/plain

#### 2.110.2 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-terms-200) | OK | Terms |  | [schema](#get-api-terms-200-schema) |

#### 2.110.3 Responses


##### <span id="get-api-terms-200"></span> 200 - Terms
Status: OK

###### <span id="get-api-terms-200-schema"></span> Schema
   
  



### 2.111 <span id="get-api-timeout"></span> Check if the replication manager is running (*GetAPITimeout*)

```
GET /api/timeout
```

This endpoint is used to check if the replication manager is running. It will respond with a JSON object after a delay of 1200 seconds.

#### 2.111.1 Produces
  * application/json

#### 2.111.2 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#get-api-timeout-200) | OK | OK |  | [schema](#get-api-timeout-200-schema) |

#### 2.111.3 Responses


##### <span id="get-api-timeout-200"></span> 200 - OK
Status: OK

###### <span id="get-api-timeout-200-schema"></span> Schema
   
  

map of string

### 2.112 <span id="post-api-clusters-actions-add-cluster-name"></span> Add a new cluster (*PostAPIClustersActionsAddClusterName*)

```
POST /api/clusters/actions/add/{clusterName}
```

Adds a new cluster to the replication manager. If the cluster already exists, it returns an error.

#### 2.112.1 Consumes
  * application/json

#### 2.112.2 Produces
  * application/json

#### 2.112.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| cluster | `body` | [ClusterClusterForm](#cluster-cluster-form) | `models.ClusterClusterForm` | | ✓ | | Cluster Form |

#### 2.112.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-actions-add-cluster-name-200) | OK | Cluster added successfully |  | [schema](#post-api-clusters-actions-add-cluster-name-200-schema) |
| [400](#post-api-clusters-actions-add-cluster-name-400) | Bad Request | Error in request or Cluster already exists |  | [schema](#post-api-clusters-actions-add-cluster-name-400-schema) |
| [500](#post-api-clusters-actions-add-cluster-name-500) | Internal Server Error | User is not valid |  | [schema](#post-api-clusters-actions-add-cluster-name-500-schema) |

#### 2.112.5 Responses


##### <span id="post-api-clusters-actions-add-cluster-name-200"></span> 200 - Cluster added successfully
Status: OK

###### <span id="post-api-clusters-actions-add-cluster-name-200-schema"></span> Schema
   
  

[ClusterCluster](#cluster-cluster)

##### <span id="post-api-clusters-actions-add-cluster-name-400"></span> 400 - Error in request or Cluster already exists
Status: Bad Request

###### <span id="post-api-clusters-actions-add-cluster-name-400-schema"></span> Schema
   
  



##### <span id="post-api-clusters-actions-add-cluster-name-500"></span> 500 - User is not valid
Status: Internal Server Error

###### <span id="post-api-clusters-actions-add-cluster-name-500-schema"></span> Schema
   
  



### 2.113 <span id="post-api-clusters-cluster-name-actions-add-cluster-sharding-name"></span> Add a sharding cluster to an existing cluster (*PostAPIClustersClusterNameActionsAddClusterShardingName*)

```
POST /api/clusters/{clusterName}/actions/add/{clusterShardingName}
```

This endpoint adds a sharding cluster to an existing cluster and triggers a rolling restart.

#### 2.113.1 Consumes
  * application/json

#### 2.113.2 Produces
  * application/json

#### 2.113.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| clusterShardingName | `path` | string | `string` |  | ✓ |  | Cluster Sharding Name |

#### 2.113.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-add-cluster-sharding-name-200) | OK | Sharding cluster added successfully |  | [schema](#post-api-clusters-cluster-name-actions-add-cluster-sharding-name-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-add-cluster-sharding-name-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-add-cluster-sharding-name-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-add-cluster-sharding-name-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-add-cluster-sharding-name-500-schema) |

#### 2.113.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-add-cluster-sharding-name-200"></span> 200 - Sharding cluster added successfully
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-add-cluster-sharding-name-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-add-cluster-sharding-name-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-add-cluster-sharding-name-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-add-cluster-sharding-name-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-add-cluster-sharding-name-500-schema"></span> Schema
   
  



### 2.114 <span id="post-api-clusters-cluster-name-actions-addserver-host-port"></span> Add a server to a specific cluster (*PostAPIClustersClusterNameActionsAddserverHostPort*)

```
POST /api/clusters/{clusterName}/actions/addserver/{host}/{port}
```

This endpoint adds a server to the specified cluster.

#### 2.114.1 Consumes
  * application/json

#### 2.114.2 Produces
  * application/json

#### 2.114.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| host | `path` | string | `string` |  | ✓ |  | Host |
| port | `path` | string | `string` |  | ✓ |  | Port |

#### 2.114.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-addserver-host-port-200) | OK | Monitor added |  | [schema](#post-api-clusters-cluster-name-actions-addserver-host-port-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-addserver-host-port-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-addserver-host-port-403-schema) |
| [409](#post-api-clusters-cluster-name-actions-addserver-host-port-409) | Conflict | Error adding new monitor |  | [schema](#post-api-clusters-cluster-name-actions-addserver-host-port-409-schema) |
| [500](#post-api-clusters-cluster-name-actions-addserver-host-port-500) | Internal Server Error | Cluster Not Found |  | [schema](#post-api-clusters-cluster-name-actions-addserver-host-port-500-schema) |

#### 2.114.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-addserver-host-port-200"></span> 200 - Monitor added
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-addserver-host-port-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-addserver-host-port-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-addserver-host-port-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-addserver-host-port-409"></span> 409 - Error adding new monitor
Status: Conflict

###### <span id="post-api-clusters-cluster-name-actions-addserver-host-port-409-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-addserver-host-port-500"></span> 500 - Cluster Not Found
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-addserver-host-port-500-schema"></span> Schema
   
  



### 2.115 <span id="post-api-clusters-cluster-name-actions-addserver-host-port-type"></span> Add a server to a specific cluster (*PostAPIClustersClusterNameActionsAddserverHostPortType*)

```
POST /api/clusters/{clusterName}/actions/addserver/{host}/{port}/{type}
```

This endpoint adds a server to the specified cluster.

#### 2.115.1 Consumes
  * application/json

#### 2.115.2 Produces
  * application/json

#### 2.115.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| host | `path` | string | `string` |  | ✓ |  | Host |
| port | `path` | string | `string` |  | ✓ |  | Port |
| type | `path` | string | `string` |  |  |  | Type |

#### 2.115.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-addserver-host-port-type-200) | OK | Monitor added |  | [schema](#post-api-clusters-cluster-name-actions-addserver-host-port-type-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-addserver-host-port-type-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-addserver-host-port-type-403-schema) |
| [409](#post-api-clusters-cluster-name-actions-addserver-host-port-type-409) | Conflict | Error adding new monitor |  | [schema](#post-api-clusters-cluster-name-actions-addserver-host-port-type-409-schema) |
| [500](#post-api-clusters-cluster-name-actions-addserver-host-port-type-500) | Internal Server Error | Cluster Not Found |  | [schema](#post-api-clusters-cluster-name-actions-addserver-host-port-type-500-schema) |

#### 2.115.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-addserver-host-port-type-200"></span> 200 - Monitor added
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-addserver-host-port-type-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-addserver-host-port-type-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-addserver-host-port-type-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-addserver-host-port-type-409"></span> 409 - Error adding new monitor
Status: Conflict

###### <span id="post-api-clusters-cluster-name-actions-addserver-host-port-type-409-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-addserver-host-port-type-500"></span> 500 - Cluster Not Found
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-addserver-host-port-type-500-schema"></span> Schema
   
  



### 2.116 <span id="post-api-clusters-cluster-name-actions-cancel-rolling-reprov"></span> Cancel rolling reprovision for a specific cluster (*PostAPIClustersClusterNameActionsCancelRollingReprov*)

```
POST /api/clusters/{clusterName}/actions/cancel-rolling-reprov
```

This endpoint cancels the rolling reprovision for the specified cluster.

#### 2.116.1 Consumes
  * application/json

#### 2.116.2 Produces
  * application/json

#### 2.116.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.116.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-cancel-rolling-reprov-200) | OK | Successfully cancelled rolling reprovision |  | [schema](#post-api-clusters-cluster-name-actions-cancel-rolling-reprov-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-cancel-rolling-reprov-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-cancel-rolling-reprov-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-cancel-rolling-reprov-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-cancel-rolling-reprov-500-schema) |

#### 2.116.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-cancel-rolling-reprov-200"></span> 200 - Successfully cancelled rolling reprovision
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-cancel-rolling-reprov-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-cancel-rolling-reprov-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-cancel-rolling-reprov-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-cancel-rolling-reprov-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-cancel-rolling-reprov-500-schema"></span> Schema
   
  



### 2.117 <span id="post-api-clusters-cluster-name-actions-cancel-rolling-restart"></span> Cancel rolling restart for a specific cluster (*PostAPIClustersClusterNameActionsCancelRollingRestart*)

```
POST /api/clusters/{clusterName}/actions/cancel-rolling-restart
```

This endpoint cancels the rolling restart for the specified cluster.

#### 2.117.1 Consumes
  * application/json

#### 2.117.2 Produces
  * application/json

#### 2.117.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.117.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-cancel-rolling-restart-200) | OK | Successfully cancelled rolling restart |  | [schema](#post-api-clusters-cluster-name-actions-cancel-rolling-restart-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-cancel-rolling-restart-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-cancel-rolling-restart-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-cancel-rolling-restart-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-cancel-rolling-restart-500-schema) |

#### 2.117.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-cancel-rolling-restart-200"></span> 200 - Successfully cancelled rolling restart
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-cancel-rolling-restart-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-cancel-rolling-restart-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-cancel-rolling-restart-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-cancel-rolling-restart-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-cancel-rolling-restart-500-schema"></span> Schema
   
  



### 2.118 <span id="post-api-clusters-cluster-name-actions-certificates-rotate"></span> Rotate keys for a specific cluster (*PostAPIClustersClusterNameActionsCertificatesRotate*)

```
POST /api/clusters/{clusterName}/actions/certificates-rotate
```

Rotate the keys for the specified cluster

#### 2.118.1 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.118.2 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-certificates-rotate-200) | OK | Keys rotated successfully |  | [schema](#post-api-clusters-cluster-name-actions-certificates-rotate-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-certificates-rotate-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-certificates-rotate-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-certificates-rotate-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-certificates-rotate-500-schema) |

#### 2.118.3 Responses


##### <span id="post-api-clusters-cluster-name-actions-certificates-rotate-200"></span> 200 - Keys rotated successfully
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-certificates-rotate-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-certificates-rotate-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-certificates-rotate-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-certificates-rotate-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-certificates-rotate-500-schema"></span> Schema
   
  



### 2.119 <span id="post-api-clusters-cluster-name-actions-checksum-all-tables"></span> Calculate checksum for all tables in a specific cluster (*PostAPIClustersClusterNameActionsChecksumAllTables*)

```
POST /api/clusters/{clusterName}/actions/checksum-all-tables
```

This endpoint triggers the checksum calculation for all tables in the specified cluster.

#### 2.119.1 Consumes
  * application/json

#### 2.119.2 Produces
  * application/json

#### 2.119.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.119.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-checksum-all-tables-200) | OK | Successfully triggered checksum calculation for all tables |  | [schema](#post-api-clusters-cluster-name-actions-checksum-all-tables-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-checksum-all-tables-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-checksum-all-tables-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-checksum-all-tables-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-checksum-all-tables-500-schema) |

#### 2.119.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-checksum-all-tables-200"></span> 200 - Successfully triggered checksum calculation for all tables
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-checksum-all-tables-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-checksum-all-tables-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-checksum-all-tables-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-checksum-all-tables-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-checksum-all-tables-500-schema"></span> Schema
   
  



### 2.120 <span id="post-api-clusters-cluster-name-actions-failover"></span> Handles the failover process for a given cluster. (*PostAPIClustersClusterNameActionsFailover*)

```
POST /api/clusters/{clusterName}/actions/failover
```

This endpoint triggers a master failover for the specified cluster.

#### 2.120.1 Consumes
  * application/json

#### 2.120.2 Produces
  * application/json

#### 2.120.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.120.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-failover-200) | OK | Successfully triggered failover |  | [schema](#post-api-clusters-cluster-name-actions-failover-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-failover-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-failover-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-failover-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-failover-500-schema) |

#### 2.120.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-failover-200"></span> 200 - Successfully triggered failover
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-failover-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-failover-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-failover-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-failover-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-failover-500-schema"></span> Schema
   
  



### 2.121 <span id="post-api-clusters-cluster-name-actions-master-physical-backup"></span> Perform a physical backup for the master of a specific cluster (*PostAPIClustersClusterNameActionsMasterPhysicalBackup*)

```
POST /api/clusters/{clusterName}/actions/master-physical-backup
```

This endpoint triggers a physical backup for the master of the specified cluster.

#### 2.121.1 Consumes
  * application/json

#### 2.121.2 Produces
  * application/json

#### 2.121.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.121.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-master-physical-backup-200) | OK | Successfully triggered physical backup |  | [schema](#post-api-clusters-cluster-name-actions-master-physical-backup-200-schema) |
| [400](#post-api-clusters-cluster-name-actions-master-physical-backup-400) | Bad Request | No cluster found |  | [schema](#post-api-clusters-cluster-name-actions-master-physical-backup-400-schema) |
| [403](#post-api-clusters-cluster-name-actions-master-physical-backup-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-master-physical-backup-403-schema) |

#### 2.121.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-master-physical-backup-200"></span> 200 - Successfully triggered physical backup
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-master-physical-backup-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-master-physical-backup-400"></span> 400 - No cluster found
Status: Bad Request

###### <span id="post-api-clusters-cluster-name-actions-master-physical-backup-400-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-master-physical-backup-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-master-physical-backup-403-schema"></span> Schema
   
  



### 2.122 <span id="post-api-clusters-cluster-name-actions-optimize"></span> Optimize a specific cluster (*PostAPIClustersClusterNameActionsOptimize*)

```
POST /api/clusters/{clusterName}/actions/optimize
```

This endpoint triggers the optimization process for the specified cluster.

#### 2.122.1 Consumes
  * application/json

#### 2.122.2 Produces
  * application/json

#### 2.122.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.122.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-optimize-200) | OK | Successfully triggered optimization |  | [schema](#post-api-clusters-cluster-name-actions-optimize-200-schema) |
| [400](#post-api-clusters-cluster-name-actions-optimize-400) | Bad Request | No cluster found |  | [schema](#post-api-clusters-cluster-name-actions-optimize-400-schema) |
| [403](#post-api-clusters-cluster-name-actions-optimize-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-optimize-403-schema) |

#### 2.122.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-optimize-200"></span> 200 - Successfully triggered optimization
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-optimize-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-optimize-400"></span> 400 - No cluster found
Status: Bad Request

###### <span id="post-api-clusters-cluster-name-actions-optimize-400-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-optimize-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-optimize-403-schema"></span> Schema
   
  



### 2.123 <span id="post-api-clusters-cluster-name-actions-replication-bootstrap-topology"></span> Bootstrap replication for a specific cluster (*PostAPIClustersClusterNameActionsReplicationBootstrapTopology*)

```
POST /api/clusters/{clusterName}/actions/replication/bootstrap/{topology}
```

This endpoint triggers the bootstrap replication process for the specified cluster.

#### 2.123.1 Consumes
  * application/json

#### 2.123.2 Produces
  * application/json

#### 2.123.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| topology | `path` | string | `string` |  | ✓ |  | Topology |

#### 2.123.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-replication-bootstrap-topology-200) | OK | Successfully bootstrapped replication |  | [schema](#post-api-clusters-cluster-name-actions-replication-bootstrap-topology-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-replication-bootstrap-topology-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-replication-bootstrap-topology-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-replication-bootstrap-topology-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-replication-bootstrap-topology-500-schema) |

#### 2.123.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-replication-bootstrap-topology-200"></span> 200 - Successfully bootstrapped replication
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-replication-bootstrap-topology-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-replication-bootstrap-topology-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-replication-bootstrap-topology-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-replication-bootstrap-topology-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-replication-bootstrap-topology-500-schema"></span> Schema
   
  



### 2.124 <span id="post-api-clusters-cluster-name-actions-replication-cleanup"></span> Cleanup replication bootstrap for a specific cluster (*PostAPIClustersClusterNameActionsReplicationCleanup*)

```
POST /api/clusters/{clusterName}/actions/replication/cleanup
```

This endpoint triggers the cleanup process for replication bootstrap for the specified cluster.

#### 2.124.1 Consumes
  * application/json

#### 2.124.2 Produces
  * application/json

#### 2.124.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.124.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-replication-cleanup-200) | OK | Successfully cleaned up replication bootstrap |  | [schema](#post-api-clusters-cluster-name-actions-replication-cleanup-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-replication-cleanup-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-replication-cleanup-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-replication-cleanup-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-replication-cleanup-500-schema) |

#### 2.124.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-replication-cleanup-200"></span> 200 - Successfully cleaned up replication bootstrap
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-replication-cleanup-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-replication-cleanup-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-replication-cleanup-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-replication-cleanup-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-replication-cleanup-500-schema"></span> Schema
   
  



### 2.125 <span id="post-api-clusters-cluster-name-actions-reset-failover-control"></span> Reset failover control for a specific cluster (*PostAPIClustersClusterNameActionsResetFailoverControl*)

```
POST /api/clusters/{clusterName}/actions/reset-failover-control
```

This endpoint resets the failover control for the specified cluster.

#### 2.125.1 Consumes
  * application/json

#### 2.125.2 Produces
  * application/json

#### 2.125.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.125.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-reset-failover-control-200) | OK | Successfully reset failover control |  | [schema](#post-api-clusters-cluster-name-actions-reset-failover-control-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-reset-failover-control-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-reset-failover-control-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-reset-failover-control-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-reset-failover-control-500-schema) |

#### 2.125.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-reset-failover-control-200"></span> 200 - Successfully reset failover control
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-reset-failover-control-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-reset-failover-control-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-reset-failover-control-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-reset-failover-control-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-reset-failover-control-500-schema"></span> Schema
   
  



### 2.126 <span id="post-api-clusters-cluster-name-actions-reset-sla"></span> Reset SLA for a specific cluster (*PostAPIClustersClusterNameActionsResetSLA*)

```
POST /api/clusters/{clusterName}/actions/reset-sla
```

Reset the SLA for the specified cluster

#### 2.126.1 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.126.2 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-reset-sla-200) | OK | SLA reset successfully |  | [schema](#post-api-clusters-cluster-name-actions-reset-sla-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-reset-sla-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-reset-sla-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-reset-sla-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-reset-sla-500-schema) |

#### 2.126.3 Responses


##### <span id="post-api-clusters-cluster-name-actions-reset-sla-200"></span> 200 - SLA reset successfully
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-reset-sla-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-reset-sla-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-reset-sla-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-reset-sla-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-reset-sla-500-schema"></span> Schema
   
  



### 2.127 <span id="post-api-clusters-cluster-name-actions-rolling"></span> Handles the rolling restart process for a given cluster. (*PostAPIClustersClusterNameActionsRolling*)

```
POST /api/clusters/{clusterName}/actions/rolling
```

This endpoint triggers a rolling restart for the specified cluster.

#### 2.127.1 Consumes
  * application/json

#### 2.127.2 Produces
  * application/json

#### 2.127.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.127.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-rolling-200) | OK | Successfully triggered rolling restart |  | [schema](#post-api-clusters-cluster-name-actions-rolling-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-rolling-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-rolling-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-rolling-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-rolling-500-schema) |

#### 2.127.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-rolling-200"></span> 200 - Successfully triggered rolling restart
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-rolling-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-rolling-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-rolling-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-rolling-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-rolling-500-schema"></span> Schema
   
  



### 2.128 <span id="post-api-clusters-cluster-name-actions-rotate-passwords"></span> Rotate passwords for a specific cluster (*PostAPIClustersClusterNameActionsRotatePasswords*)

```
POST /api/clusters/{clusterName}/actions/rotate-passwords
```

This endpoint rotates the passwords for the specified cluster.

#### 2.128.1 Consumes
  * application/json

#### 2.128.2 Produces
  * application/json

#### 2.128.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.128.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-rotate-passwords-200) | OK | Successfully rotated passwords |  | [schema](#post-api-clusters-cluster-name-actions-rotate-passwords-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-rotate-passwords-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-rotate-passwords-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-rotate-passwords-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-rotate-passwords-500-schema) |

#### 2.128.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-rotate-passwords-200"></span> 200 - Successfully rotated passwords
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-rotate-passwords-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-rotate-passwords-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-rotate-passwords-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-rotate-passwords-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-rotate-passwords-500-schema"></span> Schema
   
  



### 2.129 <span id="post-api-clusters-cluster-name-actions-start-traffic"></span> Start traffic for a specific cluster (*PostAPIClustersClusterNameActionsStartTraffic*)

```
POST /api/clusters/{clusterName}/actions/start-traffic
```

This endpoint starts traffic for the specified cluster.

#### 2.129.1 Consumes
  * application/json

#### 2.129.2 Produces
  * application/json

#### 2.129.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.129.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-start-traffic-200) | OK | Successfully started traffic |  | [schema](#post-api-clusters-cluster-name-actions-start-traffic-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-start-traffic-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-start-traffic-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-start-traffic-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-start-traffic-500-schema) |

#### 2.129.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-start-traffic-200"></span> 200 - Successfully started traffic
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-start-traffic-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-start-traffic-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-start-traffic-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-start-traffic-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-start-traffic-500-schema"></span> Schema
   
  



### 2.130 <span id="post-api-clusters-cluster-name-actions-stop-traffic"></span> Stop traffic for a specific cluster (*PostAPIClustersClusterNameActionsStopTraffic*)

```
POST /api/clusters/{clusterName}/actions/stop-traffic
```

This endpoint stops traffic for the specified cluster.

#### 2.130.1 Consumes
  * application/json

#### 2.130.2 Produces
  * application/json

#### 2.130.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.130.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-stop-traffic-200) | OK | Successfully stopped traffic |  | [schema](#post-api-clusters-cluster-name-actions-stop-traffic-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-stop-traffic-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-stop-traffic-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-stop-traffic-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-stop-traffic-500-schema) |

#### 2.130.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-stop-traffic-200"></span> 200 - Successfully stopped traffic
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-stop-traffic-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-stop-traffic-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-stop-traffic-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-stop-traffic-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-stop-traffic-500-schema"></span> Schema
   
  



### 2.131 <span id="post-api-clusters-cluster-name-actions-switchover"></span> Handles the switchover process for a given cluster. (*PostAPIClustersClusterNameActionsSwitchover*)

```
POST /api/clusters/{clusterName}/actions/switchover
```

This endpoint triggers a master switchover for the specified cluster.

#### 2.131.1 Consumes
  * application/json

#### 2.131.2 Produces
  * application/json

#### 2.131.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| prefmaster | `formData` | string | `string` |  |  |  | Preferred Master |

#### 2.131.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-switchover-200) | OK | Successfully triggered switchover |  | [schema](#post-api-clusters-cluster-name-actions-switchover-200-schema) |
| [400](#post-api-clusters-cluster-name-actions-switchover-400) | Bad Request | Master failed |  | [schema](#post-api-clusters-cluster-name-actions-switchover-400-schema) |
| [403](#post-api-clusters-cluster-name-actions-switchover-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-switchover-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-switchover-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-switchover-500-schema) |

#### 2.131.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-switchover-200"></span> 200 - Successfully triggered switchover
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-switchover-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-switchover-400"></span> 400 - Master failed
Status: Bad Request

###### <span id="post-api-clusters-cluster-name-actions-switchover-400-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-switchover-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-switchover-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-switchover-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-switchover-500-schema"></span> Schema
   
  



### 2.132 <span id="post-api-clusters-cluster-name-actions-sysbench"></span> Run sysbench for a specific cluster (*PostAPIClustersClusterNameActionsSysbench*)

```
POST /api/clusters/{clusterName}/actions/sysbench
```

This endpoint runs sysbench for the specified cluster.

#### 2.132.1 Consumes
  * application/json

#### 2.132.2 Produces
  * application/json

#### 2.132.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| threads | `query` | string | `string` |  |  |  | Number of threads |

#### 2.132.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-sysbench-200) | OK | Successfully triggered sysbench |  | [schema](#post-api-clusters-cluster-name-actions-sysbench-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-sysbench-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-sysbench-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-sysbench-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-sysbench-500-schema) |

#### 2.132.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-sysbench-200"></span> 200 - Successfully triggered sysbench
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-sysbench-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-sysbench-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-sysbench-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-sysbench-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-sysbench-500-schema"></span> Schema
   
  



### 2.133 <span id="post-api-clusters-cluster-name-actions-waitdatabases"></span> Wait for databases to be ready for a specific cluster (*PostAPIClustersClusterNameActionsWaitdatabases*)

```
POST /api/clusters/{clusterName}/actions/waitdatabases
```

This endpoint waits for the databases to be ready for the specified cluster.

#### 2.133.1 Consumes
  * application/json

#### 2.133.2 Produces
  * application/json

#### 2.133.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.133.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-actions-waitdatabases-200) | OK | Databases are ready |  | [schema](#post-api-clusters-cluster-name-actions-waitdatabases-200-schema) |
| [403](#post-api-clusters-cluster-name-actions-waitdatabases-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-actions-waitdatabases-403-schema) |
| [500](#post-api-clusters-cluster-name-actions-waitdatabases-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-actions-waitdatabases-500-schema) |

#### 2.133.5 Responses


##### <span id="post-api-clusters-cluster-name-actions-waitdatabases-200"></span> 200 - Databases are ready
Status: OK

###### <span id="post-api-clusters-cluster-name-actions-waitdatabases-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-waitdatabases-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-actions-waitdatabases-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-actions-waitdatabases-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-actions-waitdatabases-500-schema"></span> Schema
   
  



### 2.134 <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-provision"></span> Provision Proxy Service (*PostAPIClustersClusterNameProxiesProxyNameActionsProvision*)

```
POST /api/clusters/{clusterName}/proxies/{proxyName}/actions/provision
```

Provision the proxy service for a given cluster and proxy

#### 2.134.1 Consumes
  * application/json

#### 2.134.2 Produces
  * application/json

#### 2.134.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| proxyName | `path` | string | `string` |  | ✓ |  | Proxy Name |

#### 2.134.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-proxies-proxy-name-actions-provision-200) | OK | Proxy Service Provisioned |  | [schema](#post-api-clusters-cluster-name-proxies-proxy-name-actions-provision-200-schema) |
| [403](#post-api-clusters-cluster-name-proxies-proxy-name-actions-provision-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-proxies-proxy-name-actions-provision-403-schema) |
| [500](#post-api-clusters-cluster-name-proxies-proxy-name-actions-provision-500) | Internal Server Error | Cluster Not Found" "Server Not Found |  | [schema](#post-api-clusters-cluster-name-proxies-proxy-name-actions-provision-500-schema) |

#### 2.134.5 Responses


##### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-provision-200"></span> 200 - Proxy Service Provisioned
Status: OK

###### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-provision-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-provision-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-provision-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-provision-500"></span> 500 - Cluster Not Found" "Server Not Found
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-provision-500-schema"></span> Schema
   
  



### 2.135 <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-start"></span> Start Proxy Service (*PostAPIClustersClusterNameProxiesProxyNameActionsStart*)

```
POST /api/clusters/{clusterName}/proxies/{proxyName}/actions/start
```

Start the proxy service for a given cluster and proxy

#### 2.135.1 Consumes
  * application/json

#### 2.135.2 Produces
  * application/json

#### 2.135.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| proxyName | `path` | string | `string` |  | ✓ |  | Proxy Name |

#### 2.135.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-proxies-proxy-name-actions-start-200) | OK | Proxy Service Started |  | [schema](#post-api-clusters-cluster-name-proxies-proxy-name-actions-start-200-schema) |
| [403](#post-api-clusters-cluster-name-proxies-proxy-name-actions-start-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-proxies-proxy-name-actions-start-403-schema) |
| [500](#post-api-clusters-cluster-name-proxies-proxy-name-actions-start-500) | Internal Server Error | Cluster Not Found" "Server Not Found |  | [schema](#post-api-clusters-cluster-name-proxies-proxy-name-actions-start-500-schema) |

#### 2.135.5 Responses


##### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-start-200"></span> 200 - Proxy Service Started
Status: OK

###### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-start-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-start-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-start-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-start-500"></span> 500 - Cluster Not Found" "Server Not Found
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-start-500-schema"></span> Schema
   
  



### 2.136 <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-stop"></span> Stop Proxy Service (*PostAPIClustersClusterNameProxiesProxyNameActionsStop*)

```
POST /api/clusters/{clusterName}/proxies/{proxyName}/actions/stop
```

Stop the proxy service for a given cluster and proxy

#### 2.136.1 Consumes
  * application/json

#### 2.136.2 Produces
  * application/json

#### 2.136.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| proxyName | `path` | string | `string` |  | ✓ |  | Proxy Name |

#### 2.136.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-proxies-proxy-name-actions-stop-200) | OK | Proxy Service Stopped |  | [schema](#post-api-clusters-cluster-name-proxies-proxy-name-actions-stop-200-schema) |
| [403](#post-api-clusters-cluster-name-proxies-proxy-name-actions-stop-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-proxies-proxy-name-actions-stop-403-schema) |
| [500](#post-api-clusters-cluster-name-proxies-proxy-name-actions-stop-500) | Internal Server Error | Cluster Not Found" "Server Not Found |  | [schema](#post-api-clusters-cluster-name-proxies-proxy-name-actions-stop-500-schema) |

#### 2.136.5 Responses


##### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-stop-200"></span> 200 - Proxy Service Stopped
Status: OK

###### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-stop-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-stop-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-stop-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-stop-500"></span> 500 - Cluster Not Found" "Server Not Found
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-stop-500-schema"></span> Schema
   
  



### 2.137 <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-unprovision"></span> Unprovision Proxy Service (*PostAPIClustersClusterNameProxiesProxyNameActionsUnprovision*)

```
POST /api/clusters/{clusterName}/proxies/{proxyName}/actions/unprovision
```

Unprovision the proxy service for a given cluster and proxy

#### 2.137.1 Consumes
  * application/json

#### 2.137.2 Produces
  * application/json

#### 2.137.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| proxyName | `path` | string | `string` |  | ✓ |  | Proxy Name |

#### 2.137.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-proxies-proxy-name-actions-unprovision-200) | OK | Proxy Service Unprovisioned |  | [schema](#post-api-clusters-cluster-name-proxies-proxy-name-actions-unprovision-200-schema) |
| [403](#post-api-clusters-cluster-name-proxies-proxy-name-actions-unprovision-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-proxies-proxy-name-actions-unprovision-403-schema) |
| [500](#post-api-clusters-cluster-name-proxies-proxy-name-actions-unprovision-500) | Internal Server Error | Cluster Not Found" "Server Not Found |  | [schema](#post-api-clusters-cluster-name-proxies-proxy-name-actions-unprovision-500-schema) |

#### 2.137.5 Responses


##### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-unprovision-200"></span> 200 - Proxy Service Unprovisioned
Status: OK

###### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-unprovision-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-unprovision-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-unprovision-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-unprovision-500"></span> 500 - Cluster Not Found" "Server Not Found
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-proxies-proxy-name-actions-unprovision-500-schema"></span> Schema
   
  



### 2.138 <span id="post-api-clusters-cluster-name-sales-accept-subscription"></span> Accept a subscription for a specific cluster (*PostAPIClustersClusterNameSalesAcceptSubscription*)

```
POST /api/clusters/{clusterName}/sales/accept-subscription
```

This endpoint accepts a subscription for the specified cluster.

#### 2.138.1 Consumes
  * application/json

#### 2.138.2 Produces
  * application/json

#### 2.138.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| body | `body` | [ClusterUserForm](#cluster-user-form) | `models.ClusterUserForm` | | ✓ | | User Form |

#### 2.138.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-sales-accept-subscription-200) | OK | Email sent to sponsor! |  | [schema](#post-api-clusters-cluster-name-sales-accept-subscription-200-schema) |
| [403](#post-api-clusters-cluster-name-sales-accept-subscription-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-sales-accept-subscription-403-schema) |
| [500](#post-api-clusters-cluster-name-sales-accept-subscription-500) | Internal Server Error | Error accepting subscription |  | [schema](#post-api-clusters-cluster-name-sales-accept-subscription-500-schema) |

#### 2.138.5 Responses


##### <span id="post-api-clusters-cluster-name-sales-accept-subscription-200"></span> 200 - Email sent to sponsor!
Status: OK

###### <span id="post-api-clusters-cluster-name-sales-accept-subscription-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-sales-accept-subscription-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-sales-accept-subscription-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-sales-accept-subscription-500"></span> 500 - Error accepting subscription
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-sales-accept-subscription-500-schema"></span> Schema
   
  



### 2.139 <span id="post-api-clusters-cluster-name-sales-end-subscription"></span> Remove a sponsor from a specific cluster (*PostAPIClustersClusterNameSalesEndSubscription*)

```
POST /api/clusters/{clusterName}/sales/end-subscription
```

This endpoint removes a sponsor from the specified cluster.

#### 2.139.1 Consumes
  * application/json

#### 2.139.2 Produces
  * application/json

#### 2.139.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| body | `body` | [ClusterUserForm](#cluster-user-form) | `models.ClusterUserForm` | | ✓ | | User Form |

#### 2.139.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-sales-end-subscription-200) | OK | Sponsor subscription removed! |  | [schema](#post-api-clusters-cluster-name-sales-end-subscription-200-schema) |
| [403](#post-api-clusters-cluster-name-sales-end-subscription-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-sales-end-subscription-403-schema) |
| [500](#post-api-clusters-cluster-name-sales-end-subscription-500) | Internal Server Error | Error removing sponsor subscription |  | [schema](#post-api-clusters-cluster-name-sales-end-subscription-500-schema) |

#### 2.139.5 Responses


##### <span id="post-api-clusters-cluster-name-sales-end-subscription-200"></span> 200 - Sponsor subscription removed!
Status: OK

###### <span id="post-api-clusters-cluster-name-sales-end-subscription-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-sales-end-subscription-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-sales-end-subscription-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-sales-end-subscription-500"></span> 500 - Error removing sponsor subscription
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-sales-end-subscription-500-schema"></span> Schema
   
  



### 2.140 <span id="post-api-clusters-cluster-name-sales-refuse-subscription"></span> Reject a subscription for a specific cluster (*PostAPIClustersClusterNameSalesRefuseSubscription*)

```
POST /api/clusters/{clusterName}/sales/refuse-subscription
```

This endpoint rejects a subscription for the specified cluster.

#### 2.140.1 Consumes
  * application/json

#### 2.140.2 Produces
  * application/json

#### 2.140.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| body | `body` | [ClusterUserForm](#cluster-user-form) | `models.ClusterUserForm` | | ✓ | | User Form |

#### 2.140.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-sales-refuse-subscription-200) | OK | Subscription removed! |  | [schema](#post-api-clusters-cluster-name-sales-refuse-subscription-200-schema) |
| [403](#post-api-clusters-cluster-name-sales-refuse-subscription-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-sales-refuse-subscription-403-schema) |
| [500](#post-api-clusters-cluster-name-sales-refuse-subscription-500) | Internal Server Error | Error removing subscription |  | [schema](#post-api-clusters-cluster-name-sales-refuse-subscription-500-schema) |

#### 2.140.5 Responses


##### <span id="post-api-clusters-cluster-name-sales-refuse-subscription-200"></span> 200 - Subscription removed!
Status: OK

###### <span id="post-api-clusters-cluster-name-sales-refuse-subscription-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-sales-refuse-subscription-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-sales-refuse-subscription-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-sales-refuse-subscription-500"></span> 500 - Error removing subscription
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-sales-refuse-subscription-500-schema"></span> Schema
   
  



### 2.141 <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-checksum-table"></span> Calculate checksum for a specific table in a specific cluster (*PostAPIClustersClusterNameSchemaSchemaNameTableNameActionsChecksumTable*)

```
POST /api/clusters/{clusterName}/schema/{schemaName}/{tableName}/actions/checksum-table
```

This endpoint triggers the checksum calculation for a specific table in the specified cluster.

#### 2.141.1 Consumes
  * application/json

#### 2.141.2 Produces
  * application/json

#### 2.141.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| schemaName | `path` | string | `string` |  | ✓ |  | Schema Name |
| tableName | `path` | string | `string` |  | ✓ |  | Table Name |

#### 2.141.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-checksum-table-200) | OK | Successfully triggered checksum calculation for the table |  | [schema](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-checksum-table-200-schema) |
| [403](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-checksum-table-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-checksum-table-403-schema) |
| [500](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-checksum-table-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-checksum-table-500-schema) |

#### 2.141.5 Responses


##### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-checksum-table-200"></span> 200 - Successfully triggered checksum calculation for the table
Status: OK

###### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-checksum-table-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-checksum-table-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-checksum-table-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-checksum-table-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-checksum-table-500-schema"></span> Schema
   
  



### 2.142 <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-move-table-cluster-shard"></span> Move a table to a different shard cluster (*PostAPIClustersClusterNameSchemaSchemaNameTableNameActionsMoveTableClusterShard*)

```
POST /api/clusters/{clusterName}/schema/{schemaName}/{tableName}/actions/move-table/{clusterShard}
```

This endpoint moves a table to a different shard cluster for the specified cluster.

#### 2.142.1 Consumes
  * application/json

#### 2.142.2 Produces
  * application/json

#### 2.142.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| clusterShard | `path` | string | `string` |  | ✓ |  | Cluster Shard |
| schemaName | `path` | string | `string` |  | ✓ |  | Schema Name |
| tableName | `path` | string | `string` |  | ✓ |  | Table Name |

#### 2.142.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-move-table-cluster-shard-200) | OK | Successfully moved table |  | [schema](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-move-table-cluster-shard-200-schema) |
| [403](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-move-table-cluster-shard-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-move-table-cluster-shard-403-schema) |
| [500](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-move-table-cluster-shard-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-move-table-cluster-shard-500-schema) |

#### 2.142.5 Responses


##### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-move-table-cluster-shard-200"></span> 200 - Successfully moved table
Status: OK

###### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-move-table-cluster-shard-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-move-table-cluster-shard-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-move-table-cluster-shard-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-move-table-cluster-shard-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-move-table-cluster-shard-500-schema"></span> Schema
   
  



### 2.143 <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table"></span> Reshard a table for a specific cluster (*PostAPIClustersClusterNameSchemaSchemaNameTableNameActionsReshardTable*)

```
POST /api/clusters/{clusterName}/schema/{schemaName}/{tableName}/actions/reshard-table
```

This endpoint triggers the resharding of a table for the specified cluster.

#### 2.143.1 Consumes
  * application/json

#### 2.143.2 Produces
  * application/json

#### 2.143.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| schemaName | `path` | string | `string` |  | ✓ |  | Schema Name |
| tableName | `path` | string | `string` |  | ✓ |  | Table Name |

#### 2.143.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-200) | OK | Successfully triggered resharding of the table |  | [schema](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-200-schema) |
| [403](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-403-schema) |
| [500](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-500-schema) |

#### 2.143.5 Responses


##### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-200"></span> 200 - Successfully triggered resharding of the table
Status: OK

###### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-500-schema"></span> Schema
   
  



### 2.144 <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-cluster-list"></span> Reshard a table for a specific cluster (*PostAPIClustersClusterNameSchemaSchemaNameTableNameActionsReshardTableClusterList*)

```
POST /api/clusters/{clusterName}/schema/{schemaName}/{tableName}/actions/reshard-table/{clusterList}
```

This endpoint triggers the resharding of a table for the specified cluster.

#### 2.144.1 Consumes
  * application/json

#### 2.144.2 Produces
  * application/json

#### 2.144.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterList | `path` | string | `string` |  |  |  | Cluster List |
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| schemaName | `path` | string | `string` |  | ✓ |  | Schema Name |
| tableName | `path` | string | `string` |  | ✓ |  | Table Name |

#### 2.144.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-cluster-list-200) | OK | Successfully triggered resharding of the table |  | [schema](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-cluster-list-200-schema) |
| [403](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-cluster-list-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-cluster-list-403-schema) |
| [500](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-cluster-list-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-cluster-list-500-schema) |

#### 2.144.5 Responses


##### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-cluster-list-200"></span> 200 - Successfully triggered resharding of the table
Status: OK

###### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-cluster-list-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-cluster-list-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-cluster-list-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-cluster-list-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-reshard-table-cluster-list-500-schema"></span> Schema
   
  



### 2.145 <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-universal-table"></span> Set a universal table for a specific cluster (*PostAPIClustersClusterNameSchemaSchemaNameTableNameActionsUniversalTable*)

```
POST /api/clusters/{clusterName}/schema/{schemaName}/{tableName}/actions/universal-table
```

This endpoint sets a universal table for the specified cluster.

#### 2.145.1 Consumes
  * application/json

#### 2.145.2 Produces
  * application/json

#### 2.145.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| schemaName | `path` | string | `string` |  | ✓ |  | Schema Name |
| tableName | `path` | string | `string` |  | ✓ |  | Table Name |

#### 2.145.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-universal-table-200) | OK | Successfully set universal table |  | [schema](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-universal-table-200-schema) |
| [403](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-universal-table-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-universal-table-403-schema) |
| [500](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-universal-table-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-schema-schema-name-table-name-actions-universal-table-500-schema) |

#### 2.145.5 Responses


##### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-universal-table-200"></span> 200 - Successfully set universal table
Status: OK

###### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-universal-table-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-universal-table-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-universal-table-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-universal-table-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-schema-schema-name-table-name-actions-universal-table-500-schema"></span> Schema
   
  



### 2.146 <span id="post-api-clusters-cluster-name-send-vault-token"></span> Send Vault token to a specific cluster (*PostAPIClustersClusterNameSendVaultToken*)

```
POST /api/clusters/{clusterName}/send-vault-token
```

This endpoint sends the Vault token to the specified cluster via email.

#### 2.146.1 Consumes
  * application/json

#### 2.146.2 Produces
  * application/json

#### 2.146.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.146.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-send-vault-token-200) | OK | Vault token sent successfully |  | [schema](#post-api-clusters-cluster-name-send-vault-token-200-schema) |
| [403](#post-api-clusters-cluster-name-send-vault-token-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-send-vault-token-403-schema) |
| [500](#post-api-clusters-cluster-name-send-vault-token-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-send-vault-token-500-schema) |

#### 2.146.5 Responses


##### <span id="post-api-clusters-cluster-name-send-vault-token-200"></span> 200 - Vault token sent successfully
Status: OK

###### <span id="post-api-clusters-cluster-name-send-vault-token-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-send-vault-token-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-send-vault-token-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-send-vault-token-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-send-vault-token-500-schema"></span> Schema
   
  



### 2.147 <span id="post-api-clusters-cluster-name-servers-server-name-actions-pitr"></span> Perform a point-in-time recovery on a server (*PostAPIClustersClusterNameServersServerNameActionsPitr*)

```
POST /api/clusters/{clusterName}/servers/{serverName}/actions/pitr
```

Initiates a point-in-time recovery on a specified server within a cluster.

#### 2.147.1 Produces
  * application/json

#### 2.147.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |

#### 2.147.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-servers-server-name-actions-pitr-200) | OK | PITR initiated successfully |  | [schema](#post-api-clusters-cluster-name-servers-server-name-actions-pitr-200-schema) |
| [403](#post-api-clusters-cluster-name-servers-server-name-actions-pitr-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-servers-server-name-actions-pitr-403-schema) |
| [500](#post-api-clusters-cluster-name-servers-server-name-actions-pitr-500) | Internal Server Error | Cluster Not Found" or "Server Not Found" or "Decode error" or "PITR error |  | [schema](#post-api-clusters-cluster-name-servers-server-name-actions-pitr-500-schema) |

#### 2.147.4 Responses


##### <span id="post-api-clusters-cluster-name-servers-server-name-actions-pitr-200"></span> 200 - PITR initiated successfully
Status: OK

###### <span id="post-api-clusters-cluster-name-servers-server-name-actions-pitr-200-schema"></span> Schema
   
  

[ServerAPIResponse](#server-api-response)

##### <span id="post-api-clusters-cluster-name-servers-server-name-actions-pitr-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-servers-server-name-actions-pitr-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-servers-server-name-actions-pitr-500"></span> 500 - Cluster Not Found" or "Server Not Found" or "Decode error" or "PITR error
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-servers-server-name-actions-pitr-500-schema"></span> Schema
   
  



### 2.148 <span id="post-api-clusters-cluster-name-servers-server-name-server-port-write-log-task"></span> Write logs for a server (*PostAPIClustersClusterNameServersServerNameServerPortWriteLogTask*)

```
POST /api/clusters/{clusterName}/servers/{serverName}/{serverPort}/write-log/{task}
```

Writes logs for a specified server within a cluster.

#### 2.148.1 Produces
  * application/json

#### 2.148.2 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| serverName | `path` | string | `string` |  | ✓ |  | Server Name |
| serverPort | `path` | string | `string` |  | ✓ |  | Server Port |
| task | `path` | string | `string` |  | ✓ |  | Task |
| data | `body` | [ServerDecodedData](#server-decoded-data) | `models.ServerDecodedData` | | ✓ | | Log Data |

#### 2.148.3 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-servers-server-name-server-port-write-log-task-200) | OK | Message logged |  | [schema](#post-api-clusters-cluster-name-servers-server-name-server-port-write-log-task-200-schema) |
| [400](#post-api-clusters-cluster-name-servers-server-name-server-port-write-log-task-400) | Bad Request | Bad request: Task is not registered" or "Decode reading body" or "Decode body |  | [schema](#post-api-clusters-cluster-name-servers-server-name-server-port-write-log-task-400-schema) |
| [500](#post-api-clusters-cluster-name-servers-server-name-server-port-write-log-task-500) | Internal Server Error | No cluster" or "No server" or "Error decrypting data |  | [schema](#post-api-clusters-cluster-name-servers-server-name-server-port-write-log-task-500-schema) |

#### 2.148.4 Responses


##### <span id="post-api-clusters-cluster-name-servers-server-name-server-port-write-log-task-200"></span> 200 - Message logged
Status: OK

###### <span id="post-api-clusters-cluster-name-servers-server-name-server-port-write-log-task-200-schema"></span> Schema
   
  

[ServerAPIResponse](#server-api-response)

##### <span id="post-api-clusters-cluster-name-servers-server-name-server-port-write-log-task-400"></span> 400 - Bad request: Task is not registered" or "Decode reading body" or "Decode body
Status: Bad Request

###### <span id="post-api-clusters-cluster-name-servers-server-name-server-port-write-log-task-400-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-servers-server-name-server-port-write-log-task-500"></span> 500 - No cluster" or "No server" or "Error decrypting data
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-servers-server-name-server-port-write-log-task-500-schema"></span> Schema
   
  



### 2.149 <span id="post-api-clusters-cluster-name-services-actions-provision"></span> Provision services for a specific cluster (*PostAPIClustersClusterNameServicesActionsProvision*)

```
POST /api/clusters/{clusterName}/services/actions/provision
```

This endpoint provisions services for the specified cluster.

#### 2.149.1 Consumes
  * application/json

#### 2.149.2 Produces
  * application/json

#### 2.149.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.149.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-services-actions-provision-200) | OK | Successfully provisioned services |  | [schema](#post-api-clusters-cluster-name-services-actions-provision-200-schema) |
| [403](#post-api-clusters-cluster-name-services-actions-provision-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-services-actions-provision-403-schema) |
| [500](#post-api-clusters-cluster-name-services-actions-provision-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-services-actions-provision-500-schema) |

#### 2.149.5 Responses


##### <span id="post-api-clusters-cluster-name-services-actions-provision-200"></span> 200 - Successfully provisioned services
Status: OK

###### <span id="post-api-clusters-cluster-name-services-actions-provision-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-services-actions-provision-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-services-actions-provision-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-services-actions-provision-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-services-actions-provision-500-schema"></span> Schema
   
  



### 2.150 <span id="post-api-clusters-cluster-name-services-actions-unprovision"></span> Unprovision services for a specific cluster (*PostAPIClustersClusterNameServicesActionsUnprovision*)

```
POST /api/clusters/{clusterName}/services/actions/unprovision
```

This endpoint unprovisions services for the specified cluster.

#### 2.150.1 Consumes
  * application/json

#### 2.150.2 Produces
  * application/json

#### 2.150.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.150.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-services-actions-unprovision-200) | OK | Successfully unprovisioned services |  | [schema](#post-api-clusters-cluster-name-services-actions-unprovision-200-schema) |
| [403](#post-api-clusters-cluster-name-services-actions-unprovision-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-services-actions-unprovision-403-schema) |
| [500](#post-api-clusters-cluster-name-services-actions-unprovision-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-services-actions-unprovision-500-schema) |

#### 2.150.5 Responses


##### <span id="post-api-clusters-cluster-name-services-actions-unprovision-200"></span> 200 - Successfully unprovisioned services
Status: OK

###### <span id="post-api-clusters-cluster-name-services-actions-unprovision-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-services-actions-unprovision-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-services-actions-unprovision-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-services-actions-unprovision-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-services-actions-unprovision-500-schema"></span> Schema
   
  



### 2.151 <span id="post-api-clusters-cluster-name-settings-actions-add-db-tag-tag-value"></span> Add a tag to a specific cluster (*PostAPIClustersClusterNameSettingsActionsAddDbTagTagValue*)

```
POST /api/clusters/{clusterName}/settings/actions/add-db-tag/{tagValue}
```

This endpoint adds a tag to the specified cluster.

#### 2.151.1 Consumes
  * application/json

#### 2.151.2 Produces
  * application/json

#### 2.151.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| tagValue | `path` | string | `string` |  | ✓ |  | Tag Value |

#### 2.151.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-settings-actions-add-db-tag-tag-value-200) | OK | Tag added successfully |  | [schema](#post-api-clusters-cluster-name-settings-actions-add-db-tag-tag-value-200-schema) |
| [403](#post-api-clusters-cluster-name-settings-actions-add-db-tag-tag-value-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-settings-actions-add-db-tag-tag-value-403-schema) |
| [500](#post-api-clusters-cluster-name-settings-actions-add-db-tag-tag-value-500) | Internal Server Error | Cluster Not Found |  | [schema](#post-api-clusters-cluster-name-settings-actions-add-db-tag-tag-value-500-schema) |

#### 2.151.5 Responses


##### <span id="post-api-clusters-cluster-name-settings-actions-add-db-tag-tag-value-200"></span> 200 - Tag added successfully
Status: OK

###### <span id="post-api-clusters-cluster-name-settings-actions-add-db-tag-tag-value-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-add-db-tag-tag-value-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-settings-actions-add-db-tag-tag-value-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-add-db-tag-tag-value-500"></span> 500 - Cluster Not Found
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-settings-actions-add-db-tag-tag-value-500-schema"></span> Schema
   
  



### 2.152 <span id="post-api-clusters-cluster-name-settings-actions-add-proxy-tag-tag-value"></span> Add a proxy tag to a specific cluster (*PostAPIClustersClusterNameSettingsActionsAddProxyTagTagValue*)

```
POST /api/clusters/{clusterName}/settings/actions/add-proxy-tag/{tagValue}
```

This endpoint adds a proxy tag to the specified cluster.

#### 2.152.1 Consumes
  * application/json

#### 2.152.2 Produces
  * application/json

#### 2.152.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| tagValue | `path` | string | `string` |  | ✓ |  | Tag Value |

#### 2.152.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-settings-actions-add-proxy-tag-tag-value-200) | OK | Tag added successfully |  | [schema](#post-api-clusters-cluster-name-settings-actions-add-proxy-tag-tag-value-200-schema) |
| [403](#post-api-clusters-cluster-name-settings-actions-add-proxy-tag-tag-value-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-settings-actions-add-proxy-tag-tag-value-403-schema) |
| [500](#post-api-clusters-cluster-name-settings-actions-add-proxy-tag-tag-value-500) | Internal Server Error | Cluster Not Found |  | [schema](#post-api-clusters-cluster-name-settings-actions-add-proxy-tag-tag-value-500-schema) |

#### 2.152.5 Responses


##### <span id="post-api-clusters-cluster-name-settings-actions-add-proxy-tag-tag-value-200"></span> 200 - Tag added successfully
Status: OK

###### <span id="post-api-clusters-cluster-name-settings-actions-add-proxy-tag-tag-value-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-add-proxy-tag-tag-value-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-settings-actions-add-proxy-tag-tag-value-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-add-proxy-tag-tag-value-500"></span> 500 - Cluster Not Found
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-settings-actions-add-proxy-tag-tag-value-500-schema"></span> Schema
   
  



### 2.153 <span id="post-api-clusters-cluster-name-settings-actions-apply-dynamic-config"></span> Apply dynamic configuration for a specific cluster (*PostAPIClustersClusterNameSettingsActionsApplyDynamicConfig*)

```
POST /api/clusters/{clusterName}/settings/actions/apply-dynamic-config
```

This endpoint applies dynamic configuration for the specified cluster.

#### 2.153.1 Consumes
  * application/json

#### 2.153.2 Produces
  * application/json

#### 2.153.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.153.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-settings-actions-apply-dynamic-config-200) | OK | Successfully applied dynamic configuration |  | [schema](#post-api-clusters-cluster-name-settings-actions-apply-dynamic-config-200-schema) |
| [403](#post-api-clusters-cluster-name-settings-actions-apply-dynamic-config-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-settings-actions-apply-dynamic-config-403-schema) |
| [500](#post-api-clusters-cluster-name-settings-actions-apply-dynamic-config-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-settings-actions-apply-dynamic-config-500-schema) |

#### 2.153.5 Responses


##### <span id="post-api-clusters-cluster-name-settings-actions-apply-dynamic-config-200"></span> 200 - Successfully applied dynamic configuration
Status: OK

###### <span id="post-api-clusters-cluster-name-settings-actions-apply-dynamic-config-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-apply-dynamic-config-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-settings-actions-apply-dynamic-config-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-apply-dynamic-config-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-settings-actions-apply-dynamic-config-500-schema"></span> Schema
   
  



### 2.154 <span id="post-api-clusters-cluster-name-settings-actions-certificates-reload"></span> Reload client certificates for a specific cluster (*PostAPIClustersClusterNameSettingsActionsCertificatesReload*)

```
POST /api/clusters/{clusterName}/settings/actions/certificates-reload
```

This endpoint reloads the client certificates for the specified cluster.

#### 2.154.1 Consumes
  * application/json

#### 2.154.2 Produces
  * application/json

#### 2.154.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.154.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-settings-actions-certificates-reload-200) | OK | Successfully reloaded client certificates |  | [schema](#post-api-clusters-cluster-name-settings-actions-certificates-reload-200-schema) |
| [403](#post-api-clusters-cluster-name-settings-actions-certificates-reload-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-settings-actions-certificates-reload-403-schema) |
| [500](#post-api-clusters-cluster-name-settings-actions-certificates-reload-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-settings-actions-certificates-reload-500-schema) |

#### 2.154.5 Responses


##### <span id="post-api-clusters-cluster-name-settings-actions-certificates-reload-200"></span> 200 - Successfully reloaded client certificates
Status: OK

###### <span id="post-api-clusters-cluster-name-settings-actions-certificates-reload-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-certificates-reload-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-settings-actions-certificates-reload-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-certificates-reload-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-settings-actions-certificates-reload-500-schema"></span> Schema
   
  



### 2.155 <span id="post-api-clusters-cluster-name-settings-actions-discover"></span> Discover settings for a specific cluster (*PostAPIClustersClusterNameSettingsActionsDiscover*)

```
POST /api/clusters/{clusterName}/settings/actions/discover
```

This endpoint triggers the discovery of settings for the specified cluster.

#### 2.155.1 Consumes
  * application/json

#### 2.155.2 Produces
  * application/json

#### 2.155.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.155.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-settings-actions-discover-200) | OK | Successfully discovered settings |  | [schema](#post-api-clusters-cluster-name-settings-actions-discover-200-schema) |
| [403](#post-api-clusters-cluster-name-settings-actions-discover-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-settings-actions-discover-403-schema) |
| [500](#post-api-clusters-cluster-name-settings-actions-discover-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-settings-actions-discover-500-schema) |

#### 2.155.5 Responses


##### <span id="post-api-clusters-cluster-name-settings-actions-discover-200"></span> 200 - Successfully discovered settings
Status: OK

###### <span id="post-api-clusters-cluster-name-settings-actions-discover-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-discover-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-settings-actions-discover-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-discover-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-settings-actions-discover-500-schema"></span> Schema
   
  



### 2.156 <span id="post-api-clusters-cluster-name-settings-actions-drop-db-tag-tag-value"></span> Remove a tag from a specific cluster (*PostAPIClustersClusterNameSettingsActionsDropDbTagTagValue*)

```
POST /api/clusters/{clusterName}/settings/actions/drop-db-tag/{tagValue}
```

This endpoint removes a tag from the specified cluster.

#### 2.156.1 Consumes
  * application/json

#### 2.156.2 Produces
  * application/json

#### 2.156.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| tagValue | `path` | string | `string` |  | ✓ |  | Tag Value |

#### 2.156.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-settings-actions-drop-db-tag-tag-value-200) | OK | Tag removed successfully |  | [schema](#post-api-clusters-cluster-name-settings-actions-drop-db-tag-tag-value-200-schema) |
| [403](#post-api-clusters-cluster-name-settings-actions-drop-db-tag-tag-value-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-settings-actions-drop-db-tag-tag-value-403-schema) |
| [500](#post-api-clusters-cluster-name-settings-actions-drop-db-tag-tag-value-500) | Internal Server Error | Cluster Not Found |  | [schema](#post-api-clusters-cluster-name-settings-actions-drop-db-tag-tag-value-500-schema) |

#### 2.156.5 Responses


##### <span id="post-api-clusters-cluster-name-settings-actions-drop-db-tag-tag-value-200"></span> 200 - Tag removed successfully
Status: OK

###### <span id="post-api-clusters-cluster-name-settings-actions-drop-db-tag-tag-value-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-drop-db-tag-tag-value-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-settings-actions-drop-db-tag-tag-value-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-drop-db-tag-tag-value-500"></span> 500 - Cluster Not Found
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-settings-actions-drop-db-tag-tag-value-500-schema"></span> Schema
   
  



### 2.157 <span id="post-api-clusters-cluster-name-settings-actions-drop-proxy-tag-tag-value"></span> Remove a proxy tag from a specific cluster (*PostAPIClustersClusterNameSettingsActionsDropProxyTagTagValue*)

```
POST /api/clusters/{clusterName}/settings/actions/drop-proxy-tag/{tagValue}
```

This endpoint removes a proxy tag from the specified cluster.

#### 2.157.1 Consumes
  * application/json

#### 2.157.2 Produces
  * application/json

#### 2.157.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| tagValue | `path` | string | `string` |  | ✓ |  | Tag Value |

#### 2.157.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-settings-actions-drop-proxy-tag-tag-value-200) | OK | Tag removed successfully |  | [schema](#post-api-clusters-cluster-name-settings-actions-drop-proxy-tag-tag-value-200-schema) |
| [403](#post-api-clusters-cluster-name-settings-actions-drop-proxy-tag-tag-value-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-settings-actions-drop-proxy-tag-tag-value-403-schema) |
| [500](#post-api-clusters-cluster-name-settings-actions-drop-proxy-tag-tag-value-500) | Internal Server Error | Cluster Not Found |  | [schema](#post-api-clusters-cluster-name-settings-actions-drop-proxy-tag-tag-value-500-schema) |

#### 2.157.5 Responses


##### <span id="post-api-clusters-cluster-name-settings-actions-drop-proxy-tag-tag-value-200"></span> 200 - Tag removed successfully
Status: OK

###### <span id="post-api-clusters-cluster-name-settings-actions-drop-proxy-tag-tag-value-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-drop-proxy-tag-tag-value-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-settings-actions-drop-proxy-tag-tag-value-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-drop-proxy-tag-tag-value-500"></span> 500 - Cluster Not Found
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-settings-actions-drop-proxy-tag-tag-value-500-schema"></span> Schema
   
  



### 2.158 <span id="post-api-clusters-cluster-name-settings-actions-reload"></span> Reload cluster settings (*PostAPIClustersClusterNameSettingsActionsReload*)

```
POST /api/clusters/{clusterName}/settings/actions/reload
```

This endpoint reloads the settings for the specified cluster.

#### 2.158.1 Consumes
  * application/json

#### 2.158.2 Produces
  * application/json

#### 2.158.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.158.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-settings-actions-reload-200) | OK | Successfully reloaded settings |  | [schema](#post-api-clusters-cluster-name-settings-actions-reload-200-schema) |
| [500](#post-api-clusters-cluster-name-settings-actions-reload-500) | Internal Server Error | Cluster Not Found |  | [schema](#post-api-clusters-cluster-name-settings-actions-reload-500-schema) |

#### 2.158.5 Responses


##### <span id="post-api-clusters-cluster-name-settings-actions-reload-200"></span> 200 - Successfully reloaded settings
Status: OK

###### <span id="post-api-clusters-cluster-name-settings-actions-reload-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-reload-500"></span> 500 - Cluster Not Found
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-settings-actions-reload-500-schema"></span> Schema
   
  



### 2.159 <span id="post-api-clusters-cluster-name-settings-actions-reload-graphite-filterlist"></span> Reload Graphite filter list for a specific cluster (*PostAPIClustersClusterNameSettingsActionsReloadGraphiteFilterlist*)

```
POST /api/clusters/{clusterName}/settings/actions/reload-graphite-filterlist
```

This endpoint reloads the Graphite filter list for the specified cluster.

#### 2.159.1 Consumes
  * application/json

#### 2.159.2 Produces
  * application/json

#### 2.159.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.159.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-settings-actions-reload-graphite-filterlist-200) | OK | Successfully reloaded Graphite filter list |  | [schema](#post-api-clusters-cluster-name-settings-actions-reload-graphite-filterlist-200-schema) |
| [403](#post-api-clusters-cluster-name-settings-actions-reload-graphite-filterlist-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-settings-actions-reload-graphite-filterlist-403-schema) |
| [500](#post-api-clusters-cluster-name-settings-actions-reload-graphite-filterlist-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-settings-actions-reload-graphite-filterlist-500-schema) |

#### 2.159.5 Responses


##### <span id="post-api-clusters-cluster-name-settings-actions-reload-graphite-filterlist-200"></span> 200 - Successfully reloaded Graphite filter list
Status: OK

###### <span id="post-api-clusters-cluster-name-settings-actions-reload-graphite-filterlist-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-reload-graphite-filterlist-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-settings-actions-reload-graphite-filterlist-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-reload-graphite-filterlist-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-settings-actions-reload-graphite-filterlist-500-schema"></span> Schema
   
  



### 2.160 <span id="post-api-clusters-cluster-name-settings-actions-reset-graphite-filterlist-template"></span> Reset Graphite filter list for a specific cluster (*PostAPIClustersClusterNameSettingsActionsResetGraphiteFilterlistTemplate*)

```
POST /api/clusters/{clusterName}/settings/actions/reset-graphite-filterlist/{template}
```

This endpoint resets the Graphite filter list for the specified cluster.

#### 2.160.1 Consumes
  * application/json

#### 2.160.2 Produces
  * application/json

#### 2.160.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| template | `path` | string | `string` |  | ✓ |  | Template |

#### 2.160.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-settings-actions-reset-graphite-filterlist-template-200) | OK | Successfully reset Graphite filter list |  | [schema](#post-api-clusters-cluster-name-settings-actions-reset-graphite-filterlist-template-200-schema) |
| [403](#post-api-clusters-cluster-name-settings-actions-reset-graphite-filterlist-template-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-settings-actions-reset-graphite-filterlist-template-403-schema) |
| [500](#post-api-clusters-cluster-name-settings-actions-reset-graphite-filterlist-template-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-settings-actions-reset-graphite-filterlist-template-500-schema) |

#### 2.160.5 Responses


##### <span id="post-api-clusters-cluster-name-settings-actions-reset-graphite-filterlist-template-200"></span> 200 - Successfully reset Graphite filter list
Status: OK

###### <span id="post-api-clusters-cluster-name-settings-actions-reset-graphite-filterlist-template-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-reset-graphite-filterlist-template-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-settings-actions-reset-graphite-filterlist-template-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-reset-graphite-filterlist-template-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-settings-actions-reset-graphite-filterlist-template-500-schema"></span> Schema
   
  



### 2.161 <span id="post-api-clusters-cluster-name-settings-actions-set-cron-setting-name-setting-value"></span> Set cron jobs for a specific cluster (*PostAPIClustersClusterNameSettingsActionsSetCronSettingNameSettingValue*)

```
POST /api/clusters/{clusterName}/settings/actions/set-cron/{settingName}/{settingValue}
```

This endpoint sets the cron jobs for the specified cluster.

#### 2.161.1 Consumes
  * application/json

#### 2.161.2 Produces
  * application/json

#### 2.161.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| settingName | `path` | string | `string` |  | ✓ |  | Setting Name |
| settingValue | `path` | string | `string` |  | ✓ |  | Setting Value |

#### 2.161.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-settings-actions-set-cron-setting-name-setting-value-200) | OK | Successfully set cron job |  | [schema](#post-api-clusters-cluster-name-settings-actions-set-cron-setting-name-setting-value-200-schema) |
| [403](#post-api-clusters-cluster-name-settings-actions-set-cron-setting-name-setting-value-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-settings-actions-set-cron-setting-name-setting-value-403-schema) |
| [500](#post-api-clusters-cluster-name-settings-actions-set-cron-setting-name-setting-value-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-settings-actions-set-cron-setting-name-setting-value-500-schema) |

#### 2.161.5 Responses


##### <span id="post-api-clusters-cluster-name-settings-actions-set-cron-setting-name-setting-value-200"></span> 200 - Successfully set cron job
Status: OK

###### <span id="post-api-clusters-cluster-name-settings-actions-set-cron-setting-name-setting-value-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-set-cron-setting-name-setting-value-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-settings-actions-set-cron-setting-name-setting-value-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-set-cron-setting-name-setting-value-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-settings-actions-set-cron-setting-name-setting-value-500-schema"></span> Schema
   
  



### 2.162 <span id="post-api-clusters-cluster-name-settings-actions-set-graphite-filterlist-filter-type"></span> Set Graphite filter list for a specific cluster (*PostAPIClustersClusterNameSettingsActionsSetGraphiteFilterlistFilterType*)

```
POST /api/clusters/{clusterName}/settings/actions/set-graphite-filterlist/{filterType}
```

This endpoint sets the Graphite filter list for the specified cluster.

#### 2.162.1 Consumes
  * application/json

#### 2.162.2 Produces
  * application/json

#### 2.162.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| filterType | `path` | string | `string` |  | ✓ |  | Filter Type |
| body | `body` | [ClusterGraphiteFilterList](#cluster-graphite-filter-list) | `models.ClusterGraphiteFilterList` | | ✓ | | Graphite Filter List |

#### 2.162.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-settings-actions-set-graphite-filterlist-filter-type-200) | OK | Filterlist updated |  | [schema](#post-api-clusters-cluster-name-settings-actions-set-graphite-filterlist-filter-type-200-schema) |
| [403](#post-api-clusters-cluster-name-settings-actions-set-graphite-filterlist-filter-type-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-settings-actions-set-graphite-filterlist-filter-type-403-schema) |
| [500](#post-api-clusters-cluster-name-settings-actions-set-graphite-filterlist-filter-type-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-settings-actions-set-graphite-filterlist-filter-type-500-schema) |

#### 2.162.5 Responses


##### <span id="post-api-clusters-cluster-name-settings-actions-set-graphite-filterlist-filter-type-200"></span> 200 - Filterlist updated
Status: OK

###### <span id="post-api-clusters-cluster-name-settings-actions-set-graphite-filterlist-filter-type-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-set-graphite-filterlist-filter-type-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-settings-actions-set-graphite-filterlist-filter-type-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-set-graphite-filterlist-filter-type-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-settings-actions-set-graphite-filterlist-filter-type-500-schema"></span> Schema
   
  



### 2.163 <span id="post-api-clusters-cluster-name-settings-actions-set-setting-name-setting-value"></span> Set settings for a specific cluster (*PostAPIClustersClusterNameSettingsActionsSetSettingNameSettingValue*)

```
POST /api/clusters/{clusterName}/settings/actions/set/{settingName}/{settingValue}
```

This endpoint sets the settings for the specified cluster.

#### 2.163.1 Consumes
  * application/json

#### 2.163.2 Produces
  * application/json

#### 2.163.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| settingName | `path` | string | `string` |  | ✓ |  | Setting Name |
| settingValue | `path` | string | `string` |  | ✓ |  | Setting Value |

#### 2.163.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-settings-actions-set-setting-name-setting-value-200) | OK | Successfully set setting |  | [schema](#post-api-clusters-cluster-name-settings-actions-set-setting-name-setting-value-200-schema) |
| [403](#post-api-clusters-cluster-name-settings-actions-set-setting-name-setting-value-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-settings-actions-set-setting-name-setting-value-403-schema) |
| [500](#post-api-clusters-cluster-name-settings-actions-set-setting-name-setting-value-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-settings-actions-set-setting-name-setting-value-500-schema) |

#### 2.163.5 Responses


##### <span id="post-api-clusters-cluster-name-settings-actions-set-setting-name-setting-value-200"></span> 200 - Successfully set setting
Status: OK

###### <span id="post-api-clusters-cluster-name-settings-actions-set-setting-name-setting-value-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-set-setting-name-setting-value-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-settings-actions-set-setting-name-setting-value-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-set-setting-name-setting-value-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-settings-actions-set-setting-name-setting-value-500-schema"></span> Schema
   
  



### 2.164 <span id="post-api-clusters-cluster-name-settings-actions-switch-setting-name"></span> Switch settings for a specific cluster (*PostAPIClustersClusterNameSettingsActionsSwitchSettingName*)

```
POST /api/clusters/{clusterName}/settings/actions/switch/{settingName}
```

This endpoint switches the settings for the specified cluster.

#### 2.164.1 Consumes
  * application/json

#### 2.164.2 Produces
  * application/json

#### 2.164.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| settingName | `path` | string | `string` |  | ✓ |  | Setting Name |

#### 2.164.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-settings-actions-switch-setting-name-200) | OK | Successfully switched setting |  | [schema](#post-api-clusters-cluster-name-settings-actions-switch-setting-name-200-schema) |
| [403](#post-api-clusters-cluster-name-settings-actions-switch-setting-name-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-settings-actions-switch-setting-name-403-schema) |
| [500](#post-api-clusters-cluster-name-settings-actions-switch-setting-name-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-cluster-name-settings-actions-switch-setting-name-500-schema) |

#### 2.164.5 Responses


##### <span id="post-api-clusters-cluster-name-settings-actions-switch-setting-name-200"></span> 200 - Successfully switched setting
Status: OK

###### <span id="post-api-clusters-cluster-name-settings-actions-switch-setting-name-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-switch-setting-name-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-settings-actions-switch-setting-name-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-settings-actions-switch-setting-name-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-settings-actions-switch-setting-name-500-schema"></span> Schema
   
  



### 2.165 <span id="post-api-clusters-cluster-name-subscribe"></span> Subscribe a user to a cluster (*PostAPIClustersClusterNameSubscribe*)

```
POST /api/clusters/{clusterName}/subscribe
```

This endpoint allows a user to subscribe to a specified cluster.

#### 2.165.1 Consumes
  * application/json

#### 2.165.2 Produces
  * application/json

#### 2.165.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| userform | `body` | [ClusterUserForm](#cluster-user-form) | `models.ClusterUserForm` | | ✓ | | User Form |

#### 2.165.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-subscribe-200) | OK | Email sent to admin! |  | [schema](#post-api-clusters-cluster-name-subscribe-200-schema) |
| [400](#post-api-clusters-cluster-name-subscribe-400) | Bad Request | Error in request |  | [schema](#post-api-clusters-cluster-name-subscribe-400-schema) |
| [401](#post-api-clusters-cluster-name-subscribe-401) | Unauthorized | Error logging in to gitlab: Token credentials is not valid |  | [schema](#post-api-clusters-cluster-name-subscribe-401-schema) |
| [403](#post-api-clusters-cluster-name-subscribe-403) | Forbidden | Error parsing JWT" / "Current user is not logged in via Gitlab! |  | [schema](#post-api-clusters-cluster-name-subscribe-403-schema) |
| [409](#post-api-clusters-cluster-name-subscribe-409) | Conflict | User already subscribed on peer cluster!" / "Another user already subscribed on peer cluster! |  | [schema](#post-api-clusters-cluster-name-subscribe-409-schema) |
| [500](#post-api-clusters-cluster-name-subscribe-500) | Internal Server Error | No valid cluster" / "Peer does not have cloud18 setup!" / "Error sending email |  | [schema](#post-api-clusters-cluster-name-subscribe-500-schema) |

#### 2.165.5 Responses


##### <span id="post-api-clusters-cluster-name-subscribe-200"></span> 200 - Email sent to admin!
Status: OK

###### <span id="post-api-clusters-cluster-name-subscribe-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-subscribe-400"></span> 400 - Error in request
Status: Bad Request

###### <span id="post-api-clusters-cluster-name-subscribe-400-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-subscribe-401"></span> 401 - Error logging in to gitlab: Token credentials is not valid
Status: Unauthorized

###### <span id="post-api-clusters-cluster-name-subscribe-401-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-subscribe-403"></span> 403 - Error parsing JWT" / "Current user is not logged in via Gitlab!
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-subscribe-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-subscribe-409"></span> 409 - User already subscribed on peer cluster!" / "Another user already subscribed on peer cluster!
Status: Conflict

###### <span id="post-api-clusters-cluster-name-subscribe-409-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-subscribe-500"></span> 500 - No valid cluster" / "Peer does not have cloud18 setup!" / "Error sending email
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-subscribe-500-schema"></span> Schema
   
  



### 2.166 <span id="post-api-clusters-cluster-name-tests-actions-run-all"></span> Run all tests for a given cluster (*PostAPIClustersClusterNameTestsActionsRunAll*)

```
POST /api/clusters/{clusterName}/tests/actions/run/all
```

This endpoint runs all tests for the specified cluster.

#### 2.166.1 Consumes
  * application/json

#### 2.166.2 Produces
  * application/json

#### 2.166.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |

#### 2.166.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-tests-actions-run-all-200) | OK | List of test results |  | [schema](#post-api-clusters-cluster-name-tests-actions-run-all-200-schema) |
| [403](#post-api-clusters-cluster-name-tests-actions-run-all-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-tests-actions-run-all-403-schema) |
| [500](#post-api-clusters-cluster-name-tests-actions-run-all-500) | Internal Server Error | Cluster Not Found |  | [schema](#post-api-clusters-cluster-name-tests-actions-run-all-500-schema) |

#### 2.166.5 Responses


##### <span id="post-api-clusters-cluster-name-tests-actions-run-all-200"></span> 200 - List of test results
Status: OK

###### <span id="post-api-clusters-cluster-name-tests-actions-run-all-200-schema"></span> Schema
   
  

[][ClusterTest](#cluster-test)

##### <span id="post-api-clusters-cluster-name-tests-actions-run-all-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-tests-actions-run-all-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-tests-actions-run-all-500"></span> 500 - Cluster Not Found
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-tests-actions-run-all-500-schema"></span> Schema
   
  



### 2.167 <span id="post-api-clusters-cluster-name-tests-actions-run-test-name"></span> Run a specific test for a given cluster (*PostAPIClustersClusterNameTestsActionsRunTestName*)

```
POST /api/clusters/{clusterName}/tests/actions/run/{testName}
```

This endpoint runs a specific test for the specified cluster.

#### 2.167.1 Consumes
  * application/json

#### 2.167.2 Produces
  * application/json

#### 2.167.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| testName | `path` | string | `string` |  | ✓ |  | Test Name |
| provision | `formData` | string | `string` |  |  |  | Provision the cluster before running the test |
| unprovision | `formData` | string | `string` |  |  |  | Unprovision the cluster after running the test |

#### 2.167.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-tests-actions-run-test-name-200) | OK | Test result |  | [schema](#post-api-clusters-cluster-name-tests-actions-run-test-name-200-schema) |
| [403](#post-api-clusters-cluster-name-tests-actions-run-test-name-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-tests-actions-run-test-name-403-schema) |
| [500](#post-api-clusters-cluster-name-tests-actions-run-test-name-500) | Internal Server Error | Cluster Not Found |  | [schema](#post-api-clusters-cluster-name-tests-actions-run-test-name-500-schema) |

#### 2.167.5 Responses


##### <span id="post-api-clusters-cluster-name-tests-actions-run-test-name-200"></span> 200 - Test result
Status: OK

###### <span id="post-api-clusters-cluster-name-tests-actions-run-test-name-200-schema"></span> Schema
   
  

[ClusterTest](#cluster-test)

##### <span id="post-api-clusters-cluster-name-tests-actions-run-test-name-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-tests-actions-run-test-name-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-tests-actions-run-test-name-500"></span> 500 - Cluster Not Found
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-tests-actions-run-test-name-500-schema"></span> Schema
   
  



### 2.168 <span id="post-api-clusters-cluster-name-users-add"></span> Add a new user to a cluster (*PostAPIClustersClusterNameUsersAdd*)

```
POST /api/clusters/{clusterName}/users/add
```

Adds a new user to the specified cluster if the request is valid and the user has the necessary permissions.

#### 2.168.1 Consumes
  * application/json

#### 2.168.2 Produces
  * application/json

#### 2.168.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| userform | `body` | [ClusterUserForm](#cluster-user-form) | `models.ClusterUserForm` | | ✓ | | User Form |

#### 2.168.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-users-add-200) | OK | User added successfully |  | [schema](#post-api-clusters-cluster-name-users-add-200-schema) |
| [400](#post-api-clusters-cluster-name-users-add-400) | Bad Request | Error in request |  | [schema](#post-api-clusters-cluster-name-users-add-400-schema) |
| [403](#post-api-clusters-cluster-name-users-add-403) | Forbidden | No Valid ACL |  | [schema](#post-api-clusters-cluster-name-users-add-403-schema) |
| [500](#post-api-clusters-cluster-name-users-add-500) | Internal Server Error | Error adding new user" or "No valid cluster |  | [schema](#post-api-clusters-cluster-name-users-add-500-schema) |

#### 2.168.5 Responses


##### <span id="post-api-clusters-cluster-name-users-add-200"></span> 200 - User added successfully
Status: OK

###### <span id="post-api-clusters-cluster-name-users-add-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-users-add-400"></span> 400 - Error in request
Status: Bad Request

###### <span id="post-api-clusters-cluster-name-users-add-400-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-users-add-403"></span> 403 - No Valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-users-add-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-users-add-500"></span> 500 - Error adding new user" or "No valid cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-users-add-500-schema"></span> Schema
   
  



### 2.169 <span id="post-api-clusters-cluster-name-users-drop"></span> Drop a cluster user (*PostAPIClustersClusterNameUsersDrop*)

```
POST /api/clusters/{clusterName}/users/drop
```

Drops a user from the specified cluster.

#### 2.169.1 Consumes
  * application/json

#### 2.169.2 Produces
  * application/json

#### 2.169.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| userform | `body` | [ClusterUserForm](#cluster-user-form) | `models.ClusterUserForm` | | ✓ | | User Form |

#### 2.169.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-users-drop-200) | OK | User dropped successfully |  | [schema](#post-api-clusters-cluster-name-users-drop-200-schema) |
| [400](#post-api-clusters-cluster-name-users-drop-400) | Bad Request | Error in request |  | [schema](#post-api-clusters-cluster-name-users-drop-400-schema) |
| [403](#post-api-clusters-cluster-name-users-drop-403) | Forbidden | No Valid ACL |  | [schema](#post-api-clusters-cluster-name-users-drop-403-schema) |
| [500](#post-api-clusters-cluster-name-users-drop-500) | Internal Server Error | Error dropping user" or "No valid cluster |  | [schema](#post-api-clusters-cluster-name-users-drop-500-schema) |

#### 2.169.5 Responses


##### <span id="post-api-clusters-cluster-name-users-drop-200"></span> 200 - User dropped successfully
Status: OK

###### <span id="post-api-clusters-cluster-name-users-drop-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-users-drop-400"></span> 400 - Error in request
Status: Bad Request

###### <span id="post-api-clusters-cluster-name-users-drop-400-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-users-drop-403"></span> 403 - No Valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-users-drop-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-users-drop-500"></span> 500 - Error dropping user" or "No valid cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-users-drop-500-schema"></span> Schema
   
  



### 2.170 <span id="post-api-clusters-cluster-name-users-send-credentials"></span> Send credentials to a specific user (*PostAPIClustersClusterNameUsersSendCredentials*)

```
POST /api/clusters/{clusterName}/users/send-credentials
```

This endpoint sends the credentials to the specified user via email.

#### 2.170.1 Consumes
  * application/json

#### 2.170.2 Produces
  * application/json

#### 2.170.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| body | `body` | [ServerCredentialMailForm](#server-credential-mail-form) | `models.ServerCredentialMailForm` | | ✓ | | Credential Mail Form |

#### 2.170.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-users-send-credentials-200) | OK | Credentials sent to user! |  | [schema](#post-api-clusters-cluster-name-users-send-credentials-200-schema) |
| [403](#post-api-clusters-cluster-name-users-send-credentials-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-cluster-name-users-send-credentials-403-schema) |
| [500](#post-api-clusters-cluster-name-users-send-credentials-500) | Internal Server Error | Error sending email |  | [schema](#post-api-clusters-cluster-name-users-send-credentials-500-schema) |

#### 2.170.5 Responses


##### <span id="post-api-clusters-cluster-name-users-send-credentials-200"></span> 200 - Credentials sent to user!
Status: OK

###### <span id="post-api-clusters-cluster-name-users-send-credentials-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-users-send-credentials-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-users-send-credentials-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-users-send-credentials-500"></span> 500 - Error sending email
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-users-send-credentials-500-schema"></span> Schema
   
  



### 2.171 <span id="post-api-clusters-cluster-name-users-update"></span> Update a cluster user (*PostAPIClustersClusterNameUsersUpdate*)

```
POST /api/clusters/{clusterName}/users/update
```

Updates the user information for a specified cluster.

#### 2.171.1 Consumes
  * application/json

#### 2.171.2 Produces
  * application/json

#### 2.171.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| userform | `body` | [ClusterUserForm](#cluster-user-form) | `models.ClusterUserForm` | | ✓ | | User Form |

#### 2.171.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-cluster-name-users-update-200) | OK | User updated successfully |  | [schema](#post-api-clusters-cluster-name-users-update-200-schema) |
| [400](#post-api-clusters-cluster-name-users-update-400) | Bad Request | Error in request |  | [schema](#post-api-clusters-cluster-name-users-update-400-schema) |
| [403](#post-api-clusters-cluster-name-users-update-403) | Forbidden | No Valid ACL |  | [schema](#post-api-clusters-cluster-name-users-update-403-schema) |
| [500](#post-api-clusters-cluster-name-users-update-500) | Internal Server Error | Error updating user" or "No valid cluster |  | [schema](#post-api-clusters-cluster-name-users-update-500-schema) |

#### 2.171.5 Responses


##### <span id="post-api-clusters-cluster-name-users-update-200"></span> 200 - User updated successfully
Status: OK

###### <span id="post-api-clusters-cluster-name-users-update-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-users-update-400"></span> 400 - Error in request
Status: Bad Request

###### <span id="post-api-clusters-cluster-name-users-update-400-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-users-update-403"></span> 403 - No Valid ACL
Status: Forbidden

###### <span id="post-api-clusters-cluster-name-users-update-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-cluster-name-users-update-500"></span> 500 - Error updating user" or "No valid cluster
Status: Internal Server Error

###### <span id="post-api-clusters-cluster-name-users-update-500-schema"></span> Schema
   
  



### 2.172 <span id="post-api-clusters-settings-actions-reload-clusters-plans"></span> Reload cluster plans (*PostAPIClustersSettingsActionsReloadClustersPlans*)

```
POST /api/clusters/settings/actions/reload-clusters-plans
```

This endpoint reloads the cluster plans for all clusters.

#### 2.172.1 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-settings-actions-reload-clusters-plans-200) | OK | Successfully reloaded plans |  | [schema](#post-api-clusters-settings-actions-reload-clusters-plans-200-schema) |
| [403](#post-api-clusters-settings-actions-reload-clusters-plans-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-settings-actions-reload-clusters-plans-403-schema) |
| [500](#post-api-clusters-settings-actions-reload-clusters-plans-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-settings-actions-reload-clusters-plans-500-schema) |

#### 2.172.2 Responses


##### <span id="post-api-clusters-settings-actions-reload-clusters-plans-200"></span> 200 - Successfully reloaded plans
Status: OK

###### <span id="post-api-clusters-settings-actions-reload-clusters-plans-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-settings-actions-reload-clusters-plans-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-settings-actions-reload-clusters-plans-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-settings-actions-reload-clusters-plans-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-settings-actions-reload-clusters-plans-500-schema"></span> Schema
   
  



### 2.173 <span id="post-api-clusters-settings-actions-set-setting-name-setting-value"></span> Set global settings for the server (*PostAPIClustersSettingsActionsSetSettingNameSettingValue*)

```
POST /api/clusters/settings/actions/set/{settingName}/{settingValue}
```

This endpoint sets the global settings for the server.

#### 2.173.1 Consumes
  * application/json

#### 2.173.2 Produces
  * application/json

#### 2.173.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  |  |  | Cluster Name |
| settingName | `path` | string | `string` |  | ✓ |  | Setting Name |
| settingValue | `path` | string | `string` |  | ✓ |  | Setting Value |

#### 2.173.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-settings-actions-set-setting-name-setting-value-200) | OK | Successfully set setting |  | [schema](#post-api-clusters-settings-actions-set-setting-name-setting-value-200-schema) |
| [403](#post-api-clusters-settings-actions-set-setting-name-setting-value-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-settings-actions-set-setting-name-setting-value-403-schema) |
| [500](#post-api-clusters-settings-actions-set-setting-name-setting-value-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-settings-actions-set-setting-name-setting-value-500-schema) |

#### 2.173.5 Responses


##### <span id="post-api-clusters-settings-actions-set-setting-name-setting-value-200"></span> 200 - Successfully set setting
Status: OK

###### <span id="post-api-clusters-settings-actions-set-setting-name-setting-value-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-settings-actions-set-setting-name-setting-value-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-settings-actions-set-setting-name-setting-value-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-settings-actions-set-setting-name-setting-value-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-settings-actions-set-setting-name-setting-value-500-schema"></span> Schema
   
  



### 2.174 <span id="post-api-clusters-settings-actions-switch-setting-name"></span> Switch global settings for the server (*PostAPIClustersSettingsActionsSwitchSettingName*)

```
POST /api/clusters/settings/actions/switch/{settingName}
```

This endpoint switches the global settings for the server.

#### 2.174.1 Consumes
  * application/json

#### 2.174.2 Produces
  * application/json

#### 2.174.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  |  |  | Cluster Name |
| settingName | `path` | string | `string` |  | ✓ |  | Setting Name |

#### 2.174.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-clusters-settings-actions-switch-setting-name-200) | OK | Successfully switched setting |  | [schema](#post-api-clusters-settings-actions-switch-setting-name-200-schema) |
| [403](#post-api-clusters-settings-actions-switch-setting-name-403) | Forbidden | No valid ACL |  | [schema](#post-api-clusters-settings-actions-switch-setting-name-403-schema) |
| [500](#post-api-clusters-settings-actions-switch-setting-name-500) | Internal Server Error | No cluster |  | [schema](#post-api-clusters-settings-actions-switch-setting-name-500-schema) |

#### 2.174.5 Responses


##### <span id="post-api-clusters-settings-actions-switch-setting-name-200"></span> 200 - Successfully switched setting
Status: OK

###### <span id="post-api-clusters-settings-actions-switch-setting-name-200-schema"></span> Schema
   
  



##### <span id="post-api-clusters-settings-actions-switch-setting-name-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-api-clusters-settings-actions-switch-setting-name-403-schema"></span> Schema
   
  



##### <span id="post-api-clusters-settings-actions-switch-setting-name-500"></span> 500 - No cluster
Status: Internal Server Error

###### <span id="post-api-clusters-settings-actions-switch-setting-name-500-schema"></span> Schema
   
  



### 2.175 <span id="post-api-login"></span> User login (*PostAPILogin*)

```
POST /api/login
```

Authenticates a user and returns a JWT token upon successful login.

#### 2.175.1 Consumes
  * application/json

#### 2.175.2 Produces
  * application/json

#### 2.175.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| userCredentials | `body` | [ServerUserCredentials](#server-user-credentials) | `models.ServerUserCredentials` | | ✓ | | User credentials |

#### 2.175.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-api-login-200) | OK | JWT token |  | [schema](#post-api-login-200-schema) |
| [401](#post-api-login-401) | Unauthorized | Invalid credentials |  | [schema](#post-api-login-401-schema) |
| [403](#post-api-login-403) | Forbidden | Error in request |  | [schema](#post-api-login-403-schema) |
| [429](#post-api-login-429) | Too Many Requests | Too many requests |  | [schema](#post-api-login-429-schema) |
| [500](#post-api-login-500) | Internal Server Error | Error signing token |  | [schema](#post-api-login-500-schema) |

#### 2.175.5 Responses


##### <span id="post-api-login-200"></span> 200 - JWT token
Status: OK

###### <span id="post-api-login-200-schema"></span> Schema
   
  

[ServerToken](#server-token)

##### <span id="post-api-login-401"></span> 401 - Invalid credentials
Status: Unauthorized

###### <span id="post-api-login-401-schema"></span> Schema
   
  



##### <span id="post-api-login-403"></span> 403 - Error in request
Status: Forbidden

###### <span id="post-api-login-403-schema"></span> Schema
   
  



##### <span id="post-api-login-429"></span> 429 - Too many requests
Status: Too Many Requests

###### <span id="post-api-login-429-schema"></span> Schema
   
  



##### <span id="post-api-login-500"></span> 500 - Error signing token
Status: Internal Server Error

###### <span id="post-api-login-500-schema"></span> Schema
   
  



### 2.176 <span id="post-cluster-cluster-name-actions-dropserver-host-port"></span> Drop a server monitor from a cluster (*PostClusterClusterNameActionsDropserverHostPort*)

```
POST /cluster/{clusterName}/actions/dropserver/{host}/{port}
```

This endpoint allows dropping a server monitor or proxy monitor from a specified cluster.

#### 2.176.1 Consumes
  * application/json

#### 2.176.2 Produces
  * application/json

#### 2.176.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| host | `path` | string | `string` |  | ✓ |  | Host |
| port | `path` | string | `string` |  | ✓ |  | Port |

#### 2.176.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-cluster-cluster-name-actions-dropserver-host-port-200) | OK | Monitor dropped successfully |  | [schema](#post-cluster-cluster-name-actions-dropserver-host-port-200-schema) |
| [403](#post-cluster-cluster-name-actions-dropserver-host-port-403) | Forbidden | No valid ACL |  | [schema](#post-cluster-cluster-name-actions-dropserver-host-port-403-schema) |
| [500](#post-cluster-cluster-name-actions-dropserver-host-port-500) | Internal Server Error | Cluster Not Found |  | [schema](#post-cluster-cluster-name-actions-dropserver-host-port-500-schema) |

#### 2.176.5 Responses


##### <span id="post-cluster-cluster-name-actions-dropserver-host-port-200"></span> 200 - Monitor dropped successfully
Status: OK

###### <span id="post-cluster-cluster-name-actions-dropserver-host-port-200-schema"></span> Schema
   
  



##### <span id="post-cluster-cluster-name-actions-dropserver-host-port-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-cluster-cluster-name-actions-dropserver-host-port-403-schema"></span> Schema
   
  



##### <span id="post-cluster-cluster-name-actions-dropserver-host-port-500"></span> 500 - Cluster Not Found
Status: Internal Server Error

###### <span id="post-cluster-cluster-name-actions-dropserver-host-port-500-schema"></span> Schema
   
  



### 2.177 <span id="post-cluster-cluster-name-actions-dropserver-host-port-type"></span> Drop a server monitor from a cluster (*PostClusterClusterNameActionsDropserverHostPortType*)

```
POST /cluster/{clusterName}/actions/dropserver/{host}/{port}/{type}
```

This endpoint allows dropping a server monitor or proxy monitor from a specified cluster.

#### 2.177.1 Consumes
  * application/json

#### 2.177.2 Produces
  * application/json

#### 2.177.3 Parameters

| Name | Source | Type | Go type | Separator | Required | Default | Description |
|------|--------|------|---------|-----------| :------: |---------|-------------|
| clusterName | `path` | string | `string` |  | ✓ |  | Cluster Name |
| host | `path` | string | `string` |  | ✓ |  | Host |
| port | `path` | string | `string` |  | ✓ |  | Port |
| type | `path` | string | `string` |  |  |  | Monitor Type (proxy or database) |

#### 2.177.4 All responses
| Code | Status | Description | Has headers | Schema |
|------|--------|-------------|:-----------:|--------|
| [200](#post-cluster-cluster-name-actions-dropserver-host-port-type-200) | OK | Monitor dropped successfully |  | [schema](#post-cluster-cluster-name-actions-dropserver-host-port-type-200-schema) |
| [403](#post-cluster-cluster-name-actions-dropserver-host-port-type-403) | Forbidden | No valid ACL |  | [schema](#post-cluster-cluster-name-actions-dropserver-host-port-type-403-schema) |
| [500](#post-cluster-cluster-name-actions-dropserver-host-port-type-500) | Internal Server Error | Cluster Not Found |  | [schema](#post-cluster-cluster-name-actions-dropserver-host-port-type-500-schema) |

#### 2.177.5 Responses


##### <span id="post-cluster-cluster-name-actions-dropserver-host-port-type-200"></span> 200 - Monitor dropped successfully
Status: OK

###### <span id="post-cluster-cluster-name-actions-dropserver-host-port-type-200-schema"></span> Schema
   
  



##### <span id="post-cluster-cluster-name-actions-dropserver-host-port-type-403"></span> 403 - No valid ACL
Status: Forbidden

###### <span id="post-cluster-cluster-name-actions-dropserver-host-port-type-403-schema"></span> Schema
   
  



##### <span id="post-cluster-cluster-name-actions-dropserver-host-port-type-500"></span> 500 - Cluster Not Found
Status: Internal Server Error

###### <span id="post-cluster-cluster-name-actions-dropserver-host-port-type-500-schema"></span> Schema
   
  



## 3. Models

### 3.1 <span id="cluster-api-user"></span> cluster.APIUser


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| grants | map of boolean| `map[string]bool` |  | |  |  |
| roles | map of boolean| `map[string]bool` |  | |  |  |
| user | string| `string` |  | |  |  |



### 3.2 <span id="cluster-agent"></span> cluster.Agent


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| cpuCores | integer| `int64` |  | |  |  |
| cpuFreq | integer| `int64` |  | |  |  |
| hostName | string| `string` |  | |  |  |
| id | string| `string` |  | |  |  |
| memBytes | integer| `int64` |  | |  |  |
| memFreeBytes | integer| `int64` |  | |  |  |
| osKernel | string| `string` |  | |  |  |
| osName | string| `string` |  | |  |  |
| status | string| `string` |  | |  |  |
| version | string| `string` |  | |  |  |



### 3.3 <span id="cluster-alerts"></span> cluster.Alerts


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| errors | [][StateStateHTTP](#state-state-http)| `[]*StateStateHTTP` |  | |  |  |
| warnings | [][StateStateHTTP](#state-state-http)| `[]*StateStateHTTP` |  | |  |  |



### 3.4 <span id="cluster-cluster"></span> cluster.Cluster


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| activePassiveStatus | string| `string` |  | |  |  |
| agents | [][ClusterAgent](#cluster-agent)| `[]*ClusterAgent` |  | |  |  |
| apiUsers | map of [ClusterAPIUser](#cluster-api-user)| `map[string]ClusterAPIUser` |  | |  |  |
| backupList | [ConfigBackupMetaMap](#config-backup-meta-map)| `ConfigBackupMetaMap` |  | |  |  |
| backupStat | [Repmanv3BackupStat](#repmanv3-backup-stat)| `Repmanv3BackupStat` |  | |  |  |
| blacklist | [][RegexpRegexp](#regexp-regexp)| `[]RegexpRegexp` |  | |  |  |
| canConnectVault | boolean| `bool` |  | |  |  |
| canInitNodes | boolean| `bool` |  | |  |  |
| cleanReplication | boolean| `bool` |  | | used in testing |  |
| config | [GithubComSignal18ReplicationManagerConfigConfig](#github-com-signal18-replication-manager-config-config)| `GithubComSignal18ReplicationManagerConfigConfig` |  | |  |  |
| configurator | [ConfiguratorConfigurator](#configurator-configurator)| `ConfiguratorConfigurator` |  | |  |  |
| dbServers | []string| `[]string` |  | |  |  |
| dbServersCrashes | [][ClusterCrash](#cluster-crash)| `[]*ClusterCrash` |  | | This will be purged on all db node up |  |
| diffVariables | [][ClusterVariableDiff](#cluster-variable-diff)| `[]*ClusterVariableDiff` |  | |  |  |
| diskType | map of string| `map[string]string` |  | |  |  |
| failoverCounter | integer| `int64` |  | |  |  |
| failoverHistory | [][ClusterCrash](#cluster-crash)| `[]*ClusterCrash` |  | | This will be used for PITR |  |
| failoverLastTime | integer| `int64` |  | |  |  |
| fsType | map of boolean| `map[string]bool` |  | |  |  |
| haveDBTLSCert | boolean| `bool` |  | |  |  |
| haveDBTLSOldCert | boolean| `bool` |  | |  |  |
| inBinlogBackup | boolean| `bool` |  | |  |  |
| inLogicalBackup | boolean| `bool` |  | |  |  |
| inPhysicalBackup | boolean| `bool` |  | |  |  |
| inResticBackup | boolean| `bool` |  | |  |  |
| inRollingRestart | boolean| `bool` |  | |  |  |
| isAlertDisable | boolean| `bool` |  | |  |  |
| isAllDbUp | boolean| `bool` |  | |  |  |
| isCapturing | boolean| `bool` |  | |  |  |
| isClusterDown | boolean| `bool` |  | |  |  |
| isDown | boolean| `bool` |  | |  |  |
| isExportPush | boolean| `bool` |  | |  |  |
| isFailable | boolean| `bool` |  | |  |  |
| isFailedArbitrator | boolean| `bool` |  | |  |  |
| isGettingSlowLog | boolean| `bool` |  | |  |  |
| isGitPull | boolean| `bool` |  | |  |  |
| isGitPush | boolean| `bool` |  | |  |  |
| isLostMajority | boolean| `bool` |  | |  |  |
| isMasterDown | boolean| `bool` |  | |  |  |
| isNeedDatabasesConfigChange | boolean| `bool` |  | |  |  |
| isNeedDatabasesReprov | boolean| `bool` |  | |  |  |
| isNeedDatabasesRestart | boolean| `bool` |  | |  |  |
| isNeedDatabasesRollingReprov | boolean| `bool` |  | |  |  |
| isNeedDatabasesRollingRestart | boolean| `bool` |  | |  |  |
| isNeedProxiesConfigChange | boolean| `bool` |  | |  |  |
| isNeedProxiesRestart | boolean| `bool` |  | |  |  |
| isNeedProxyRestart | boolean| `bool` |  | |  |  |
| isNotMonitoring | boolean| `bool` |  | |  |  |
| isPostgres | boolean| `bool` |  | |  |  |
| isProvision | boolean| `bool` |  | |  |  |
| isSplitBrain | boolean| `bool` |  | |  |  |
| isValidBackup | boolean| `bool` |  | |  |  |
| jobResults | [ConfigTasksMap](#config-tasks-map)| `ConfigTasksMap` |  | |  |  |
| lastDelayStatPrint | string| `string` |  | |  |  |
| log | [S18logHTTPLog](#s18log-http-log)| `S18logHTTPLog` |  | |  |  |
| logTask | [S18logHTTPLog](#s18log-http-log)| `S18logHTTPLog` |  | |  |  |
| monitorSpin | string| `string` |  | |  |  |
| monitorType | map of string| `map[string]string` |  | |  |  |
| name | string| `string` |  | |  |  |
| proxyServers | []string| `[]string` |  | |  |  |
| slaHistory | [][StateSLA](#state-sla)| `[]*StateSLA` |  | |  |  |
| slavesConnected | integer| `int64` |  | |  |  |
| slavesOldestMasterFile | [ClusterSlavesOldestMasterFile](#cluster-slaves-oldest-master-file)| `ClusterSlavesOldestMasterFile` |  | |  |  |
| sqlErrorLog | [S18logHTTPLog](#s18log-http-log)| `S18logHTTPLog` |  | |  |  |
| sqlGeneralLog | [S18logHTTPLog](#s18log-http-log)| `S18logHTTPLog` |  | |  |  |
| sstAvailablePorts | map of string| `map[string]string` |  | |  |  |
| stateMachine | [ClusterCluster](#cluster-cluster)| `ClusterCluster` |  | | dbUser                        string                      `json:"-"`</br>oldDbUser string `json:"-"`</br>dbPass                        string                      `json:"-"`</br>oldDbPass string `json:"-"`</br>rplUser                   string                      `json:"-"`</br>rplPass                   string                      `json:"-"`</br>proxysqlUser              string                      `json:"-"`</br>proxysqlPass              string                      `json:"-"` |  |
| tenant | string| `string` |  | |  |  |
| topology | string| `string` |  | |  |  |
| topologyType | map of string| `map[string]string` |  | |  |  |
| uptime | string| `string` |  | |  |  |
| uptimeFailable | string| `string` |  | |  |  |
| uptimeSemisync | string| `string` |  | |  |  |
| useBlacklist | boolean| `bool` |  | |  |  |
| useWhitelist | boolean| `bool` |  | |  |  |
| versionsMap | [ConfigVersionsMap](#config-versions-map)| `ConfigVersionsMap` |  | |  |  |
| vmType | map of boolean| `map[string]bool` |  | |  |  |
| waitingFailover | integer| `int64` |  | |  |  |
| waitingRejoin | integer| `int64` |  | |  |  |
| waitingSwitchover | integer| `int64` |  | |  |  |
| whitelist | [][RegexpRegexp](#regexp-regexp)| `[]RegexpRegexp` |  | |  |  |
| workLoad | [ConfigWorkLoad](#config-work-load)| `ConfigWorkLoad` |  | |  |  |
| workingDir | string| `string` |  | |  |  |



### 3.5 <span id="cluster-cluster-form"></span> cluster.ClusterForm


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| clusterName | string| `string` |  | |  |  |
| orchestrator | string| `string` |  | |  |  |
| plan | string| `string` |  | |  |  |



### 3.6 <span id="cluster-crash"></span> cluster.Crash


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| electedMasterURL | string| `string` |  | |  |  |
| failoverIOGtid | [][GtidGtid](#gtid-gtid)| `[]*GtidGtid` |  | |  |  |
| failoverMasterLogFile | string| `string` |  | |  |  |
| failoverMasterLogPos | string| `string` |  | |  |  |
| failoverSemiSyncSlaveStatus | boolean| `bool` |  | |  |  |
| newMasterLogFile | string| `string` |  | |  |  |
| newMasterLogPos | string| `string` |  | |  |  |
| switchover | boolean| `bool` |  | |  |  |
| unixTimestamp | integer| `int64` |  | |  |  |
| url | string| `string` |  | |  |  |



### 3.7 <span id="cluster-delay-stat"></span> cluster.DelayStat


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| counter | integer| `int64` |  | | Increment |  |
| delay | number| `float64` |  | | Average Seconds of Delay |  |
| delayCount | integer| `int64` |  | | Number of Delay Occurred |  |
| slaveErrCount | integer| `int64` |  | | Number of Slave Err Occurred |  |



### 3.8 <span id="cluster-delay-stat-history"></span> cluster.DelayStatHistory


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| delayDT | string| `string` |  | |  |  |
| delayStat | [ClusterDelayStat](#cluster-delay-stat)| `ClusterDelayStat` |  | |  |  |



### 3.9 <span id="cluster-diff"></span> cluster.Diff


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| serverName | string| `string` |  | |  |  |
| variableValue | string| `string` |  | |  |  |



### 3.10 <span id="cluster-graphite-filter-list"></span> cluster.GraphiteFilterList


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| blacklist | string| `string` |  | |  |  |
| whitelist | string| `string` |  | |  |  |



### 3.11 <span id="cluster-server-backup-meta"></span> cluster.ServerBackupMeta


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| logical | [ConfigBackupMetadata](#config-backup-metadata)| `ConfigBackupMetadata` |  | |  |  |
| physical | [ConfigBackupMetadata](#config-backup-metadata)| `ConfigBackupMetadata` |  | |  |  |



### 3.12 <span id="cluster-server-delay-stat"></span> cluster.ServerDelayStat


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| current | [ClusterDelayStat](#cluster-delay-stat)| `ClusterDelayStat` |  | |  |  |
| currentDT | string| `string` |  | |  |  |
| delayHistory | [][ClusterDelayStatHistory](#cluster-delay-stat-history)| `[]*ClusterDelayStatHistory` |  | |  |  |
| rotated | [ClusterDelayStat](#cluster-delay-stat)| `ClusterDelayStat` |  | |  |  |
| total | [ClusterServerDelayStat](#cluster-server-delay-stat)| `ClusterServerDelayStat` |  | | Total Delay Average since SRM started |  |



### 3.13 <span id="cluster-server-monitor"></span> cluster.ServerMonitor


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| HaveBinlogSlaveUpdates | boolean| `bool` |  | |  |  |
| HaveHealthyReplica | boolean| `bool` |  | |  |  |
| HaveSshError | boolean| `bool` |  | |  |  |
| agent | string| `string` |  | | used to provision service in orchestrator |  |
| binaryLogDir | string| `string` |  | |  |  |
| binaryLogFile | string| `string` |  | |  |  |
| binaryLogFileOldest | string| `string` |  | |  |  |
| binaryLogFilePrevious | string| `string` |  | |  |  |
| binaryLogFiles | [DbhelperBinaryLogMetaMap](#dbhelper-binary-log-meta-map)| `DbhelperBinaryLogMetaMap` |  | |  |  |
| binaryLogFilesCount | integer| `int64` |  | |  |  |
| binaryLogOldestTimestamp | integer| `int64` |  | |  |  |
| binaryLogPos | string| `string` |  | |  |  |
| binaryLogPurgeBefore | integer| `int64` |  | |  |  |
| binlogDumpThreads | integer| `int64` |  | |  |  |
| currentGtid | [][GtidGtid](#gtid-gtid)| `[]*GtidGtid` |  | |  |  |
| datadir | string| `string` |  | |  |  |
| dbVersion | [VersionVersion](#version-version)| `VersionVersion` |  | |  |  |
| dbdataDir | string| `string` |  | |  |  |
| delayStat | [ClusterServerDelayStat](#cluster-server-delay-stat)| `ClusterServerDelayStat` |  | |  |  |
| domain | string| `string` |  | | Use to store orchestrator CNI domain .<cluster_name>.svc.<cluster_name> |  |
| domainId | integer| `int64` |  | |  |  |
| engineInnodb | [ConfigStringsMap](#config-strings-map)| `ConfigStringsMap` |  | |  |  |
| errorLog | [S18logHTTPLog](#s18log-http-log)| `S18logHTTPLog` |  | |  |  |
| eventScheduler | boolean| `bool` |  | |  |  |
| eventStatus | [][DbhelperEvent](#dbhelper-event)| `[]*DbhelperEvent` |  | |  |  |
| failCount | integer| `int64` |  | |  |  |
| failSuspectHeartbeat | integer| `int64` |  | |  |  |
| failoverIoGtid | [][GtidGtid](#gtid-gtid)| `[]*GtidGtid` |  | |  |  |
| failoverMasterLogFile | string| `string` |  | |  |  |
| failoverMasterLogPos | string| `string` |  | |  |  |
| failoverSemiSyncSlaveStatus | boolean| `bool` |  | |  |  |
| gtidBinlogPos | [][GtidGtid](#gtid-gtid)| `[]*GtidGtid` |  | |  |  |
| gtidExecuted | string| `string` |  | |  |  |
| hashUUID | integer| `int64` |  | |  |  |
| haveBinLogSync | boolean| `bool` |  | |  |  |
| haveBinlog | boolean| `bool` |  | |  |  |
| haveBinlogAnnotate | boolean| `bool` |  | |  |  |
| haveBinlogCompress | boolean| `bool` |  | |  |  |
| haveBinlogMixed | boolean| `bool` |  | |  |  |
| haveBinlogRow | boolean| `bool` |  | |  |  |
| haveBinlogSlowqueries | boolean| `bool` |  | |  |  |
| haveBinlogStatement | boolean| `bool` |  | |  |  |
| haveDiskMonitor | boolean| `bool` |  | |  |  |
| haveGtidStrictMode | boolean| `bool` |  | |  |  |
| haveInnodbChecksum | boolean| `bool` |  | |  |  |
| haveInnodbTrxCommit | boolean| `bool` |  | |  |  |
| haveLogGeneral | boolean| `bool` |  | |  |  |
| haveMariadbGtid | boolean| `bool` |  | |  |  |
| haveMetaDataLocksLog | boolean| `bool` |  | |  |  |
| haveMysqlGtid | boolean| `bool` |  | |  |  |
| haveNoMasterOnStart | boolean| `bool` |  | |  |  |
| havePFS | boolean| `bool` |  | |  |  |
| havePFSSlowQueryLog | boolean| `bool` |  | |  |  |
| haveQueryResponseTimeLog | boolean| `bool` |  | |  |  |
| haveReadOnly | boolean| `bool` |  | |  |  |
| haveSQLErrorLog | boolean| `bool` |  | |  |  |
| haveSemiSync | boolean| `bool` |  | |  |  |
| haveSlaveAggressive | boolean| `bool` |  | |  |  |
| haveSlaveConservative | boolean| `bool` |  | |  |  |
| haveSlaveIdempotent | boolean| `bool` |  | |  |  |
| haveSlaveMinimal | boolean| `bool` |  | |  |  |
| haveSlaveOptimistic | boolean| `bool` |  | |  |  |
| haveSlaveSerialized | boolean| `bool` |  | |  |  |
| haveSlowQueryLog | boolean| `bool` |  | |  |  |
| haveWsrep | boolean| `bool` |  | |  |  |
| host | string| `string` |  | |  |  |
| id | string| `string` |  | | Unique name given by cluster & crc64(URL) used by test to provision |  |
| ignored | boolean| `bool` |  | |  |  |
| ignoredRO | boolean| `bool` |  | |  |  |
| inCaptureMode | boolean| `bool` |  | |  |  |
| inPurgingBinaryLog | boolean| `bool` |  | |  |  |
| ioGtid | [][GtidGtid](#gtid-gtid)| `[]*GtidGtid` |  | |  |  |
| ip | string| `string` |  | |  |  |
| isBackingUpBinaryLog | boolean| `bool` |  | |  |  |
| isCompute | boolean| `bool` |  | | Used to idenfied spider compute nide |  |
| isConfigGen | boolean| `bool` |  | |  |  |
| isDelayed | boolean| `bool` |  | |  |  |
| isFull | boolean| `bool` |  | |  |  |
| isGroupReplicationMaster | boolean| `bool` |  | |  |  |
| isGroupReplicationSlave | boolean| `bool` |  | |  |  |
| isInPFSQueryCapture | boolean| `bool` |  | |  |  |
| isInSlowQueryCapture | boolean| `bool` |  | |  |  |
| isLoadingJobList | boolean| `bool` |  | |  |  |
| isMaintenance | boolean| `bool` |  | |  |  |
| isMaxscale | boolean| `bool` |  | |  |  |
| isRefreshingBinlog | boolean| `bool` |  | |  |  |
| isRefreshingBinlogMeta | boolean| `bool` |  | |  |  |
| isRelay | boolean| `bool` |  | |  |  |
| isReseeding | string| `string` |  | |  |  |
| isSlave | boolean| `bool` |  | |  |  |
| isVirtualMaster | boolean| `bool` |  | |  |  |
| isWsrepDonor | boolean| `bool` |  | |  |  |
| isWsrepPrimary | boolean| `bool` |  | |  |  |
| isWsrepSync | boolean| `bool` |  | |  |  |
| jobResults | [ConfigTasksMap](#config-tasks-map)| `ConfigTasksMap` |  | |  |  |
| lastBackupMeta | [ClusterServerBackupMeta](#cluster-server-backup-meta)| `ClusterServerBackupMeta` |  | |  |  |
| lastSeenReplications | [][DbhelperSlaveStatus](#dbhelper-slave-status)| `[]*DbhelperSlaveStatus` |  | |  |  |
| logOutput | string| `string` |  | |  |  |
| longQueryTime | string| `string` |  | |  |  |
| longQueryTimeSaved | string| `string` |  | |  |  |
| masterStatus | [DbhelperMasterStatus](#dbhelper-master-status)| `DbhelperMasterStatus` |  | |  |  |
| maxSlowQueryTimestamp | integer| `int64` |  | |  |  |
| maxscaleHaveGtid | boolean| `bool` |  | |  |  |
| maxscaleServerName | string| `string` |  | | Unique server Name in maxscale conf |  |
| maxscaleServerStatus | string| `string` |  | |  |  |
| maxscaleVersion | integer| `int64` |  | |  |  |
| name | string| `string` |  | |  |  |
| needRefreshJobs | boolean| `bool` |  | |  |  |
| pointInTimeMeta | [ConfigPointInTimeMeta](#config-point-in-time-meta)| `ConfigPointInTimeMeta` |  | |  |  |
| port | string| `string` |  | |  |  |
| postgressDB | string| `string` |  | |  |  |
| prefered | boolean| `bool` |  | |  |  |
| preferedBackup | boolean| `bool` |  | |  |  |
| prevState | string| `string` |  | |  |  |
| process | [OsProcess](#os-process)| `OsProcess` |  | |  |  |
| proxysqlHostgroup | string| `string` |  | |  |  |
| qps | integer| `int64` |  | |  |  |
| readOnly | string| `string` |  | |  |  |
| relayLogSize | integer| `int64` |  | |  |  |
| replicationHealth | string| `string` |  | |  |  |
| replicationSourceName | string| `string` |  | |  |  |
| replicationTags | string| `string` |  | |  |  |
| replications | [][DbhelperSlaveStatus](#dbhelper-slave-status)| `[]*DbhelperSlaveStatus` |  | |  |  |
| semiSyncMasterStatus | boolean| `bool` |  | |  |  |
| semiSyncSlaveStatus | boolean| `bool` |  | |  |  |
| serverId | integer| `int64` |  | |  |  |
| serviceName | string| `string` |  | |  |  |
| slaposDatadir | string| `string` |  | |  |  |
| slaveGtid | [][GtidGtid](#gtid-gtid)| `[]*GtidGtid` |  | |  |  |
| slaveVariables | [ClusterSlaveVariables](#cluster-slave-variables)| `ClusterSlaveVariables` |  | |  |  |
| slowQueryCapture | boolean| `bool` |  | |  |  |
| slowQueryLog | string| `string` |  | |  |  |
| sourceClusterName | string| `string` |  | | Used to idenfied server added from other clusters linked with multi source |  |
| sstPort | string| `string` |  | | used to send data to dbjobs |  |
| state | string| `string` |  | |  |  |
| strict | string| `string` |  | |  |  |
| tlsConfigUsed | string| `string` |  | | used to track TLS config during key rotation |  |
| tunnelPort | string| `string` |  | |  |  |
| url | string| `string` |  | |  |  |
| user | string| `string` |  | |  |  |
| workLoad | [ConfigWorkLoadsMap](#config-work-loads-map)| `ConfigWorkLoadsMap` |  | |  |  |



### 3.14 <span id="cluster-slave-variables"></span> cluster.SlaveVariables


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| slaveParallelMaxQueued | integer| `int64` |  | |  |  |
| slaveParallelMode | string| `string` |  | |  |  |
| slaveParallelThreads | integer| `int64` |  | |  |  |
| slaveParallelWorkers | integer| `int64` |  | |  |  |
| slaveTypeConversions | string| `string` |  | |  |  |



### 3.15 <span id="cluster-slaves-oldest-master-file"></span> cluster.SlavesOldestMasterFile


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| oldestTimestamp | string| `string` |  | |  |  |
| prefix | string| `string` |  | |  |  |
| suffix | integer| `int64` |  | |  |  |



### 3.16 <span id="cluster-test"></span> cluster.Test


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| config-file | string| `string` |  | |  |  |
| config-init | [GithubComSignal18ReplicationManagerConfigConfig](#github-com-signal18-replication-manager-config-config)| `GithubComSignal18ReplicationManagerConfigConfig` |  | |  |  |
| config-test | [GithubComSignal18ReplicationManagerConfigConfig](#github-com-signal18-replication-manager-config-config)| `GithubComSignal18ReplicationManagerConfigConfig` |  | |  |  |
| name | string| `string` |  | |  |  |
| result | string| `string` |  | |  |  |



### 3.17 <span id="cluster-user-form"></span> cluster.UserForm


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| grants | string| `string` |  | |  |  |
| password | string| `string` |  | |  |  |
| roles | string| `string` |  | |  |  |
| username | string| `string` |  | |  |  |



### 3.18 <span id="cluster-variable-diff"></span> cluster.VariableDiff


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| diffValues | [][ClusterDiff](#cluster-diff)| `[]*ClusterDiff` |  | |  |  |
| variableName | string| `string` |  | |  |  |



### 3.19 <span id="config-backup-meta-map"></span> config.BackupMetaMap


  

[interface{}](#interface)

### 3.20 <span id="config-backup-metadata"></span> config.BackupMetadata


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| backupMethod | integer| `int64` |  | |  |  |
| backupStrategy | integer| `int64` |  | |  |  |
| backupTool | string| `string` |  | |  |  |
| binLogFileName | string| `string` |  | |  |  |
| binLogFilePos | integer| `int64` |  | |  |  |
| binLogUuid | string| `string` |  | |  |  |
| checksum | string| `string` |  | |  |  |
| completed | boolean| `bool` |  | |  |  |
| compressed | boolean| `bool` |  | |  |  |
| dest | string| `string` |  | |  |  |
| encrypted | boolean| `bool` |  | |  |  |
| encryptionAlgo | string| `string` |  | |  |  |
| encryptionKey | string| `string` |  | |  |  |
| endTime | string| `string` |  | |  |  |
| id | integer| `int64` |  | |  |  |
| previous | integer| `int64` |  | |  |  |
| retentionDays | integer| `int64` |  | |  |  |
| size | integer| `int64` |  | |  |  |
| source | string| `string` |  | |  |  |
| startTime | string| `string` |  | |  |  |



### 3.21 <span id="config-config-variable-type"></span> config.ConfigVariableType


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| available | boolean| `bool` |  | |  |  |
| id | integer| `int64` |  | |  |  |
| label | string| `string` |  | |  |  |
| name | string| `string` |  | |  |  |



### 3.22 <span id="config-docker-repo"></span> config.DockerRepo


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| image | string| `string` |  | |  |  |
| name | string| `string` |  | |  |  |
| tags | [ConfigDockerTag](#config-docker-tag)| `ConfigDockerTag` |  | |  |  |



### 3.23 <span id="config-docker-tag"></span> config.DockerTag


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| results | [][ConfigTagResult](#config-tag-result)| `[]*ConfigTagResult` |  | |  |  |



### 3.24 <span id="config-grant"></span> config.Grant


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| enable | boolean| `bool` |  | |  |  |
| grant | string| `string` |  | |  |  |



### 3.25 <span id="config-partner"></span> config.Partner


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| dbopsEmail | string| `string` |  | |  |  |
| domains | string| `string` |  | |  |  |
| id | integer| `int64` |  | |  |  |
| isDbops | integer| `int64` |  | |  |  |
| isSysops | integer| `int64` |  | |  |  |
| name | string| `string` |  | |  |  |
| stars | integer| `int64` |  | |  |  |
| sysopsEmail | string| `string` |  | |  |  |



### 3.26 <span id="config-peer-cluster"></span> config.PeerCluster


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| api-credentials-acl-allow | string| `string` |  | |  |  |
| api-credentials-acl-allow-external | string| `string` |  | |  |  |
| api-public-url | string| `string` |  | |  |  |
| cloud18-cost-currency | string| `string` |  | |  |  |
| cloud18-database-read-srv-record | string| `string` |  | |  |  |
| cloud18-database-read-write-split-srv-record | string| `string` |  | |  |  |
| cloud18-database-read-write-srv-record | string| `string` |  | |  |  |
| cloud18-domain | string| `string` |  | |  |  |
| cloud18-external-dbops | string| `string` |  | |  |  |
| cloud18-external-sysops | string| `string` |  | |  |  |
| cloud18-infra-certifications | string| `string` |  | |  |  |
| cloud18-infra-cpu-freq | string| `string` |  | |  |  |
| cloud18-infra-cpu-model | string| `string` |  | |  |  |
| cloud18-infra-data-centers | string| `string` |  | |  |  |
| cloud18-infra-geo-localizations | string| `string` |  | |  |  |
| cloud18-infra-public-bandwidth | string| `string` |  | |  | `0` |
| cloud18-monthly-dbops-cost | string| `string` |  | |  | `0` |
| cloud18-monthly-infra-cost | string| `string` |  | |  | `0` |
| cloud18-monthly-license-cost | string| `string` |  | |  | `0` |
| cloud18-monthly-sysops-cost | string| `string` |  | |  | `0` |
| cloud18-open-dbops | string| `string` |  | |  | `false` |
| cloud18-open-sysops | string| `string` |  | |  | `false` |
| cloud18-peer | string| `string` |  | |  | `false` |
| cloud18-platform-description | string| `string` |  | |  |  |
| cloud18-promotion-pct | string| `string` |  | |  | `0` |
| cloud18-shared | string| `string` |  | |  | `false` |
| cloud18-sla-provision-time | string| `string` |  | |  | `0` |
| cloud18-sla-repair-time | string| `string` |  | |  | `0` |
| cloud18-sla-response-time | string| `string` |  | |  | `0` |
| cloud18-sub-domain | string| `string` |  | |  |  |
| cloud18-sub-domain-zone | string| `string` |  | |  |  |
| cloud18-subscribed-dbops | string| `string` |  | |  | `false` |
| cluster-name | string| `string` |  | |  |  |
| peer-users | []string| `[]string` |  | |  |  |
| prov-db-cpu-cores | string| `string` |  | |  | `0` |
| prov-db-disk-iops | string| `string` |  | |  | `0` |
| prov-db-disk-size | string| `string` |  | |  | `0` |
| prov-db-memory | string| `string` |  | |  | `0` |
| prov-orchestrator | string| `string` |  | |  |  |
| prov-service-plan | string| `string` |  | |  |  |



### 3.27 <span id="config-point-in-time-meta"></span> config.PointInTimeMeta


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| backup | integer| `int64` |  | |  |  |
| isInPITR | boolean| `bool` |  | |  |  |
| restoreTime | integer| `int64` |  | |  |  |
| useBinlog | boolean| `bool` |  | |  |  |



### 3.28 <span id="config-role"></span> config.Role


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| enable | boolean| `bool` |  | |  |  |
| role | string| `string` |  | |  |  |



### 3.29 <span id="config-service-plan"></span> config.ServicePlan


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| bp | string| `string` |  | |  | `0` |
| certs | string| `string` |  | |  |  |
| cpu | string| `string` |  | |  |  |
| dbacost | string| `string` |  | |  | `0` |
| dbcores | string| `string` |  | |  | `0` |
| dbcpufreq | string| `string` |  | |  |  |
| dbdatasize | string| `string` |  | |  | `0` |
| dbiops | string| `string` |  | |  | `0` |
| dbmemory | string| `string` |  | |  | `0` |
| dbsystemsize | string| `string` |  | |  | `0` |
| dc | string| `string` |  | |  |  |
| devise | string| `string` |  | |  |  |
| extdbops | string| `string` |  | |  |  |
| extsysops | string| `string` |  | |  |  |
| gti | string| `string` |  | |  | `0` |
| gtr | string| `string` |  | |  | `0` |
| id | string| `string` |  | |  | `0` |
| infra | string| `string` |  | |  |  |
| infracost | string| `string` |  | |  | `0` |
| licencecost | string| `string` |  | |  | `0` |
| plan | string| `string` |  | |  |  |
| promo | string| `string` |  | |  | `0` |
| provtime | string| `string` |  | |  | `0` |
| prxcores | string| `string` |  | |  | `0` |
| prxdatasize | string| `string` |  | |  | `0` |
| syscost | string| `string` |  | |  | `0` |
| zone | string| `string` |  | |  |  |



### 3.30 <span id="config-strings-map"></span> config.StringsMap


  

[interface{}](#interface)

### 3.31 <span id="config-tag-result"></span> config.TagResult


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| name | string| `string` |  | |  |  |



### 3.32 <span id="config-tarball"></span> config.Tarball


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| OS | string| `string` |  | |  |  |
| checksum | string| `string` |  | |  |  |
| date_added | string| `string` |  | |  |  |
| flavor | string| `string` |  | |  |  |
| minimal | boolean| `bool` |  | |  |  |
| name | string| `string` |  | |  |  |
| notes | string| `string` |  | |  |  |
| short_version | string| `string` |  | |  |  |
| size | integer| `int64` |  | |  |  |
| updated_by | string| `string` |  | |  |  |
| url | string| `string` |  | |  |  |
| version | string| `string` |  | |  |  |



### 3.33 <span id="config-tasks-map"></span> config.TasksMap


  

[interface{}](#interface)

### 3.34 <span id="config-versions-map"></span> config.VersionsMap


  

[interface{}](#interface)

### 3.35 <span id="config-work-load"></span> config.WorkLoad


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| busyTime | string| `string` |  | |  |  |
| connections | integer| `int64` |  | |  |  |
| cpuThreadPool | number| `float64` |  | |  |  |
| cpuUserStats | number| `float64` |  | |  |  |
| dbIndexSize | integer| `int64` |  | |  |  |
| dbTableSize | integer| `int64` |  | |  |  |
| qps | integer| `int64` |  | |  |  |



### 3.36 <span id="config-work-loads-map"></span> config.WorkLoadsMap


  

[interface{}](#interface)

### 3.37 <span id="configurator-configurator"></span> configurator.Configurator


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| configPrxTags | [][Repmanv3Tag](#repmanv3-tag)| `[]*Repmanv3Tag` |  | | from module |  |
| configTags | [][Repmanv3Tag](#repmanv3-tag)| `[]*Repmanv3Tag` |  | | from module |  |
| dbServersTags | []string| `[]string` |  | | from conf |  |
| dbServersTagsDiscover | []string| `[]string` |  | | from conf |  |
| proxyServersTags | []string| `[]string` |  | |  |  |
| proxyServersTagsDiscover | []string| `[]string` |  | |  |  |



### 3.38 <span id="dbhelper-binary-log-meta-map"></span> dbhelper.BinaryLogMetaMap


  

[interface{}](#interface)

### 3.39 <span id="dbhelper-event"></span> dbhelper.Event


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| db | string| `string` |  | |  |  |
| definer | string| `string` |  | |  |  |
| name | string| `string` |  | |  |  |
| status | integer| `int64` |  | |  |  |



### 3.40 <span id="dbhelper-master-status"></span> dbhelper.MasterStatus


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| binlogDoDB | string| `string` |  | |  |  |
| binlogIgnoreDB | string| `string` |  | |  |  |
| file | string| `string` |  | |  |  |
| position | integer| `int64` |  | |  |  |



### 3.41 <span id="dbhelper-slave-status"></span> dbhelper.SlaveStatus


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| channelName | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| connectionName | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| eeplicateDoDomainIds | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| execMasterLogPos | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| executedGtidSet | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| gtidIoPos | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| gtidSlavePos | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| lastIoErrno | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| lastIoError | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| lastSqlErrno | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| lastSqlError | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| masterHost | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| masterLogFile | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| masterPort | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| masterServerId | integer| `int64` |  | |  |  |
| masterUser | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| postgresExternalId | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| readMasterLogPos | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| relayMasterLogFile | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| replicateDoDb | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| replicateDoTable | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| replicateIgnoreDb | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| replicateIgnoreDomainIds | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| replicateIgnoreServerIds | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| replicateIgnoreTable | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| replicateWildDoTable | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| replicateWildIgnoreTable | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| retrievedGtidSet | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| secondsBehindMaster | [SQLNullInt64](#sql-null-int64)| `SQLNullInt64` |  | |  |  |
| slaveHeartbeatPeriod | number| `float64` |  | |  |  |
| slaveIoRunning | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| slaveSQLRunningState | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| slaveSqlRunning | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |
| usingGtid | [SQLNullString](#sql-null-string)| `SQLNullString` |  | |  |  |



### 3.42 <span id="github-com-signal18-replication-manager-config-config"></span> github_com_signal18_replication-manager_config.Config


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| alertPushoverAppToken | string| `string` |  | |  |  |
| alertPushoverUserToken | string| `string` |  | |  |  |
| alertScript | string| `string` |  | |  |  |
| alertSlackChannel | string| `string` |  | |  |  |
| alertSlackUrl | string| `string` |  | |  |  |
| alertSlackUser | string| `string` |  | |  |  |
| alertTeamsProxyUrl | string| `string` |  | |  |  |
| alertTeamsState | string| `string` |  | |  |  |
| alertTeamsUrl | string| `string` |  | |  |  |
| analyzeUseSql | boolean| `bool` |  | |  |  |
| apiBind | string| `string` |  | |  |  |
| apiCredentials | string| `string` |  | |  |  |
| apiCredentialsACLAllow | string| `string` |  | |  |  |
| apiCredentialsACLAllowExternal | string| `string` |  | |  |  |
| apiCredentialsACLDiscard | string| `string` |  | |  |  |
| apiCredentialsACLDiscardExternal | string| `string` |  | |  |  |
| apiCredentialsExternal | string| `string` |  | |  |  |
| apiCredentialsSecureConfig | boolean| `bool` |  | |  |  |
| apiHttpsBind | boolean| `bool` |  | |  |  |
| apiOAuthClientID | string| `string` |  | |  |  |
| apiOAuthClientSecret | string| `string` |  | |  |  |
| apiOAuthProvider | string| `string` |  | |  |  |
| apiPort | string| `string` |  | |  |  |
| apiPublicUrl | string| `string` |  | |  |  |
| apiServer | boolean| `bool` |  | |  |  |
| apiSwaggerEnabled | boolean| `bool` |  | |  |  |
| apiTokenTimeout | integer| `int64` |  | |  |  |
| arbitrationExternal | boolean| `bool` |  | |  |  |
| arbitrationExternalHosts | string| `string` |  | |  |  |
| arbitrationExternalSecret | string| `string` |  | |  |  |
| arbitrationExternalUniqueId | integer| `int64` |  | |  |  |
| arbitrationFailedMasterScript | string| `string` |  | |  |  |
| arbitrationPeerHosts | string| `string` |  | |  |  |
| arbitrationReadTimout | integer| `int64` |  | |  |  |
| arbitratorBindAddress | string| `string` |  | |  |  |
| arbitratorDriver | string| `string` |  | |  |  |
| autorejoin | boolean| `bool` |  | |  |  |
| autorejoinBackupBinlog | boolean| `bool` |  | |  |  |
| autorejoinFlashback | boolean| `bool` |  | |  |  |
| autorejoinFlashbackOnSync | boolean| `bool` |  | |  |  |
| autorejoinFlashbackOnUnsync | boolean| `bool` |  | |  |  |
| autorejoinForceRestore | boolean| `bool` |  | |  |  |
| autorejoinLogicalBackup | boolean| `bool` |  | |  |  |
| autorejoinMysqldump | boolean| `bool` |  | |  |  |
| autorejoinPhysicalBackup | boolean| `bool` |  | |  |  |
| autorejoinScript | string| `string` |  | |  |  |
| autorejoinSlavePositionalHeartbeat | boolean| `bool` |  | |  |  |
| autorejoinZfsFlashback | boolean| `bool` |  | |  |  |
| autoseed | boolean| `bool` |  | |  |  |
| backup | boolean| `bool` |  | |  |  |
| backupBinlogs | boolean| `bool` |  | |  |  |
| backupBinlogsKeep | integer| `int64` |  | |  |  |
| backupKeepDaily | integer| `int64` |  | |  |  |
| backupKeepHourly | integer| `int64` |  | |  |  |
| backupKeepMonthly | integer| `int64` |  | |  |  |
| backupKeepUntilValid | boolean| `bool` |  | |  |  |
| backupKeepWeekly | integer| `int64` |  | |  |  |
| backupKeepYearly | integer| `int64` |  | |  |  |
| backupLoadScript | string| `string` |  | |  |  |
| backupLockDDL | boolean| `bool` |  | |  |  |
| backupLogicalDumpSystemTables | boolean| `bool` |  | |  |  |
| backupLogicalDumpThreads | integer| `int64` |  | |  |  |
| backupLogicalLoadThreads | integer| `int64` |  | |  |  |
| backupLogicalType | string| `string` |  | |  |  |
| backupMyDumperOptions | string| `string` |  | |  |  |
| backupMyDumperRegex | string| `string` |  | |  |  |
| backupMyLoaderOptions | string| `string` |  | |  |  |
| backupMydumperPath | string| `string` |  | |  |  |
| backupMyloaderPath | string| `string` |  | |  |  |
| backupMysqlbinlogPath | string| `string` |  | |  |  |
| backupMysqlclientgPath | string| `string` |  | |  |  |
| backupMysqldumpOptions | string| `string` |  | |  |  |
| backupMysqldumpPath | string| `string` |  | |  |  |
| backupPhysicalType | string| `string` |  | |  |  |
| backupRestic | boolean| `bool` |  | |  |  |
| backupResticAws | boolean| `bool` |  | |  |  |
| backupResticAwsAccessKeyId | string| `string` |  | |  |  |
| backupResticBinaryPath | string| `string` |  | |  |  |
| backupResticRepository | string| `string` |  | |  |  |
| backupSaveScript | string| `string` |  | |  |  |
| backupStreaming | boolean| `bool` |  | |  |  |
| backupStreamingBucket | string| `string` |  | |  |  |
| backupStreamingDebug | boolean| `bool` |  | |  |  |
| backupStreamingEndpoint | string| `string` |  | |  |  |
| backupStreamingRegion | string| `string` |  | |  |  |
| binlogCopyMode | string| `string` |  | |  |  |
| binlogCopyScript | string| `string` |  | |  |  |
| binlogParseMode | string| `string` |  | |  |  |
| binlogRotationScript | string| `string` |  | |  |  |
| checkBinlogFilters | boolean| `bool` |  | |  |  |
| checkBinlogServerId | integer| `int64` |  | |  |  |
| checkGrants | boolean| `bool` |  | |  |  |
| checkReplicationErrantTrx | boolean| `bool` |  | |  |  |
| checkReplicationFilters | boolean| `bool` |  | |  |  |
| checkReplicationState | boolean| `bool` |  | |  |  |
| checkType | string| `string` |  | |  |  |
| cloud18 | boolean| `bool` |  | |  |  |
| cloud18CostCurrency | string| `string` |  | |  |  |
| cloud18DatabaseReadSrvRecord | string| `string` |  | |  |  |
| cloud18DatabaseReadWriteSplitSrvRecord | string| `string` |  | |  |  |
| cloud18DatabaseReadWriteSrvRecord | string| `string` |  | |  |  |
| cloud18DbOps | string| `string` |  | |  |  |
| cloud18DbaUserCredential | string| `string` |  | |  |  |
| cloud18Domain | string| `string` |  | |  |  |
| cloud18ExternalDbOps | string| `string` |  | |  |  |
| cloud18ExternalSysOps | string| `string` |  | |  |  |
| cloud18GitUser | string| `string` |  | |  |  |
| cloud18InfraCertifications | string| `string` |  | |  |  |
| cloud18InfraCpuFreq | string| `string` |  | |  |  |
| cloud18InfraCpuModel | string| `string` |  | |  |  |
| cloud18InfraDataCenters | string| `string` |  | |  |  |
| cloud18InfraDescription | string| `string` |  | |  |  |
| cloud18InfraGeoLocalizations | string| `string` |  | |  |  |
| cloud18InfraPublicBandwidth | number| `float64` |  | |  |  |
| cloud18MonthlyDbopsCost | number| `float64` |  | |  |  |
| cloud18MonthlyInfraCost | number| `float64` |  | |  |  |
| cloud18MonthlyLicenseCost | number| `float64` |  | |  |  |
| cloud18MonthlySysopsCost | number| `float64` |  | |  |  |
| cloud18OpenDbops | boolean| `bool` |  | |  |  |
| cloud18OpenSysops | boolean| `bool` |  | |  |  |
| cloud18PlatformDescription | string| `string` |  | |  |  |
| cloud18PromotionPct | number| `float64` |  | |  |  |
| cloud18SalesSubscriptionScript | string| `string` |  | |  |  |
| cloud18SalesSubscriptionValidateScript | string| `string` |  | |  |  |
| cloud18SalesUnsubscribeScript | string| `string` |  | |  |  |
| cloud18Shared | boolean| `bool` |  | |  |  |
| cloud18SlaProvisionTime | number| `float64` |  | |  |  |
| cloud18SlaRepairTime | number| `float64` |  | |  |  |
| cloud18SlaResponseTime | number| `float64` |  | |  |  |
| cloud18SponsorUserCredential | string| `string` |  | |  |  |
| cloud18SubDomain | string| `string` |  | |  |  |
| cloud18SubDomainZone | string| `string` |  | |  |  |
| cloud18SubscribedDbops | boolean| `bool` |  | |  |  |
| clusterHead | string| `string` |  | |  |  |
| compressBackups | boolean| `bool` |  | |  |  |
| dbServersBackupHosts | string| `string` |  | |  |  |
| dbServersConnectTimeout | integer| `int64` |  | |  |  |
| dbServersCredential | string| `string` |  | |  |  |
| dbServersHosts | string| `string` |  | |  |  |
| dbServersIgnoredHosts | string| `string` |  | |  |  |
| dbServersIgnoredReadonly | string| `string` |  | |  |  |
| dbServersLocality | string| `string` |  | |  |  |
| dbServersPreferedMaster | string| `string` |  | |  |  |
| dbServersReadTimeout | integer| `int64` |  | |  |  |
| dbServersStateChangeScript | string| `string` |  | |  |  |
| dbServersTlsCaCert | string| `string` |  | |  |  |
| dbServersTlsClientCert | string| `string` |  | |  |  |
| dbServersTlsClientKey | string| `string` |  | |  |  |
| dbServersTlsServerCert | string| `string` |  | |  |  |
| dbServersTlsServerKey | string| `string` |  | |  |  |
| dbServersUseGeneratedCert | boolean| `bool` |  | |  |  |
| delayStatCapture | boolean| `bool` |  | |  |  |
| delayStatRotate | integer| `int64` |  | |  |  |
| eeplicationRestartOnSqlLErrorMatch | string| `string` |  | |  |  |
| enterprise | boolean| `bool` |  | | used to talk to opensvc collector |  |
| extproxy | boolean| `bool` |  | |  |  |
| extproxyAddress | string| `string` |  | |  |  |
| failoverAtSync | boolean| `bool` |  | |  |  |
| failoverCheckDelayStat | boolean| `bool` |  | |  |  |
| failoverEventScheduler | boolean| `bool` |  | |  |  |
| failoverEventStatus | boolean| `bool` |  | |  |  |
| failoverFalsePositiveExternal | boolean| `bool` |  | |  |  |
| failoverFalsePositiveExternalPort | integer| `int64` |  | |  |  |
| failoverFalsePositiveHeartbeat | boolean| `bool` |  | |  |  |
| failoverFalsePositiveHeartbeatTimeout | integer| `int64` |  | |  |  |
| failoverFalsePositiveMaxscale | boolean| `bool` |  | |  |  |
| failoverFalsePositiveMaxscaleTimeout | integer| `int64` |  | |  |  |
| failoverFalsePositivePingCounter | integer| `int64` |  | |  |  |
| failoverLimit | integer| `int64` |  | |  |  |
| failoverLogFileKeep | integer| `int64` |  | |  |  |
| failoverMaxSlaveDelay | integer| `int64` |  | |  |  |
| failoverMdevCheck | boolean| `bool` |  | |  |  |
| failoverMdevLevel | string| `string` |  | |  |  |
| failoverMode | string| `string` |  | |  |  |
| failoverPostScript | string| `string` |  | |  |  |
| failoverPreScript | string| `string` |  | |  |  |
| failoverReadOnlyState | boolean| `bool` |  | |  |  |
| failoverResetTime | integer| `int64` |  | |  |  |
| failoverRestartUnsafe | boolean| `bool` |  | |  |  |
| failoverSemisyncState | boolean| `bool` |  | |  |  |
| failoverSuperReadOnlyState | boolean| `bool` |  | |  |  |
| failoverSwithToPrefered | boolean| `bool` |  | |  |  |
| failoverTimeLimit | integer| `int64` |  | |  |  |
| forceBinlogAnnotate | boolean| `bool` |  | |  |  |
| forceBinlogChecksum | boolean| `bool` |  | |  |  |
| forceBinlogCompress | boolean| `bool` |  | |  |  |
| forceBinlogPurge | boolean| `bool` |  | |  |  |
| forceBinlogPurgeMinReplica | integer| `int64` |  | |  |  |
| forceBinlogPurgeOnRestore | boolean| `bool` |  | |  |  |
| forceBinlogPurgeReplicas | boolean| `bool` |  | |  |  |
| forceBinlogPurgeTotalSize | integer| `int64` |  | |  |  |
| forceBinlogRow | boolean| `bool` |  | |  |  |
| forceBinlogSlowqueries | boolean| `bool` |  | |  |  |
| forceDiskRelaylogSizeLimit | boolean| `bool` |  | |  |  |
| forceDiskRelaylogSizeLimitSize | integer| `int64` |  | |  |  |
| forceInmemoryBinlogCacheSize | boolean| `bool` |  | |  |  |
| forceNoslaveBehind | boolean| `bool` |  | |  |  |
| forceSlaveGtidMode | boolean| `bool` |  | |  |  |
| forceSlaveGtidModeStrict | boolean| `bool` |  | |  |  |
| forceSlaveHeartbeat | boolean| `bool` |  | |  |  |
| forceSlaveHeartbeatRetry | integer| `int64` |  | |  |  |
| forceSlaveHeartbeatTime | integer| `int64` |  | |  |  |
| forceSlaveIdempotent | boolean| `bool` |  | |  |  |
| forceSlaveNoGtidMode | boolean| `bool` |  | |  |  |
| forceSlaveParallelMode | string| `string` |  | |  |  |
| forceSlaveReadonly | boolean| `bool` |  | |  |  |
| forceSlaveSemisync | boolean| `bool` |  | |  |  |
| forceSlaveStrict | boolean| `bool` |  | |  |  |
| forceSyncBinlog | boolean| `bool` |  | |  |  |
| forceSyncInnodb | boolean| `bool` |  | |  |  |
| fullVersion | string| `string` |  | |  |  |
| gitMonitoringTicker | integer| `int64` |  | |  |  |
| gitUrl | string| `string` |  | |  |  |
| gitUrlPull | string| `string` |  | |  |  |
| gitUsername | string| `string` |  | |  |  |
| goArch | string| `string` |  | |  |  |
| goOS | string| `string` |  | |  |  |
| graphiteBlacklist | boolean| `bool` |  | |  |  |
| graphiteCarbonApiPort | integer| `int64` |  | |  |  |
| graphiteCarbonHost | string| `string` |  | |  |  |
| graphiteCarbonLinkPort | integer| `int64` |  | |  |  |
| graphiteCarbonPicklePort | integer| `int64` |  | |  |  |
| graphiteCarbonPort | integer| `int64` |  | |  |  |
| graphiteCarbonPprofPort | integer| `int64` |  | |  |  |
| graphiteCarbonServerPort | integer| `int64` |  | |  |  |
| graphiteEmbedded | boolean| `bool` |  | |  |  |
| graphiteMetrics | boolean| `bool` |  | |  |  |
| graphiteWhitelist | boolean| `bool` |  | |  |  |
| graphiteWhitelistTemplate | string| `string` |  | |  |  |
| haproxy | boolean| `bool` |  | |  |  |
| haproxyAPIPort | integer| `int64` |  | |  |  |
| haproxyAPIReadBackend | string| `string` |  | |  |  |
| haproxyAPIWriteBackend | string| `string` |  | |  |  |
| haproxyBinaryPath | string| `string` |  | |  |  |
| haproxyDebug | boolean| `bool` |  | |  |  |
| haproxyIpReadBind | string| `string` |  | |  |  |
| haproxyIpWriteBind | string| `string` |  | |  |  |
| haproxyJanitorWeights | string| `string` |  | |  |  |
| haproxyLogLevel | integer| `int64` |  | |  |  |
| haproxyMode | string| `string` |  | |  |  |
| haproxyPassword | string| `string` |  | |  |  |
| haproxyReadPort | integer| `int64` |  | |  |  |
| haproxyServers | string| `string` |  | |  |  |
| haproxyServers-ipv6 | string| `string` |  | |  |  |
| haproxyStatPort | integer| `int64` |  | |  |  |
| haproxyWritePort | integer| `int64` |  | |  |  |
| haproxylUser | string| `string` |  | |  |  |
| heartbeatTable | boolean| `bool` |  | |  |  |
| http-use-react | boolean| `bool` |  | |  |  |
| httpAuth | boolean| `bool` |  | |  |  |
| httpBindAdress | string| `string` |  | |  |  |
| httpBootstrapButton | boolean| `bool` |  | |  |  |
| httpPort | string| `string` |  | |  |  |
| httpRefreshInterval | integer| `int64` |  | |  |  |
| httpRoot | string| `string` |  | |  |  |
| httpServer | boolean| `bool` |  | |  |  |
| httpSessionLifetime | integer| `int64` |  | |  |  |
| interactive | boolean| `bool` |  | |  |  |
| jobLogBatchSize | integer| `int64` |  | |  |  |
| kubeConfig | string| `string` |  | |  |  |
| logBackupStream | boolean| `bool` |  | |  |  |
| logBackupStreamLevel | integer| `int64` |  | |  |  |
| logBinlogPurge | boolean| `bool` |  | |  |  |
| logBinlogPurgeLevel | integer| `int64` |  | |  |  |
| logConfigLoad | boolean| `bool` |  | |  |  |
| logConfigLoadLevel | integer| `int64` |  | |  |  |
| logFile | string| `string` |  | |  |  |
| logFileLevel | integer| `int64` |  | |  |  |
| logGit | boolean| `bool` |  | |  |  |
| logGitLevel | integer| `int64` |  | |  |  |
| logGraphite | boolean| `bool` |  | |  |  |
| logGraphiteLevel | integer| `int64` |  | |  |  |
| logHeartbeat | boolean| `bool` |  | |  |  |
| logHeartbeatLevel | integer| `int64` |  | |  |  |
| logLevel | integer| `int64` |  | |  |  |
| logOrchestrator | boolean| `bool` |  | |  |  |
| logOrchestratorLevel | integer| `int64` |  | |  |  |
| logProxy | boolean| `bool` |  | |  |  |
| logProxyLevel | integer| `int64` |  | |  |  |
| logRotateMaxAge | integer| `int64` |  | |  |  |
| logRotateMaxBackup | integer| `int64` |  | |  |  |
| logRotateMaxSize | integer| `int64` |  | |  |  |
| logSqlInMonitoring | boolean| `bool` |  | |  |  |
| logSst | boolean| `bool` |  | | internal replication-manager sst |  |
| logSstLevel | integer| `int64` |  | | internal replication-manager sst |  |
| logSyslog | boolean| `bool` |  | |  |  |
| logTask | boolean| `bool` |  | |  |  |
| logTaskLevel | integer| `int64` |  | |  |  |
| logTopology | boolean| `bool` |  | |  |  |
| logTopologyLevel | integer| `int64` |  | |  |  |
| logVault | boolean| `bool` |  | |  |  |
| logVaultLevel | integer| `int64` |  | |  |  |
| logWriterElection | boolean| `bool` |  | |  |  |
| logWriterElectionLevel | integer| `int64` |  | |  |  |
| mailFrom | string| `string` |  | |  |  |
| mailSmtpAddr | string| `string` |  | |  |  |
| mailSmtpPassword | string| `string` |  | |  |  |
| mailSmtpTlsSkipVerify | boolean| `bool` |  | |  |  |
| mailSmtpUser | string| `string` |  | |  |  |
| mailTo | string| `string` |  | |  |  |
| maxscale | boolean| `bool` |  | |  |  |
| maxscaleBinlog | boolean| `bool` |  | |  |  |
| maxscaleBinlogPort | integer| `int64` |  | |  |  |
| maxscaleDebug | boolean| `bool` |  | |  |  |
| maxscaleDisableMonitor | boolean| `bool` |  | |  |  |
| maxscaleGetInfoMethod | string| `string` |  | |  |  |
| maxscaleJanitorWeights | string| `string` |  | |  |  |
| maxscaleLogLevel | integer| `int64` |  | |  |  |
| maxscaleMaxinfoPort | integer| `int64` |  | |  |  |
| maxscalePass | string| `string` |  | |  |  |
| maxscalePort | string| `string` |  | |  |  |
| maxscaleReadPort | integer| `int64` |  | |  |  |
| maxscaleReadWritePort | integer| `int64` |  | |  |  |
| maxscaleServerMatchPort | boolean| `bool` |  | |  |  |
| maxscaleServers | string| `string` |  | |  |  |
| maxscaleServers-ipv6 | string| `string` |  | |  |  |
| maxscaleUser | string| `string` |  | |  |  |
| maxscaleWritePort | integer| `int64` |  | |  |  |
| maxscalemBinaryPath | string| `string` |  | |  |  |
| monitoringAddress | string| `string` |  | |  |  |
| monitoringAlertTrigger | string| `string` |  | |  |  |
| monitoringBasedir | string| `string` |  | |  |  |
| monitoringCapture | boolean| `bool` |  | |  |  |
| monitoringCaptureFileKeep | integer| `int64` |  | |  |  |
| monitoringCaptureTrigger | string| `string` |  | |  |  |
| monitoringCheckGrants | boolean| `bool` |  | |  |  |
| monitoringCloseStateScript | string| `string` |  | |  |  |
| monitoringConfdir | string| `string` |  | |  |  |
| monitoringConfdirBackup | string| `string` |  | |  |  |
| monitoringConfdirExtra | string| `string` |  | |  |  |
| monitoringDatadir | string| `string` |  | |  |  |
| monitoringDiskUsage | boolean| `bool` |  | |  |  |
| monitoringDiskUsagePct | integer| `int64` |  | |  |  |
| monitoringErreurLogLength | integer| `int64` |  | |  |  |
| monitoringIgnoreErrors | string| `string` |  | |  |  |
| monitoringInnoDBStatus | boolean| `bool` |  | |  |  |
| monitoringKeyPath | string| `string` |  | |  |  |
| monitoringKeyPathGitOverwrite | boolean| `bool` |  | |  |  |
| monitoringLongQueryLogLength | integer| `int64` |  | |  |  |
| monitoringLongQueryScript | string| `string` |  | |  |  |
| monitoringLongQueryTime | integer| `int64` |  | |  |  |
| monitoringLongQueryWithProcess | boolean| `bool` |  | |  |  |
| monitoringLongQueryWithTable | boolean| `bool` |  | |  |  |
| monitoringMergeConfigOnStart | boolean| `bool` |  | |  |  |
| monitoringOpenStateScript | string| `string` |  | |  |  |
| monitoringPause | boolean| `bool` |  | |  |  |
| monitoringPerformanceSchema | boolean| `bool` |  | |  |  |
| monitoringPlugins | boolean| `bool` |  | |  |  |
| monitoringProcesslist | boolean| `bool` |  | |  |  |
| monitoringQueries | boolean| `bool` |  | |  |  |
| monitoringQueryRules | boolean| `bool` |  | |  |  |
| monitoringQueryTimeout | integer| `int64` |  | |  |  |
| monitoringRestoreConfigOnStart | boolean| `bool` |  | |  |  |
| monitoringSSLCert | string| `string` |  | |  |  |
| monitoringSSLKey | string| `string` |  | |  |  |
| monitoringSaveConfig | boolean| `bool` |  | |  |  |
| monitoringScheduler | boolean| `bool` |  | |  |  |
| monitoringSchemaChange | boolean| `bool` |  | |  |  |
| monitoringSchemaChangeScript | string| `string` |  | |  |  |
| monitoringSharedir | string| `string` |  | |  |  |
| monitoringSocket | string| `string` |  | |  |  |
| monitoringTenant | string| `string` |  | |  |  |
| monitoringTicker | integer| `int64` |  | |  |  |
| monitoringTunnelCredential | string| `string` |  | |  |  |
| monitoringTunnelHost | string| `string` |  | |  |  |
| monitoringTunnelKeyPath | string| `string` |  | |  |  |
| monitoringVariableDiff | boolean| `bool` |  | |  |  |
| monitoringWaitRetry | integer| `int64` |  | |  |  |
| monitoringWriteHeartbeat | boolean| `bool` |  | |  |  |
| monitoringWriteHeartbeatCredential | string| `string` |  | |  |  |
| myproxy | boolean| `bool` |  | |  |  |
| myproxyDebug | boolean| `bool` |  | |  |  |
| myproxyLogLevel | integer| `int64` |  | |  |  |
| myproxyPassword | string| `string` |  | |  |  |
| myproxyPort | integer| `int64` |  | |  |  |
| myproxyUser | string| `string` |  | |  |  |
| mysqlrouter | boolean| `bool` |  | |  |  |
| mysqlrouterDebug | boolean| `bool` |  | |  |  |
| mysqlrouterJanitorWeights | string| `string` |  | |  |  |
| mysqlrouterLogLevel | integer| `int64` |  | |  |  |
| mysqlrouterPass | string| `string` |  | |  |  |
| mysqlrouterPort | string| `string` |  | |  |  |
| mysqlrouterReadPort | integer| `int64` |  | |  |  |
| mysqlrouterReadWritePort | integer| `int64` |  | |  |  |
| mysqlrouterServers | string| `string` |  | |  |  |
| mysqlrouterUser | string| `string` |  | |  |  |
| mysqlrouterWritePort | integer| `int64` |  | |  |  |
| onpremiseSsh | boolean| `bool` |  | |  |  |
| onpremiseSshCredential | string| `string` |  | |  |  |
| onpremiseSshDbJobScript | string| `string` |  | |  |  |
| onpremiseSshPort | integer| `int64` |  | |  |  |
| onpremiseSshPrivateKey | string| `string` |  | |  |  |
| onpremiseSshStartDbScript | string| `string` |  | |  |  |
| onpremiseSshStartProxyScript | string| `string` |  | |  |  |
| onpremiseSshStopProxyScript | string| `string` |  | |  |  |
| opensvcAdminUser | string| `string` |  | |  |  |
| opensvcCodeapp | string| `string` |  | |  |  |
| opensvcCollectorAccount | string| `string` |  | |  |  |
| opensvcHost | string| `string` |  | |  |  |
| opensvcP12Certificate | string| `string` |  | |  |  |
| opensvcP12Secret | string| `string` |  | |  |  |
| opensvcRegister | boolean| `bool` |  | |  |  |
| opensvcUseCollectorApi | boolean| `bool` |  | |  |  |
| opensvcUser | string| `string` |  | |  |  |
| optimizeUseSql | boolean| `bool` |  | |  |  |
| printDelayStat | boolean| `bool` |  | |  |  |
| printDelayStatHistory | boolean| `bool` |  | |  |  |
| printDelayStatInterval | integer| `int64` |  | |  |  |
| provDBApplyDynamicConfig | boolean| `bool` |  | |  |  |
| provDBCompliance | string| `string` |  | |  |  |
| provDBForceWriteConfig | boolean| `bool` |  | |  |  |
| provDbAgents | string| `string` |  | |  |  |
| provDbBinaryBasedir | string| `string` |  | |  |  |
| provDbBinaryInTarball | boolean| `bool` |  | |  |  |
| provDbBinaryTarballName | string| `string` |  | |  |  |
| provDbBootstrapScript | string| `string` |  | |  |  |
| provDbCleanupScript | string| `string` |  | |  |  |
| provDbClientBasedir | string| `string` |  | |  |  |
| provDbCpuCores | string| `string` |  | |  |  |
| provDbDatadirVersion | string| `string` |  | |  |  |
| provDbDiskDevice | string| `string` |  | |  |  |
| provDbDiskDockerSize | string| `string` |  | |  |  |
| provDbDiskFs | string| `string` |  | |  |  |
| provDbDiskFsCompress | string| `string` |  | |  |  |
| provDbDiskIops | string| `string` |  | |  |  |
| provDbDiskIopsLatency | string| `string` |  | |  |  |
| provDbDiskPool | string| `string` |  | |  |  |
| provDbDiskSize | string| `string` |  | |  |  |
| provDbDiskSnapshotKeep | integer| `int64` |  | |  |  |
| provDbDiskSnapshotPreferedMaster | boolean| `bool` |  | |  |  |
| provDbDiskSystemSize | string| `string` |  | |  |  |
| provDbDiskTempSize | string| `string` |  | |  |  |
| provDbDiskType | string| `string` |  | |  |  |
| provDbDockerImg | string| `string` |  | |  |  |
| provDbDomain | string| `string` |  | |  |  |
| provDbExpireLogDays | integer| `int64` |  | |  |  |
| provDbLoadCsv | string| `string` |  | |  |  |
| provDbLoadSql | string| `string` |  | |  |  |
| provDbMaxConnections | integer| `int64` |  | |  |  |
| provDbMemory | string| `string` |  | |  |  |
| provDbMemorySharedPct | string| `string` |  | |  |  |
| provDbMemoryThreadedPct | string| `string` |  | |  |  |
| provDbNetGateway | string| `string` |  | |  |  |
| provDbNetIface | string| `string` |  | |  |  |
| provDbNetMask | string| `string` |  | |  |  |
| provDbServiceType | string| `string` |  | |  |  |
| provDbStartScript | string| `string` |  | |  |  |
| provDbStopScript | string| `string` |  | |  |  |
| provDbTags | string| `string` |  | |  |  |
| provDbVolumeData | string| `string` |  | |  |  |
| provDbVolumeDocker | string| `string` |  | |  |  |
| provDockerDaemonPrivate | boolean| `bool` |  | |  |  |
| provNetCni | boolean| `bool` |  | |  |  |
| provNetCniCluster | string| `string` |  | |  |  |
| provOrchestrator | string| `string` |  | |  |  |
| provOrchestratorCluster | string| `string` |  | |  |  |
| provOrchestratorEnable | string| `string` |  | |  |  |
| provProxyAgents | string| `string` |  | |  |  |
| provProxyAgentsFailover | string| `string` |  | |  |  |
| provProxyBootstrapScript | string| `string` |  | |  |  |
| provProxyCleanupScript | string| `string` |  | |  |  |
| provProxyCompliance | string| `string` |  | |  |  |
| provProxyCpuCores | string| `string` |  | |  |  |
| provProxyDiskDevice | string| `string` |  | |  |  |
| provProxyDiskFs | string| `string` |  | |  |  |
| provProxyDiskPool | string| `string` |  | |  |  |
| provProxyDiskSize | string| `string` |  | |  |  |
| provProxyDiskType | string| `string` |  | |  |  |
| provProxyDockerHaproxyImg | string| `string` |  | |  |  |
| provProxyDockerMaxscaleImg | string| `string` |  | |  |  |
| provProxyDockerMysqlrouterImg | string| `string` |  | |  |  |
| provProxyDockerProxysqlImg | string| `string` |  | |  |  |
| provProxyDockerShardproxyImg | string| `string` |  | |  |  |
| provProxyMemory | string| `string` |  | |  |  |
| provProxyNetGateway | string| `string` |  | |  |  |
| provProxyNetIface | string| `string` |  | |  |  |
| provProxyNetMask | string| `string` |  | |  |  |
| provProxyRouteAddr | string| `string` |  | |  |  |
| provProxyRouteMask | string| `string` |  | |  |  |
| provProxyRoutePolicy | string| `string` |  | |  |  |
| provProxyRoutePort | string| `string` |  | |  |  |
| provProxyServiceType | string| `string` |  | |  |  |
| provProxyStartScript | string| `string` |  | |  |  |
| provProxyStopScript | string| `string` |  | |  |  |
| provProxyTags | string| `string` |  | |  |  |
| provProxyVolumeData | string| `string` |  | |  |  |
| provSerialized | boolean| `bool` |  | |  |  |
| provServicePlan | string| `string` |  | |  |  |
| provServicePlanRegistry | string| `string` |  | |  |  |
| provSphinxAgents | string| `string` |  | |  |  |
| provSphinxCpuCores | string| `string` |  | |  |  |
| provSphinxDiskDevice | string| `string` |  | |  |  |
| provSphinxDiskFs | string| `string` |  | |  |  |
| provSphinxDiskPool | string| `string` |  | |  |  |
| provSphinxDiskSize | string| `string` |  | |  |  |
| provSphinxDiskType | string| `string` |  | |  |  |
| provSphinxDockerImg | string| `string` |  | |  |  |
| provSphinxMaxChildrens | string| `string` |  | |  |  |
| provSphinxMemory | string| `string` |  | |  |  |
| provSphinxReindexSchedule | string| `string` |  | |  |  |
| provSphinxServiceType | string| `string` |  | |  |  |
| provSphinxTags | string| `string` |  | |  |  |
| provTlsServerCa | string| `string` |  | |  |  |
| provTlsServerCert | string| `string` |  | |  |  |
| provTlsServerKey | string| `string` |  | |  |  |
| proxyServersBackendCompression | boolean| `bool` |  | |  |  |
| proxyServersBackendMaxConnections | integer| `int64` |  | |  |  |
| proxyServersBackendMaxReplicationLag | integer| `int64` |  | |  |  |
| proxyServersChangeStateScript | string| `string` |  | |  |  |
| proxyServersReadOnMaster | boolean| `bool` |  | |  |  |
| proxyServersReadOnMasterNoSlave | boolean| `bool` |  | |  |  |
| proxyjanitorAdminPort | string| `string` |  | |  |  |
| proxyjanitorBinaryPath | string| `string` |  | |  |  |
| proxyjanitorDebug | boolean| `bool` |  | |  |  |
| proxyjanitorLogLevel | integer| `int64` |  | |  |  |
| proxyjanitorPassword | string| `string` |  | |  |  |
| proxyjanitorPort | string| `string` |  | |  |  |
| proxyjanitorServers | string| `string` |  | |  |  |
| proxyjanitorServers-ipv6 | string| `string` |  | |  |  |
| proxyjanitorUser | string| `string` |  | |  |  |
| proxysql | boolean| `bool` |  | |  |  |
| proxysqlAdminPort | string| `string` |  | |  |  |
| proxysqlBinaryPath | string| `string` |  | |  |  |
| proxysqlBootstrap | boolean| `bool` |  | |  |  |
| proxysqlBootstrapHostgroups | boolean| `bool` |  | |  |  |
| proxysqlBootstrapQueryRules | boolean| `bool` |  | |  |  |
| proxysqlBootstrapVariables | boolean| `bool` |  | |  |  |
| proxysqlBootstrapyUsers | boolean| `bool` |  | |  |  |
| proxysqlDebug | boolean| `bool` |  | |  |  |
| proxysqlJanitorWeights | string| `string` |  | |  |  |
| proxysqlLogLevel | integer| `int64` |  | |  |  |
| proxysqlMultiplexing | boolean| `bool` |  | |  |  |
| proxysqlPassword | string| `string` |  | |  |  |
| proxysqlPort | string| `string` |  | |  |  |
| proxysqlReaderHostgroup | string| `string` |  | |  |  |
| proxysqlSaveToDisk | boolean| `bool` |  | |  |  |
| proxysqlServers | string| `string` |  | |  |  |
| proxysqlServersIpv6 | string| `string` |  | |  |  |
| proxysqlUser | string| `string` |  | |  |  |
| proxysqlWriterHostgroup | string| `string` |  | |  |  |
| registryConsul | boolean| `bool` |  | |  |  |
| registryConsulCredential | string| `string` |  | |  |  |
| registryConsulDebug | boolean| `bool` |  | |  |  |
| registryConsulLogLevel | integer| `int64` |  | |  |  |
| registryConsulToken | string| `string` |  | |  |  |
| registryJanitorWeights | string| `string` |  | |  |  |
| registryServers | string| `string` |  | |  |  |
| replicationActivePassive | boolean| `bool` |  | |  |  |
| replicationCredential | string| `string` |  | |  |  |
| replicationDelayedHosts | string| `string` |  | |  |  |
| replicationDelayedTime | integer| `int64` |  | |  |  |
| replicationDynamicTopology | boolean| `bool` |  | |  |  |
| replicationErrorScript | string| `string` |  | |  |  |
| replicationMasterConnectRetry | integer| `int64` |  | |  |  |
| replicationMasterSlaveNeverRelay | boolean| `bool` |  | |  |  |
| replicationMasterSlavePgLogical | boolean| `bool` |  | |  |  |
| replicationMasterSlavePgStream | boolean| `bool` |  | |  |  |
| replicationMultiMaster | boolean| `bool` |  | |  |  |
| replicationMultiMasterConcurrentWrite | boolean| `bool` |  | |  |  |
| replicationMultiMasterGrouprep | boolean| `bool` |  | |  |  |
| replicationMultiMasterGrouprepPort | integer| `int64` |  | |  |  |
| replicationMultiMasterRing | boolean| `bool` |  | |  |  |
| replicationMultiMasterRingUnsafe | boolean| `bool` |  | |  |  |
| replicationMultiMasterWsrep | boolean| `bool` |  | |  |  |
| replicationMultiMasterWsrepPort | integer| `int64` |  | |  |  |
| replicationMultiMasterWsrepSSTMethod | string| `string` |  | |  |  |
| replicationMultiTierSlave | boolean| `bool` |  | |  |  |
| replicationMultisourceHeadClusters | string| `string` |  | |  |  |
| replicationSourceName | string| `string` |  | |  |  |
| replicationUseSsl | boolean| `bool` |  | |  |  |
| schedulerAlertDisable | boolean| `bool` |  | |  |  |
| schedulerAlertDisableCron | string| `string` |  | |  |  |
| schedulerAlertDisableTime | integer| `int64` |  | |  |  |
| schedulerDatabaseLogsTableKeep | integer| `int64` |  | |  |  |
| schedulerDbServersAnalyze | boolean| `bool` |  | |  |  |
| schedulerDbServersAnalyzeCron | string| `string` |  | |  |  |
| schedulerDbServersLogicalBackup | boolean| `bool` |  | |  |  |
| schedulerDbServersLogicalBackupCron | string| `string` |  | |  |  |
| schedulerDbServersLogs | boolean| `bool` |  | |  |  |
| schedulerDbServersLogsCron | string| `string` |  | |  |  |
| schedulerDbServersLogsTableRotate | boolean| `bool` |  | |  |  |
| schedulerDbServersLogsTableRotateCron | string| `string` |  | |  |  |
| schedulerDbServersOptimize | boolean| `bool` |  | |  |  |
| schedulerDbServersOptimizeCron | string| `string` |  | |  |  |
| schedulerDbServersPhysicalBackup | boolean| `bool` |  | |  |  |
| schedulerDbServersPhysicalBackupCron | string| `string` |  | |  |  |
| schedulerDbServersReceiverPorts | string| `string` |  | |  |  |
| schedulerDbServersReceiverUseSSL | boolean| `bool` |  | |  |  |
| schedulerDbServersSenderPorts | string| `string` |  | |  |  |
| schedulerJobsSsh | boolean| `bool` |  | |  |  |
| schedulerJobsSshCron | string| `string` |  | |  |  |
| schedulerRollingReprov | boolean| `bool` |  | |  |  |
| schedulerRollingReprovCron | string| `string` |  | |  |  |
| schedulerRollingRestart | boolean| `bool` |  | |  |  |
| schedulerRollingRestartCron | string| `string` |  | |  |  |
| schedulerSlaRotateCron | string| `string` |  | |  |  |
| shardproxy | boolean| `bool` |  | |  |  |
| shardproxyCopyGrants | boolean| `bool` |  | |  |  |
| shardproxyCredential | string| `string` |  | |  |  |
| shardproxyDebug | boolean| `bool` |  | |  |  |
| shardproxyIgnoreTables | string| `string` |  | |  |  |
| shardproxyJanitorWeights | string| `string` |  | |  |  |
| shardproxyLoadSystem | boolean| `bool` |  | |  |  |
| shardproxyLogLevel | integer| `int64` |  | |  |  |
| shardproxyServers | string| `string` |  | |  |  |
| shardproxyServers-ipv6 | string| `string` |  | |  |  |
| shardproxyUniversalTables | string| `string` |  | |  |  |
| slaposConfig | string| `string` |  | |  |  |
| slaposDbPartitions | string| `string` |  | |  |  |
| slaposHaproxyPartitions | string| `string` |  | |  |  |
| slaposMaxscalePartitions | string| `string` |  | |  |  |
| slaposProxysqlPartitions | string| `string` |  | |  |  |
| slaposShardproxyPartitions | string| `string` |  | |  |  |
| slaposSphinxPartitions | string| `string` |  | |  |  |
| sphinx | boolean| `bool` |  | |  |  |
| sphinxConfig | string| `string` |  | |  |  |
| sphinxDebug | boolean| `bool` |  | |  |  |
| sphinxJanitorWeights | string| `string` |  | |  |  |
| sphinxLogLevel | integer| `int64` |  | |  |  |
| sphinxPort | string| `string` |  | |  |  |
| sphinxServers | string| `string` |  | |  |  |
| sphinxServers-ipv6 | string| `string` |  | |  |  |
| sphinxSqlPort | string| `string` |  | |  |  |
| sstSendBuffer | integer| `int64` |  | |  |  |
| switchoverAtEqualGtid | boolean| `bool` |  | |  |  |
| switchoverAtSync | boolean| `bool` |  | |  |  |
| switchoverDecreaseMaxConn | boolean| `bool` |  | |  |  |
| switchoverDecreaseMaxConnValue | integer| `int64` |  | |  |  |
| switchoverLowerRelease | boolean| `bool` |  | |  |  |
| switchoverMaxSlaveDelay | integer| `int64` |  | |  |  |
| switchoverSlaveWaitCatch | boolean| `bool` |  | |  |  |
| switchoverWaitKill | integer| `int64` |  | |  |  |
| switchoverWaitRouteChange | integer| `int64` |  | |  |  |
| switchoverWaitTrx | integer| `int64` |  | |  |  |
| switchoverWaitWriteQuery | integer| `int64` |  | |  |  |
| sysbenchBinaryPath | string| `string` |  | |  |  |
| sysbenchBinaryTest | string| `string` |  | |  |  |
| sysbenchScale | integer| `int64` |  | |  |  |
| sysbenchTables | integer| `int64` |  | |  |  |
| sysbenchThreads | integer| `int64` |  | |  |  |
| sysbenchTime | integer| `int64` |  | |  |  |
| sysbenchV1 | boolean| `bool` |  | |  |  |
| test | boolean| `bool` |  | |  |  |
| testInjectTraffic | boolean| `bool` |  | |  |  |
| topologyTarget | string| `string` |  | |  |  |
| vaultAuth | string| `string` |  | |  |  |
| vaultMode | string| `string` |  | |  |  |
| vaultMount | string| `string` |  | |  |  |
| vaultRoleId | string| `string` |  | |  |  |
| vaultSecretId | string| `string` |  | |  |  |
| vaultServerAddr | string| `string` |  | |  |  |
| vaultToken | string| `string` |  | |  |  |
| verbose | boolean| `bool` |  | |  |  |
| version | string| `string` |  | |  |  |
| withEmbed | string| `string` |  | |  |  |
| withTarball | string| `string` |  | |  |  |



### 3.43 <span id="gtid-gtid"></span> gtid.Gtid


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| domainId | integer| `int64` |  | |  |  |
| seqNo | integer| `int64` |  | |  |  |
| serverId | integer| `int64` |  | |  |  |



### 3.44 <span id="opensvc-addr"></span> opensvc.Addr


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| addr | string| `string` |  | |  |  |
| addr_type | string| `string` |  | |  |  |
| intf | string| `string` |  | |  |  |
| mask | string| `string` |  | |  |  |
| net_broadcast | string| `string` |  | |  |  |
| net_gateway | string| `string` |  | |  |  |
| net_name | string| `string` |  | |  |  |
| net_network | string| `string` |  | |  |  |



### 3.45 <span id="opensvc-host"></span> opensvc.Host


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| cpu_cores | integer| `int64` |  | |  |  |
| cpu_freq | integer| `int64` |  | |  |  |
| id | integer| `int64` |  | |  |  |
| ips | [][OpensvcAddr](#opensvc-addr)| `[]*OpensvcAddr` |  | |  |  |
| mem_bytes | integer| `int64` |  | |  |  |
| node_id | string| `string` |  | |  |  |
| nodename | string| `string` |  | |  |  |
| os_kernel | string| `string` |  | |  |  |
| os_name | string| `string` |  | |  |  |
| svc | [][OpensvcService](#opensvc-service)| `[]*OpensvcService` |  | |  |  |



### 3.46 <span id="opensvc-service"></span> opensvc.Service


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| id | integer| `int64` |  | |  |  |
| svc_id | string| `string` |  | |  |  |
| svc_status | string| `string` |  | |  |  |
| svcname | string| `string` |  | |  |  |
| updated | string| `string` |  | |  |  |



### 3.47 <span id="os-process"></span> os.Process


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| pid | integer| `int64` |  | |  |  |



### 3.48 <span id="regexp-regexp"></span> regexp.Regexp


  

[interface{}](#interface)

### 3.49 <span id="repmanv3-backup-stat"></span> repmanv3.BackupStat


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| total_blob_count | integer| `int64` |  | |  |  |
| total_file_count | integer| `int64` |  | |  |  |
| total_size | integer| `int64` |  | |  |  |



### 3.50 <span id="repmanv3-tag"></span> repmanv3.Tag


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| category | string| `string` |  | |  |  |
| id | integer| `int64` |  | |  |  |
| name | string| `string` |  | |  |  |



### 3.51 <span id="s18log-http-log"></span> s18log.HttpLog


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| buffer | [][S18logHTTPMessage](#s18log-http-message)| `[]*S18logHTTPMessage` |  | |  |  |
| len | integer| `int64` |  | |  |  |
| line | integer| `int64` |  | |  |  |



### 3.52 <span id="s18log-http-message"></span> s18log.HttpMessage


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| group | string| `string` |  | |  |  |
| level | string| `string` |  | |  |  |
| text | string| `string` |  | |  |  |
| timestamp | string| `string` |  | |  |  |



### 3.53 <span id="server-api-response"></span> server.ApiResponse


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| data | string| `string` |  | |  |  |
| success | boolean| `bool` |  | |  |  |



### 3.54 <span id="server-credential-mail-form"></span> server.CredentialMailForm


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| type | string| `string` |  | |  |  |
| username | string| `string` |  | |  |  |



### 3.55 <span id="server-decoded-data"></span> server.DecodedData


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| data | string| `string` |  | |  |  |



### 3.56 <span id="server-heartbeat"></span> server.Heartbeat


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| cluster | string| `string` |  | |  |  |
| failed | integer| `int64` |  | |  |  |
| hosts | integer| `int64` |  | |  |  |
| id | integer| `int64` |  | |  |  |
| master | string| `string` |  | |  |  |
| secret | string| `string` |  | |  |  |
| status | string| `string` |  | |  |  |
| uuid | string| `string` |  | |  |  |



### 3.57 <span id="server-replication-manager"></span> server.ReplicationManager


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| agents | [][OpensvcHost](#opensvc-host)| `[]*OpensvcHost` |  | |  |  |
| arch | string| `string` |  | |  |  |
| backupBinlogList | map of boolean| `map[string]bool` |  | |  |  |
| backupLogicalList | map of boolean| `map[string]bool` |  | |  |  |
| backupPhysicalList | map of boolean| `map[string]bool` |  | |  |  |
| binlogParseList | map of boolean| `map[string]bool` |  | |  |  |
| canConnectVault | boolean| `bool` |  | |  |  |
| clusters | []string| `[]string` |  | |  |  |
| config | [GithubComSignal18ReplicationManagerConfigConfig](#github-com-signal18-replication-manager-config-config)| `GithubComSignal18ReplicationManagerConfigConfig` |  | |  |  |
| confs | map of [GithubComSignal18ReplicationManagerConfigConfig](#github-com-signal18-replication-manager-config-config)| `map[string]GithubComSignal18ReplicationManagerConfigConfig` |  | |  |  |
| cpuprofile | string| `string` |  | |  |  |
| fullVersion | string| `string` |  | |  |  |
| graphiteTemplateList | map of boolean| `map[string]bool` |  | |  |  |
| hasSavingConfigQueue | boolean| `bool` |  | |  |  |
| hostname | string| `string` |  | |  |  |
| isGitPull | boolean| `bool` |  | |  |  |
| isGitPush | boolean| `bool` |  | |  |  |
| isSavingConfig | boolean| `bool` |  | |  |  |
| logs | [S18logHTTPLog](#s18log-http-log)| `S18logHTTPLog` |  | |  |  |
| memprofile | string| `string` |  | |  |  |
| os | string| `string` |  | |  |  |
| osUser | [UserUser](#user-user)| `UserUser` |  | |  |  |
| partner | [ConfigPartner](#config-partner)| `ConfigPartner` |  | |  |  |
| partners | [][ConfigPartner](#config-partner)| `[]*ConfigPartner` |  | |  |  |
| serviceAcl | [][ConfigGrant](#config-grant)| `[]*ConfigGrant` |  | |  |  |
| serviceDisk | map of string| `map[string]string` |  | |  |  |
| serviceFS | map of boolean| `map[string]bool` |  | |  |  |
| serviceOrchestrators | [][ConfigConfigVariableType](#config-config-variable-type)| `[]*ConfigConfigVariableType` |  | |  |  |
| servicePlans | [][ConfigServicePlan](#config-service-plan)| `[]*ConfigServicePlan` |  | |  |  |
| servicePool | map of boolean| `map[string]bool` |  | |  |  |
| serviceRepos | [][ConfigDockerRepo](#config-docker-repo)| `[]*ConfigDockerRepo` |  | |  |  |
| serviceRoles | [][ConfigRole](#config-role)| `[]*ConfigRole` |  | |  |  |
| serviceTarballs | [][ConfigTarball](#config-tarball)| `[]*ConfigTarball` |  | |  |  |
| serviceVM | map of boolean| `map[string]bool` |  | |  |  |
| spitBrain | boolean| `bool` |  | |  |  |
| status | string| `string` |  | |  |  |
| termsDT | map of string| `map[string]string` |  | |  |  |
| tests | []string| `[]string` |  | |  |  |
| uuid | string| `string` |  | |  |  |
| version | string| `string` |  | |  |  |



### 3.58 <span id="server-token"></span> server.token


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| token | string| `string` |  | |  |  |



### 3.59 <span id="server-user-credentials"></span> server.userCredentials


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| password | string| `string` |  | |  |  |
| username | string| `string` |  | |  |  |



### 3.60 <span id="sql-null-int64"></span> sql.NullInt64


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| int64 | integer| `int64` |  | |  |  |
| valid | boolean| `bool` |  | | Valid is true if Int64 is not NULL |  |



### 3.61 <span id="sql-null-string"></span> sql.NullString


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| string | string| `string` |  | |  |  |
| valid | boolean| `bool` |  | | Valid is true if String is not NULL |  |



### 3.62 <span id="state-sla"></span> state.Sla


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| firsttime | integer| `int64` |  | |  |  |
| lasttime | integer| `int64` |  | |  |  |
| uptime | integer| `int64` |  | |  |  |
| uptimeFailable | integer| `int64` |  | |  |  |
| uptimeSemisync | integer| `int64` |  | |  |  |



### 3.63 <span id="state-state-http"></span> state.StateHttp


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| desc | string| `string` |  | |  |  |
| from | string| `string` |  | |  |  |
| number | string| `string` |  | |  |  |



### 3.64 <span id="state-state-machine"></span> state.StateMachine


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| discovered | boolean| `bool` |  | |  |  |
| inFailover | boolean| `bool` |  | |  |  |
| inSchemaMonitor | boolean| `bool` |  | |  |  |



### 3.65 <span id="user-user"></span> user.User


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| gid | string| `string` |  | | Gid is the primary group ID.</br>On POSIX systems, this is a decimal number representing the gid.</br>On Windows, this is a SID in a string format.</br>On Plan 9, this is the contents of /dev/user. |  |
| homeDir | string| `string` |  | | HomeDir is the path to the user's home directory (if they have one). |  |
| name | string| `string` |  | | Name is the user's real or display name.</br>It might be blank.</br>On POSIX systems, this is the first (or only) entry in the GECOS field</br>list.</br>On Windows, this is the user's display name.</br>On Plan 9, this is the contents of /dev/user. |  |
| uid | string| `string` |  | | Uid is the user ID.</br>On POSIX systems, this is a decimal number representing the uid.</br>On Windows, this is a security identifier (SID) in a string format.</br>On Plan 9, this is the contents of /dev/user. |  |
| username | string| `string` |  | | Username is the login name. |  |



### 3.66 <span id="version-version"></span> version.Version


  



**Properties**

| Name | Type | Go type | Required | Default | Description | Example |
|------|------|---------|:--------:| ------- |-------------|---------|
| dist | [VersionVersion](#version-version)| `VersionVersion` |  | |  |  |
| flavor | string| `string` |  | |  |  |
| major | integer| `int64` |  | |  |  |
| minor | integer| `int64` |  | |  |  |
| path | string| `string` |  | |  |  |
| release | integer| `int64` |  | |  |  |
| suffix | string| `string` |  | |  |  |


