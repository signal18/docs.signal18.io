---
title: AI Assistant (MCP)
taxonomy:
    category: docs
---

## 3.8 AI assistant access with MCP

Since **3.1.43** **replication-manager** can expose its clusters to an AI assistant through
the Model Context Protocol (MCP): Claude Code, Claude Desktop or any MCP client can list the
clusters, read topology, alerts, logs, server status, backups, and, when allowed, run
actions such as a switchover or a rolling restart.

Every MCP tool mirrors a REST endpoint and runs under the caller's own permissions: the
assistant can do exactly what the account or token it uses can do through the API, nothing
more. There is no separate MCP permission model.

### 3.8.1 Enabling the server

| Setting | Default | Description |
| --- | --- | --- |
| `mcp-server` | `false` | Start the MCP server. |
| `mcp-transport` | `sse` | `sse` (HTTP, for remote clients), `stdio`, or `both`. |
| `mcp-bind-address` / `mcp-port` | `localhost` / `10007` | Where the SSE transport listens. |
| `mcp-advertise-address` | | Public base URL announced to clients when it differs from the bind address. |
| `mcp-auth-enabled` | `true` | Require a bearer on the MCP endpoints and run every tool under that user's ACL. |
| `mcp-write-enabled` | `false` | Register the action tools (failover, switchover, settings, restic, proxies…). Server-wide, cannot be changed through a tool. |

All are server-wide settings and need a restart of **replication-manager**.

The SSE transport is plain HTTP: keep it on localhost or behind a TLS proxy when clients are
remote. The `stdio` transport carries no credential and only starts with
`mcp-auth-enabled = false`, which runs every tool unrestricted.

### 3.8.2 Authenticating the assistant

Use an [API token](/security/api-tokens): it survives restarts, can be narrowed to the grants
and clusters the assistant needs, and is revocable. Create it from your User Profile in the
dashboard or from the CLI:

```
replication-manager-cli token create --label claude --grants "db-show cluster-show" --clusters belair --expire-days 90
```

Then point the client at the server with the token as bearer, for example in `.mcp.json`
for Claude Code:

```json
{
  "mcpServers": {
    "replication-manager": {
      "type": "sse",
      "url": "http://localhost:10007/sse",
      "headers": { "Authorization": "Bearer ${REPLICATION_MANAGER_API_TOKEN}" }
    }
  }
}
```

An interactive login JWT from `POST /api/login` also works, but it expires after
`api-token-timeout` hours and at every restart.

### 3.8.3 What the assistant may do

- Only the clusters the account has access to are listed; a cluster-scoped token sees only
  its clusters.
- Each tool is checked against the ACL of the REST endpoint it mirrors: reading server
  variables needs `db-show-variables`, a switchover needs `cluster-switchover`, changing a
  setting needs `cluster-settings`, and so on. A token narrowed to `db-show` can read but
  never act.
- Refusals are returned to the assistant as tool errors and written to the security log as
  `mcp_denied`; rejected connections as `mcp_auth_failure`.

### 3.8.4 Tools

Read: `list-clusters`, `get-cluster-health`, `get-cluster-topology`, `get-cluster-settings`,
`get-cluster-alerts`, `get-cluster-logs`, `get-cluster-crashes`, `check-cluster-error-state`,
`get-server-status`, `get-server-variables`, `get-server-processlist`,
`get-server-slow-queries`, `get-server-error-log`, `get-server-tables`,
`check-server-is-master`, `check-server-is-slave`, `check-server-is-late`, `list-backups`,
`get-backup-stats`, `list-restic-snapshots`, `get-restic-stats`, `get-restic-task-queue`,
`list-proxies`, `get-proxy`.

Actions (with `mcp-write-enabled`): `cluster-failover`, `cluster-switchover`,
`cluster-rolling-restart`, `cluster-optimize`, `cluster-rotate-passwords`,
`cluster-reset-failover-control`, `cluster-reset-sla`, `cluster-start-traffic`,
`cluster-stop-traffic`, `cluster-physical-backup`, `cluster-checksum-tables`,
`cluster-set-setting`, `cluster-switch-setting`, `cluster-bootstrap-replication`,
`cluster-cleanup-replication`, `server-start`, `server-stop`, `server-restart`,
`server-backup-physical`, `server-optimize`, `server-set-maintenance`, `server-set-read-only`,
`server-set-read-write`, `server-kill-query`, `restic-init`, `restic-fetch`, `restic-purge`,
`restic-unlock`, `restic-task-queue-pause`, `restic-task-queue-resume`, `restic-task-cancel`,
`proxy-start`, `proxy-stop`, `proxy-provision`, `proxy-unprovision`.
