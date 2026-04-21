---
title: Start & Stop Monitoring
taxonomy:
    category: docs
---

## 1. Start & Stop Monitoring

![clientserver](/images/clientserver.png)

replication-manager runs as a daemon that continuously monitors your clusters. It exposes a REST API, a web GUI, and accepts commands from the CLI client.

---

## 2. Start

**systemd (package install):**

```bash
systemctl start replication-manager
```

**Enable on boot:**

```bash
systemctl enable replication-manager
```

**init.d (legacy):**

```bash
/etc/init.d/replication-manager start
```

**Manual (tarball or embedded binary):**

```bash
replication-manager monitor --config /etc/replication-manager/config.toml --http-server
```

---

## 3. Stop

```bash
systemctl stop replication-manager
```

---

## 4. Check Status

**Service status:**

```bash
systemctl status replication-manager
```

**Cluster status via CLI:**

```bash
replication-manager-cli status
```

**All clusters:**

```bash
replication-manager-cli --cluster="mycluster" status
```

Sample output:

```
| Group: mycluster |  Mode: Automatic
         Id            Host   Port    Status   Failures   Using GTID         Current GTID    Delay  RO
5641630519400684578  10.0.1.10  3306    Master          0    Slave_Pos     0-3306-4210            0 OFF
9624235790336213315  10.0.1.11  3306     Slave          0    Slave_Pos     0-3306-4210            0  ON
3944708846436490796  10.0.1.12  3306     Slave          0    Slave_Pos     0-3306-4210            0  ON
```

**Cluster topology:**

```bash
replication-manager-cli topology
```

**Web GUI:**

Open `https://<host>:10005` in a browser — the dashboard shows live cluster state, replication health, and server metrics.

---

## 5. Logs

See [Logs](../logs) for a full reference — log files written, per-module verbosity settings, rotation, and how to query logs via journalctl and the REST API.
