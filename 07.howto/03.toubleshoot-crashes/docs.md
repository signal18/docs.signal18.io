---
title: Troubleshoot Crashes
---

### Troubleshoot Crashes

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
