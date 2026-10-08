---
title: OpenSVC Advanced Configuration
taxonomy:
    category: docs
---


## 10.2.1.3.1 Disks

##### `prov-db-disk-fs` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database type of FS deployment|
| Type | Enum |
| Values | xfs,ext4,zfs,ufs |
| Example | "zfs" |

File system many drivers are available we do test xfs ext4 zfs . Many other drivers like ceph or drbd would need additional testing to be used as extra options may need to be added.

OpenSVC Agent drivers can provision disk resources on many SAN arrays and Cloud API, contact support if you need custom type of disk provisioning for your architecture.

##### `prov-db-disk-pool` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database disk pool type for Micro Services deployment|
| Type | Enum |
| Values | none,zpool,lvm |
| Example | "zpool" |

##### `prov-db-disk-type` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database disk pool type for Micro Services deployment|
| Type | Enum |
| Values | loopback,physical,pool,directory|
| Example | "loopback" |

When loopback instead of a real device the FS path is needed instead of device path

##### `prov-db-disk-device` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database disk device path for Micro Services deployment|
| Type | String |
| Example | "/srv" |

Depends on  `prov-db-disk-type`

physical: define the device /dev/XXXXXXXX
pool: define the pool name
loopback: define  the path to create the loopback file
directory: define the path to create the service_name directory

## 10.2.1.3.1b Container start timeouts (3.1.43)

The orchestrator's own `start_timeout` for a docker container is **5 seconds**. The nodes purge
their docker images, and a start that has to pull the image again exceeds that: after a node
restart every container of the node fails its first start. replication-manager writes a start
timeout on every container it defines:

| Setting | Default | Written on |
| ------- | ------- | ---------- |
| `prov-db-start-timeout` | `2m` | `container#db`, the jobs sidecar, the pause and sensor containers of every database service |
| `prov-proxy-start-timeout` | `2m` | `container#prx`, the pause and sensor containers of every proxy |
| `prov-app-start-timeout` | `2m` | `container#app`, the init, pause and sensor containers of every app, per app or for the cluster |

A duration (`2m`, `90s`). The same value is written as the container's `pull_timeout`: the
orchestrator's own pull budget is 2 minutes, which a large image (ERPNext, Mattermost) exceeds
after an image purge; raise the app's own `prov-app-start-timeout` (10m) for those. Settable per
cluster from the Config page (Orchestrator, Database VM section) or the settings route; the
template refresh carries it to the orchestrator without a restart, the next start uses it.
PostgreSQL engine servers take the app value through their template.

Every service also carries a start priority in the orchestrator (`DEFAULT.priority`, smaller
first): databases 10, proxies 20, apps 30, so that a node restarting everything at once serves
the databases before the proxies that route to them and the apps that connect through the
proxies. The orchestrator keeps the key only for an API identity holding its `prioritizer`
grant.

## 10.2.1.3.2 network

Network please check availability of the ip before using them , also some opensvc deployemetn can manage range of dhcp ip and DNS entries   

##### `prov-db-net-iface` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database ethernet device. |
| Type | String |
| Example | "br0" |

##### `prov0-db-net-gateway` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database network gateway. |
| Type | String |
| Example | "192.168.1.254" |

##### `prov-db-net-mask` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database eth device |
| Type | String |
| Example | "255.255.255.0" |

---

## 10.2.1.3.4 Terminal / Console Access

> **Available since:** replication-manager **v3.1.25**, requires OpenSVC v3 agent

The web terminal feature allows opening a shell session directly into database, proxy, or application containers from the replication-manager GUI. On OpenSVC v3, this uses the `PostInstanceResourceConsole` API which requires a tty-share relay server configured at the cluster level.

### OpenSVC v3 Cluster Configuration

Configure the console relay on each OpenSVC cluster:

```bash
om cluster config update \
  --set console.server=console.opensvc.com:3456 \
  --set console.max_seats=2 \
  --set console.max_greet_timeout=1h
```

Verify the configuration:

```bash
om cluster config show --section console
[console]
server = console.opensvc.com:3456
max_seats = 2
max_greet_timeout = 1h
```

| Setting | Description |
|---|---|
| `console.server` | tty-share relay server address. `console.opensvc.com:3456` is the public OpenSVC relay. |
| `console.max_seats` | Maximum concurrent console sessions per container resource. |
| `console.max_greet_timeout` | Maximum time to wait for the console handshake (e.g. `1h`, `30m`). |

Without this configuration, the terminal button in replication-manager opens a connection but the OpenSVC agent cannot establish the tty-share session and the terminal fails silently.

### replication-manager Configuration

Enable the terminal feature in replication-manager:

```toml
terminal-session-enabled = true
```

The terminal supports three session types per container:
- **bash** — interactive shell (uses tty-share on OpenSVC v3, gotty-client on v2, SSH on on-premise)
- **mysql** — MariaDB/MySQL client connection
- **mytop** — real-time process monitoring
