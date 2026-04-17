---
title: Troubleshoot Crashes
taxonomy:
    category: docs
---

### Troubleshooting failover switchover

The log verbosity can be increase to get better understanding for the route cause of some issue
```
replication-manager-cli api  url=https://127.0.0.1:3000/api/clusters/cluster_name/settings/switch/verbosity
```

### Troubleshooting internal status

**replication-manager** track internal status inside following class settings, clusters, servers, master, slaves, crashes, alerts

You can explore those status via the show client per default JSON file have all classes but it can be filtered with the --get option  

```
replication-manager-cli show
```

You can attach the command result with the log file, the increase verbosity log file  to a signal18.io support ticket.  

### Troubleshoot Crashes

**replication-manager** (2.O) record a crash information in the replication-manager datadir for each failover or switchover. It helps the DBA to refer to it in case of later reporting. The binary log state of the elected master or his own replication state when he was still a master get lost when the traffic take over, such a backup is always good to have and save DBA time to create it manually.  

**replication-manager** (1.1) Save some binlog informations on crash when old master restart.
Before this happen one can query the current recorded elections via the API, the HTTP URL `/crashes` or form the client.
```
[
	{
		"URL": "127.0.0.1:3310",
		"FailoverMasterLogFile": "8228534449735335205-bin.000001",
		"FailoverMasterLogPos": "459",
		"FailoverSemiSyncSlaveStatus": true,
		"FailoverIOGtid": [
			{
				"DomainID": 0,
				"ServerID": 3310,
				"SeqNo": 1
			}
		],
		"ElectedMasterURL": "127.0.0.1:3311"
	}
]
```  

**replication-manager**  track  crashes under the data directory under the json file `cluster_name.json`

```
{
	"servers": "127.0.0.1:3310,127.0.0.1:3311",
	"crashes": [
		{
			"URL": "127.0.0.1:3310",
			"FailoverMasterLogFile": "8228534449735335205-bin.000001",
			"FailoverMasterLogPos": "459",
			"FailoverSemiSyncSlaveStatus": true,
			"FailoverIOGtid": [
				{
					"DomainID": 0,
					"ServerID": 3310,
					"SeqNo": 1
				}
			],
			"ElectedMasterURL": "127.0.0.1:3311"
		}
	],
	"sla": {
		"firsttime": 1506375653,
		"uptime": 170132,
		"uptimeFailable": 170119,
		"uptimeSemisync": 401
	}
}
```

Those internal states are saved every 60 seconds to disk and reloaded on startup.

**replication-manager** will clear the crashes informations when the all cluster topology is back to no ERROR, some stopped slave can still need those informations to rejoin correctly the cluster.  

**replication-manager** when old master rejoin,  binlogs used for rejoin are saved with extra election informations in a directory `/datadir/crash*Unixtime*`
