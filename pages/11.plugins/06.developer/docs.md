---
title: Developing Plugins
taxonomy:
    category: docs
---

## 12.6.1 Developing Plugins

External plugins are standalone Go binaries that follow a simple JSON stdin/stdout contract. No special framework is required — only the `wire` package from the replication-manager module.

---

## 12.6.2 Quick Start

### 12.6.2.1 Create the plugin directory

```
cluster/logplugin/plugins/plugin-<category>-<name>/
└── main.go
```

Category conventions: `workload`, `security`, `score`, `binlog`.

### 12.6.2.2 Write the plugin

```go
package main

import (
    "encoding/json"
    "fmt"
    "os"

    "github.com/signal18/replication-manager/cluster/logplugin/plugins/wire"
)

func main() {
    // 1. Decode the request from stdin
    var req wire.Request
    if err := json.NewDecoder(os.Stdin).Decode(&req); err != nil {
        fmt.Fprintf(os.Stderr, "decode error: %v\n", err)
        os.Exit(1)
    }

    // 2. Read configuration (config map first, env var as fallback)
    threshold := wire.CfgFloat(req.Config, "my-threshold",
        wire.EnvFloat("REPMAN_<PLUGINNAME>_MY_THRESHOLD", 0.50))

    // 3. Inspect the server snapshot
    var findings []wire.Finding

    if val := req.ServerVariables["some_variable"]; val == "UNSAFE" {
        findings = append(findings, wire.Finding{
            ErrKey:      "WARN0399",
            Severity:    "WARNING",
            Description: fmt.Sprintf("Server %s: some_variable is unsafe", req.ServerURL),
        })
    }

    // 4. Write the response to stdout
    json.NewEncoder(os.Stdout).Encode(wire.Response{Findings: findings})
}
```

### 12.6.2.3 Build the plugin

```bash
cd cluster/logplugin/plugins/plugin-<category>-<name>
go build -o ../../../../share/plugins/plugin-<category>-<name> .
```

### 12.6.2.4 Sign the plugin (if signature verification is enabled)

```bash
replication-manager plugin-sign \
    --binary share/plugins/plugin-<category>-<name> \
    --key etc/plugin-signing.key \
    --out share/plugins/plugin-<category>-<name>.sig
```

---

## 12.6.3 Wire Protocol Reference

### 12.6.3.1 wire.Request

```go
type Request struct {
    ServerURL       string            `json:"server_url"`
    ServerVariables map[string]string `json:"server_variables"`
    DatabaseUsers   []DBUser          `json:"database_users"`
    ClusterContext  ClusterContext    `json:"cluster_context"`
    ErrorLog        []Msg             `json:"error_log"`
    SlowLog         []SlowMsg         `json:"slow_log"`
    ProcessList     []Process         `json:"process_list"`
    BinlogEvents    []BinlogEvent     `json:"binlog_events"`
    Config          map[string]string `json:"config,omitempty"`
}
```

`ServerVariables` keys are always **lowercase** (e.g. `require_secure_transport`, not `REQUIRE_SECURE_TRANSPORT`). Boolean values may be `"ON"` / `"OFF"` or `"1"` / `"0"` depending on the MariaDB/MySQL version — always test both.

### 12.6.3.2 wire.DBUser

```go
type DBUser struct {
    User          string `json:"user"`
    Host          string `json:"host"`
    Plugin        string `json:"plugin"`
    PasswordEmpty bool   `json:"password_empty"`
    AccountLocked bool   `json:"account_locked"`
}
```

### 12.6.3.3 wire.Finding

```go
type Finding struct {
    ErrKey      string `json:"err_key"`
    Severity    string `json:"severity"`
    Description string `json:"description"`
}
```

**Severity values:**

| Value | Routing |
|---|---|
| `"WARNING"` | Main HA log |
| `"ERROR"` | Main HA log |
| `"SECURITY"` | Dedicated `security.log` + SecurityStateMachine |

### 12.6.3.4 wire.ScoreCheck

```go
type ScoreCheck struct {
    Name        string `json:"name"`
    Pass        bool   `json:"pass"`
    Description string `json:"description"`
}
```

### 12.6.3.5 wire.Response

```go
type Response struct {
    Findings    []Finding    `json:"findings"`
    ScoreChecks []ScoreCheck `json:"score_checks"`
}
```

---

## 12.6.4 Configuration Helpers

The `wire` package provides helpers to read configuration from the request `Config` map with typed conversion and an environment variable fallback.

```go
// Integer with env fallback
hours := wire.CfgInt(req.Config, "timeframe-hours",
    wire.EnvInt("REPMAN_MYNAME_TIMEFRAME_HOURS", 1))

// Float with env fallback
ratio := wire.CfgFloat(req.Config, "ratio-threshold",
    wire.EnvFloat("REPMAN_MYNAME_RATIO_THRESHOLD", 0.30))

// String with env fallback
users := wire.CfgStr(req.Config, "ignored-users",
    wire.EnvStr("REPMAN_MYNAME_IGNORED_USERS", ""))

// Bool with env fallback
flag := wire.CfgBool(req.Config, "include-empty",
    wire.EnvStr("REPMAN_MYNAME_INCLUDE_EMPTY", "true") != "false")
```

**Environment variable naming convention:**

```
REPMAN_<PLUGIN_NAME_UPPER>_<KEY_UPPER>
```

Where `<PLUGIN_NAME_UPPER>` is the plugin name without the `plugin-` prefix, with hyphens replaced by underscores and uppercased.

| Plugin name | Env prefix |
|---|---|
| `plugin-connection-storm` | `REPMAN_CONNECTION_STORM_` |
| `plugin-security-hardening` | `REPMAN_SECURITY_HARDENING_` |
| `plugin-binlog-creditcard-leak` | `REPMAN_BINLOG_CREDITCARD_LEAK_` |

---

## 12.6.5 Error Code Assignment

Assign error codes from the appropriate range:

| Prefix | Range | Category |
|---|---|---|
| `WARN` | 0300–0399 | Workload and operational anomalies |
| `SEC` | 0100–0199 | Security findings |
| `INFO` | 0300–0399 | Informational nudges |

Check existing codes in the current plugins to avoid collisions.

---

## 12.6.6 Registering GUI Configuration

To expose plugin configuration in the replication-manager GUI, add the plugin to `pluginKnownKeys()` in `share/dashboard_react/src/Pages/Settings/PluginsSettings.jsx`:

```js
case 'plugin-<category>-<name>':
    return ['my-threshold', 'timeframe-hours']
```

Also add entries in `pluginKeyType()`, `pluginKeyRange()`, `pluginKeyLabel()`, `pluginKeyDefault()`, and `pluginKeyHelp()` for each key. Each key gets:
- A type: `'int'`, `'float'`, `'text'`, or `'bool'`
- A numeric range for number inputs (min, max, step)
- A human-readable label
- A default value matching the plugin source code
- A markdown help string explaining the calculation

---

## 12.6.7 Adding an Automated Remediation

If the finding has a fix via a compliance module tag, add an entry to `secTagMap` in `cluster/cluster_sec_fix.go`:

```go
"WARN0399": {action: "drop_tag", tag: "with_my_tag", risk: "safe"},
```

Then add a `case "WARN0399":` to `FixSecState()` in the same file.

If the fix is a direct database operation (e.g. locking accounts), implement it as a helper function and call it from `FixSecState()`.

If the fix requires a server restart, add `go cluster.RollingRestart()` after the tag is applied and set the risk to `"disruptive"`.

---

## 12.6.8 Testing a Plugin Locally

The simplest way to test a plugin is to pipe a sample JSON request into it:

```bash
echo '{
  "server_url": "127.0.0.1:3306",
  "server_variables": {"some_variable": "UNSAFE"},
  "database_users": [],
  "config": {"my-threshold": "0.75"}
}' | ./plugin-<category>-<name>
```

The output should be a valid `wire.Response` JSON object:

```json
{"findings":[{"err_key":"WARN0399","severity":"WARNING","description":"Server 127.0.0.1:3306: some_variable is unsafe"}],"score_checks":null}
```

For integration testing, enable the plugin in a development cluster, set `log-level-plugin = 4`, and watch the plugin log in the monitoring output.

---

## 12.6.9 Checklist

- [ ] Plugin binary placed in `share/plugins/`
- [ ] Signature file created if signing is enabled
- [ ] Env var names follow `REPMAN_<PLUGINNAME>_<KEY>` convention
- [ ] All config keys read via `wire.CfgXxx` with env fallback
- [ ] Error code checked against existing codes — no collision
- [ ] `Description` always includes `req.ServerURL` so findings are traceable to a server
- [ ] Response always written even when `findings` is empty (write `wire.Response{}`)
- [ ] Plugin exits 0 on success; non-zero exit is logged as a plugin error
- [ ] GUI keys registered in `PluginsSettings.jsx` with correct type, range, and help text
- [ ] Remediation entry added to `secTagMap` / `FixSecState` if automated fix exists
