---
title: Collector
taxonomy:
    category: docs
---

## Setup SAS Collector

Request your account or eval license file to sigal18.io team after having register to (https://signal18.io)

Once receive copy the provided file in the share/opensvc or /etc/replication-manager directory of replication-manager, you should be good to go to setup your cluster agents  

## Setup On-promise Collector

Pre requirements
```  
apt-get install -y python
apt-get install -y net-tools
apt-get install -y psmisc
```

To install a collector, the first step is to install the OpenSVC agent that will also be used later on the other cluster nodes. This first agent will be in charge to manage the deployment of the collector as a docker service.

Follow the instructions to install the agent and the collector on https://docs.opensvc.com/collector.evaluation.html

When done, a first run of replication-manager is needed to configure the collector and load the playbook:

```
./replication-manager-pro monitor --opensvc-register
```

This run gives similar output to:
```
2017/07/13 11:45:43 {
	"info": "change group replication-manager: privilege: False => F",
	"data": [
		{
			"privilege": false,
			"role": "replication-manager",
			"id": 317,
			"description": null
		}
	]
}

2017/07/13 11:45:43 INFO  https://192.168.1.101:443/init/rest/api/groups?props=role,id&filters[]=privilege T&filters[]=role !manager&limit=0
2017/07/13 11:45:43 INFO  https://192.168.1.101:443/init/rest/api/users/122/groups/23
2017/07/13 11:45:44 {
```

At startup it creates a replication-manager user with password `mariadb`, a group and application group named `mariadb` is affected to this user. The needed roles and grants for this group are created.

repication-manager loads some playbooks call compliance in OpenSVC from the replication share/opensvc directory.

Some compliance hardcoded rules are also served by replication-manager to the agents. They are compiled in a tar.gz file named `current` in the `share/opensvc` subdirectory including all the modules of the directory `share/opensvc/compliance`.

Those rules can be recompiled via the publish_module.sh script.

If you need to adapt the modules, each agent will have to collect the rules via this command
```
nodemgr updatecomp
```
