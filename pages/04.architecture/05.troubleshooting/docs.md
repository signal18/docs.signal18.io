---
title: Troubleshooting
taxonomy:
    category: docs
---

## Troubleshooting High Availability

This page lists every file and API endpoint replication-manager writes or exposes during normal operation, failovers, and crashes — and explains how to read them when something goes wrong.

---

## Data Directory Layout

All runtime state is stored under `monitoring-datadir` (default `/var/lib/replication-manager`). Each cluster gets its own sub-directory named after the cluster section in the config file.

```
/var/lib/replication-manager/
├── panic.log                          # stacktrace on daemon panic (JSON)
├── carbonapi.log                      # embedded Graphite backend log
└── <cluster-name>/
    ├── clusterstate.json              # full cluster state snapshot (persisted every tick)
    ├── sla.json                       # SLA counters (uptime, failover counts)
    ├── queryrules.json                # active ProxySQL query rules
    ├── agents.json                    # OpenSVC agent list
    ├── <cluster-name>.toml            # active runtime config (written by API/GUI changes)
    ├── cache.toml                     # cached config values
    ├── immutable.toml                 # config values locked from API changes
    ├── overwrite.toml                 # config values that override the main config
    ├── failover.YYYYMMDDHHMMSS.json   # one file per failover/switchover event
    ├── sql_error.log                  # SQL errors from all DB connections
    ├── sql_general.log                # all SQL statements executed by replication-manager
    ├── ca-cert.pem                    # generated CA certificate
    ├── ca-key.pem                     # generated CA private key
    ├── server-cert.pem                # server TLS certificate
    └── server-key.pem                 # server TLS private key
```

---

## Key Files Explained

### `clusterstate.json`

Written on every monitoring tick. Contains the complete cluster snapshot including:
- Current primary and all replicas with their status, GTID positions, and lag
- Active error and warning state codes (e.g. `ERR00076`, `WARN0108`)
- Crash history (`dbServersCrashes`) — cleared when all servers are back up
- Failover history (`failoverHistory`) — kept across restarts for PITR reference

```bash
cat /var/lib/replication-manager/mycluster/clusterstate.json | jq .
# Show current state codes
cat /var/lib/replication-manager/mycluster/clusterstate.json | jq '.crashes'
# Show SLA
cat /var/lib/replication-manager/mycluster/sla.json | jq .
```

### `failover.YYYYMMDDHHMMSS.json`

One file is written per failover or switchover event, named with a timestamp. Contains:
- Old primary URL and elected new primary URL
- Binlog file and position at the moment of the event
- GTID positions (IO GTID, executed GTID set)
- Semi-sync slave status at failover time
- Whether it was a switchover (`"switchover": true`) or failover

```bash
# List all failover events, most recent first
ls -lt /var/lib/replication-manager/mycluster/failover.*.json

# Inspect the most recent event
ls -t /var/lib/replication-manager/mycluster/failover.*.json | head -1 | xargs cat | jq .
```

The number of retained failover files is controlled by `failover-log-file-keep` (default: 5).

### `sql_error.log` and `sql_general.log`

- `sql_error.log` — every SQL error returned when replication-manager queries the database (connection failures, permission errors, invalid queries)
- `sql_general.log` — every SQL statement replication-manager executes against the cluster (useful to audit what the daemon does at each step of a failover or rejoin)

```bash
# Watch SQL errors in real time
tail -f /var/lib/replication-manager/mycluster/sql_error.log

# Find statements executed during a failover window
grep "2024-06-01 03:" /var/lib/replication-manager/mycluster/sql_general.log
```

### `panic.log`

If replication-manager itself crashes, a JSON-formatted stacktrace is written here. Send this file to Signal18 support when reporting a daemon crash.

```bash
cat /var/lib/replication-manager/panic.log | jq .
```

---

## Log Files

See the [Logs](../../usage/logs) reference for the full list of log files and journalctl commands. For HA troubleshooting the most relevant are:

| File | What to look for |
|---|---|
| Main log (`log-file`) | `STATE`, `ERR0`, `WARN0` lines around the incident time |
| Maintenance log (`*-maintenance.log`) | Rejoin and reseed progress |
| `sql_error.log` | Permission or connectivity errors that blocked a failover step |
| `sql_general.log` | Exact SQL sequence replication-manager ran during failover/rejoin |

```bash
# Find all state changes in the main log
grep 'STATE' /var/log/replication-manager.log

# Find failover-related lines around a specific time
grep '2024-06-01 03:' /var/log/replication-manager.log | grep -E 'failover|Failover|ERR0|STATE'

# Follow the maintenance log during a rejoin
tail -f /var/log/replication-manager-maintenance.log
```

---

## REST API Endpoints

All in-memory state is also accessible over the API without reading files:

| Endpoint | Content |
|---|---|
| `GET /api/clusters/{name}/topology/servers` | All servers with status, GTID, lag, replication health |
| `GET /api/clusters/{name}/topology/master` | Current primary details |
| `GET /api/clusters/{name}/topology/slaves` | All replicas with replication thread status |
| `GET /api/clusters/{name}/topology/alerts` | Active alert and warning state codes |
| `GET /api/clusters/{name}/topology/crashes` | Crash and failover history (in-memory) |
| `GET /api/clusters/{name}/topology/logs` | General cluster log buffer |
| `GET /api/clusters/{name}/topology/http-logs` | HTTP/API log buffer |

```bash
# Quick cluster health check
curl -sk -u admin:repman https://localhost:10005/api/clusters/mycluster/topology/servers | jq '.[] | {host: .host, port: .port, state: .state, gtid: .currentGtid, lag: .delay}'

# Active alerts
curl -sk -u admin:repman https://localhost:10005/api/clusters/mycluster/topology/alerts | jq .

# Crash history
curl -sk -u admin:repman https://localhost:10005/api/clusters/mycluster/topology/crashes | jq .
```

---

## Increasing Verbosity for an Investigation

To get more detail without restarting the daemon, bump the relevant module log levels via config reload or API:

```toml
# In cluster config — reload with SIGHUP or via API
log-level           = 4   # full DEBUG for all modules
log-level-topology  = 4   # topology discovery detail
log-level-sql       = 4   # every SQL statement with timing
log-level-sst       = 4   # state transfer / rejoin steps
```

```bash
# Follow debug output in real time
journalctl -u replication-manager -f -p debug

# Or if log-file is configured
tail -f /var/log/replication-manager.log | grep -E 'DEBUG|topology|rejoin|failover'
```

---

## Common First Steps

| Symptom | Where to look |
|---|---|
| Unexpected failover | `failover.*.json` + main log `STATE` lines around the event time |
| Failover did not trigger | Main log — check false-positive check results (`ERR00023`, grace period messages) |
| Replica not rejoining | Maintenance log + `sql_error.log` for permission or GTID errors |
| Wrong server promoted | `clusterstate.json` — check replication health scores at the time |
| Daemon crashed | `panic.log` — JSON stacktrace |
| Proxy not updated after failover | Main log — search `[proxy]`, `[haproxy]`, `[proxysql]` module tags |
| Config not taking effect | `<cluster>.toml`, `cache.toml`, `immutable.toml` in the datadir — check which layer wins |
