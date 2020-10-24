---
title: Quick Start
taxonomy:
    category: docs
---


![clientserver](/images/clientserver.png)

Start the server via:
```
systemctl start replication-manager   
```
or via init.d
```
/etc/init.d/replication-manager start
```

You can permanently start the monitor via
```
systemctl enable replication-manager   
```

**replication-manager-cli (2.0)** allows command line interaction with the monitor daemon.

Command line help can be displayed via
```
replication-manager-cli --help
replication-manager-cli switchover --help
```

In the next section we will explore how to quickly switchover using the interactive command-line console.
