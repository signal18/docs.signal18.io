---
title: Command Line Client
taxonomy:
    category: docs
---

## 1. Command Line Client

replication-manager ships with `replication-manager-cli`, a command line client that connects to the running daemon over the REST API. It can be used interactively via the terminal console or non-interactively for scripting and automation.

---

## 2. Interactive Console

Start the interactive console:

```bash
replication-manager-cli console
```

For a specific cluster:

```bash
replication-manager-cli console --cluster=mycluster
```

![mrmconsole](/images/console.png)

### 2.1 Keyboard Shortcuts

| Key | Action |
|---|---|
| `Ctrl-D` | Print debug information |
| `Ctrl-F` | Manual failover |
| `Ctrl-I` | Toggle automatic / manual failover mode |
| `Ctrl-R` | Set replicas read-only |
| `Ctrl-S` | Switchover |
| `Ctrl-W` | Set replicas read-write |
| `Ctrl-P` / `Ctrl-N` | Switch between clusters |
| `Ctrl-Q` | Quit |

---

## 3. Non-Interactive Commands

### 3.1 Switchover

Trigger a controlled primary handoff:

```bash
replication-manager-cli switchover --cluster=mycluster
```

### 3.2 Failover

Trigger a failover without waiting for the primary to recover:

```bash
replication-manager-cli failover --cluster=mycluster
```

### 3.3 Bootstrap Replication

Initialize replication on existing database nodes that have no replication configured. Supported topologies:

- `master-slave`
- `master-slave-no-gtid`
- `maxscale-binlog`
- `multi-master`
- `multi-tier-slave`

```bash
replication-manager-cli --cluster=mycluster bootstrap --clean-all --topology=multi-tier-slave
```

### 3.4 Cluster Status

```bash
replication-manager-cli --cluster=mycluster status
```

Sample output:

```
| Group: mycluster |  Mode: Manual
                 Id            Host   Port          Status   Failures   Using GTID         Current GTID           Slave GTID   Delay  RO
5641630519400684578       127.0.0.1   3317          Master          0           No          0-3317-3124                            0 OFF
9624235790336213315       127.0.0.1   3318     Maintenance          0    Slave_Pos          0-3317-3124          0-3317-3124        0  ON
3944708846436490796       127.0.0.1   3319           Slave          0    Slave_Pos          0-3317-3124          0-3317-3124        0  ON
```

### 3.5 Topology

```bash
replication-manager-cli topology
```

### 3.6 Server Maintenance

Place a specific server in maintenance mode using its numeric ID:

```bash
replication-manager-cli server --id=9624235790336213315 --maintenance
```
