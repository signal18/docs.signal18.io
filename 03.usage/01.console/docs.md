---
title: Console Client Usage
---

## Console Client Usage

**replication-manager** provides an interactive console client to interact with your clusters.

Start the interactive console via:

`replication-manager-cli console`

or for a specific cluster
`replication-manager-cli console --cluster=mycluster`

![mrmconsole](/images/console.png)

The console client accepts several shortcut key commands:

```
Ctrl-D  Print debug information
Ctrl-F  Manual Failover
Ctrl-I  Toggle automatic/manual failover mode
Ctrl-R  Set slaves read-only
Ctrl-S  Switchover
Ctrl-Q  Quit
Ctrl-W  Set slaves read-write
Ctrl-P Ctrl-N switch between clusters
```
