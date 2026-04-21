---
title: Plugin Architecture
taxonomy:
    category: docs
---

## 12.1.1 Plugin Architecture

> **Available since:** replication-manager **v3.1.24**

**replication-manager** extends its monitoring capabilities through a plugin system. On every monitoring tick each enabled plugin is invoked, receives a full snapshot of the monitored server state as JSON on its standard input, and writes findings and/or score checks as JSON on its standard output.

Plugins are organised in three tiers:

| Tier | Implementation | Availability | Examples |
|---|---|---|---|
| **Static** | In-process Go functions, no subprocess | All instances, no registration required | `errorlog`, `sqlerrorlog`, `slowlog`, `auditlog` |
| **Community** | External compiled binaries | Instances registered at gitlab.signal18.io (free) | All workload, security and score plugins |
| **Enterprise** | External compiled binaries | Signal18 Support Contract customers | `plugin-critical-alerts` |

**Community plugins** are the external plugin library shipped with replication-manager. Registering your instance at gitlab.signal18.io unlocks the full community plugin set. Registration is free — it allows Signal18 to understand how the product is used in production so the roadmap can be focused where it creates the most value.

**Enterprise plugins** are developed from Signal18's field experience supporting production deployments and are available to customers with an active Support Contract.

---

## 12.1.2 Lifecycle — External Plugins

```
Monitor tick (every monitoring-ticker seconds)
│
├─ for each server in cluster
│     │
│     └─ for each enabled external plugin
│           │
│           ├─ Build wire.Request JSON (server snapshot)
│           ├─ exec plugin binary  ←── stdin: JSON Request
│           │                      ──► stdout: JSON Response
│           ├─ Decode wire.Response
│           ├─ Append findings → SecurityStateMachine / LogStateMachine
│           └─ Append score_checks → SecurityScore
│
└─ SecurityStateMachine.GetOpenStates()
       └─ feed remediation engine, alerts, dashboard
```

Plugins are **stateless** and **ephemeral**: a new process is spawned for every evaluation and exits after writing one JSON response. State across ticks must be kept by replication-manager itself (state machines, Graphite metrics).

---

## 12.1.3 Wire Protocol — JSON stdin/stdout

All plugins share the types defined in `cluster/logplugin/plugins/wire/wire.go`.

### 12.1.3.1 Request

One JSON object written to the plugin's stdin before it starts reading:

| Field | Go type | Description |
|---|---|---|
| `server_url` | `string` | `host:port` of the monitored server |
| `server_variables` | `map[string]string` | Full `SHOW GLOBAL VARIABLES` snapshot (lowercase keys) |
| `database_users` | `[]DBUser` | `mysql.user` rows — no credential hashes |
| `cluster_context` | `ClusterContext` | Cluster-level facts (backup encryption, proxy presence, …) |
| `error_log` | `[]Msg` | Recent error log lines |
| `slow_log` | `[]SlowMsg` | Recent slow query log entries |
| `process_list` | `[]Process` | `SHOW PROCESSLIST` snapshot |
| `binlog_events` | `[]BinlogEvent` | Recent decoded binlog QUERY events |
| `config` | `map[string]string` | Per-plugin configuration key-value pairs |

**DBUser fields:**

| Field | Type | Description |
|---|---|---|
| `user` | string | Account username (`""` = anonymous) |
| `host` | string | Account host (`%` = wildcard) |
| `plugin` | string | Authentication plugin name |
| `password_empty` | bool | `true` when `authentication_string` is empty |
| `account_locked` | bool | `true` when `ACCOUNT LOCKED` |

### 12.1.3.2 Response

One JSON object written to stdout before the process exits:

| Field | Go type | Description |
|---|---|---|
| `findings` | `[]Finding` | Security or operational findings |
| `score_checks` | `[]ScoreCheck` | Named pass/fail compliance checks |

**Finding fields:**

| Field | Type | Description |
|---|---|---|
| `err_key` | string | Unique code, e.g. `SEC0103`, `WARN0301` |
| `severity` | string | `"WARNING"`, `"ERROR"`, or `"SECURITY"` |
| `description` | string | Human-readable explanation including server URL |

**ScoreCheck fields:**

| Field | Type | Description |
|---|---|---|
| `name` | string | Check identifier, e.g. `HasTableEncryption` |
| `pass` | bool | `true` = check passes |
| `description` | string | Explanation |

---

## 12.1.4 Plugin Configuration

Each plugin receives its configuration through two mechanisms, in priority order:

1. **`config` map in the JSON Request** — set via the replication-manager GUI or TOML file under `[cluster.plugin-config.plugin-name]`
2. **Scoped environment variables** — fallback when the config map key is absent

Environment variables follow the naming pattern `REPMAN_<PLUGINNAME>_<KEY>` where `<PLUGINNAME>` is the plugin name without the `plugin-` prefix, uppercased with hyphens converted to underscores.

**Example — `plugin-connection-storm`:**

```toml
[mycluster.plugin-config.plugin-connection-storm]
sleep-ratio-threshold = 0.75
min-connections = 20
```

Equivalent environment variables (fallback):
```
REPMAN_CONNECTION_STORM_SLEEP_RATIO_THRESHOLD=0.75
REPMAN_CONNECTION_STORM_MIN_CONNECTIONS=20
```

Helper functions in `wire.go` read from both sources transparently:

```go
threshold := wire.CfgFloat(req.Config, "sleep-ratio-threshold",
    wire.EnvFloat("REPMAN_CONNECTION_STORM_SLEEP_RATIO_THRESHOLD", 0.60))
```

---

## 12.1.5 Plugin Discovery and Loading

External plugins are placed in the cluster's plugins directory. At startup and on reload replication-manager scans the directory and registers every executable file whose name matches a known plugin prefix via `LoadPluginsFromDir`.

Built-in plugins are registered in `logplugin.GlobalRegistry`. External plugins are registered in the per-cluster `cluster.pluginRegistry`. The `/api/clusters/{name}/plugins` endpoint returns the combined list from the per-cluster registry.

### 12.1.5.1 Plugin Signing

When `plugin-signing-public-key` is configured, replication-manager verifies every external plugin binary against an Ed25519 signature stored in a `.sig` sidecar file under `<share>/plugins/` before executing it. Plugins that fail verification are rejected and logged as errors — they are never executed.

```toml
plugin-signing-public-key = "/etc/replication-manager/plugin-signing.pub"
```

The public key ships with the package at `<ShareDir>/plugins/plugin-signing.pub`. To sign a plugin binary:

```bash
# Generate key pair (once)
replication-manager plugin-key-gen

# Sign a plugin
replication-manager plugin-sign --binary share/plugins/plugin-connection-storm \
    --key etc/plugin-signing.key \
    --out share/plugins/plugin-connection-storm.sig
```

---

## 12.1.6 Security Considerations

- **Process isolation:** each plugin runs as a child process of replication-manager with the same OS user. No elevated privileges are granted.
- **No network access required:** plugins operate entirely on the JSON snapshot passed via stdin; they do not need database credentials.
- **No persistent state:** plugins must not write files or modify system state. All output goes to stdout.
- **Signature verification:** in production environments always enable `plugin-signing-public-key` to prevent execution of tampered binaries.
- **Timeout:** plugin processes are killed if they do not produce a response within the monitoring tick timeout. A timed-out plugin emits an error log line and contributes no findings for that tick.

---

## 12.1.7 Global Plugin Settings

| Config key | Default | Description |
|---|---|---|
| `log-plugin` | `false` | Master switch — enable the external plugin evaluation loop |
| `log-level-plugin` | `0` | Verbosity for the plugin subsystem (0=off … 4=debug) |
| `plugin-signing-public-key` | `""` | Path to Ed25519 public key for binary verification |
