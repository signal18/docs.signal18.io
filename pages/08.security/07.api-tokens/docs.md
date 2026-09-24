---
title: API Tokens
taxonomy:
    category: docs
---

## 8.8 API tokens

Since **3.1.43** a user can issue **API tokens** for themselves: bearer credentials for
scripts, CI jobs, an MCP server or the CLI, that carry a subset of the user's own grants
and a cluster scope, and expire. A client sends the token as
`Authorization: Bearer <token>` and never logs in. Every call is attributed to the user
who issued the token in the security log.

A token replaces the interactive login for machine clients. The login itself, the
`POST /api/login` call that returns a session JWT from a username and password, and the
accounts it authenticates are described in [First login](/installation/first-login) and
[API client usage](/usage/api/overview). Creating a token still requires that login: a
token cannot issue tokens.

A token can only **narrow** what its owner may do, never extend it:

- At creation, every grant put in the token must be a grant the owner holds.
- At every request, the effective grants are the token's grants **and** the owner's
  current grants. Remove a grant from the user, and every token of that user loses it at
  once. Delete the user, and the tokens stop working.
- A token scoped to named clusters can only reach those clusters' endpoints. Global
  settings and cluster management need the "every cluster" scope.
- A token cannot issue tokens. Creating one requires an interactive login.
- Two grants control it: `token-create` lets a user issue tokens for their own account,
  `token-manage` lets them list and revoke other users' tokens on a cluster. Both are in the
  default ACL of `admin`; `dba`, sponsors and external dbops get `token-create`. If your
  `api-credentials-acl-allow` lists grants explicitly, add `token` or `token-create` to the
  users who should issue tokens. The `system` service account can never hold these grants.

### 8.8.1 Creating a token

**Dashboard**: click your user name in the top bar, then **API tokens** in the User Profile window, then **New token**. Give it a label, pick the
grants (none selected means every grant you hold), the cluster scope, and the lifetime.
The token is displayed once with a copy button; the eye icon shows it again later, since
it is yours.

**CLI**:

```
replication-manager-cli token create --label ci --grants "db-show proxy" --clusters belair --expire-days 30
replication-manager-cli token list
replication-manager-cli token revoke <id>
```

`--grants` takes grant prefixes as in the ACL configuration (`db-show`, `proxy`,
`cluster-switchover`, …); `--expire-days -1` means no expiry; both default to "everything
I hold" and the server default lifetime.

**API**:

| Method | Path | Purpose |
| --- | --- | --- |
| `POST` | `/api/tokens` | create, body `{"label","grants","clusters","expireDays"}` |
| `GET` | `/api/tokens` | list my tokens |
| `DELETE` | `/api/tokens/{id}` | revoke |
| `GET` | `/api/clusters/{name}/tokens` | list every token covering a cluster (grant `token-manage`) |

### 8.8.2 Using a token

With the interactive login you first exchange a username and password for a session JWT
(see [API client usage](/usage/api/overview)):

```
JWT=$(curl -s -k -H 'Content-Type: application/json' --data '{"username":"admin","password":"..."}' https://repman:10005/api/login | jq -r .token)
curl -k -H "Authorization: Bearer $JWT" https://repman:10005/api/clusters/belair/topology/servers
```

With an API token there is no login call, the token is the bearer:

```
curl -k -H "Authorization: Bearer <token>" https://repman:10005/api/clusters/belair/topology/servers
replication-manager-cli --api-token <token> topology --cluster belair
```

A session JWT expires after `api-token-timeout` hours and dies when **replication-manager**
restarts; an API token lives until its expiry or revocation and survives restarts.

A revoked token is refused immediately. Revoking another user's token needs the
`token-manage` grant on every cluster the token covers.

### 8.8.3 Where tokens live

Tokens are stored on the **replication-manager** instance in
`<monitoring-datadir>/api-tokens.json`, encrypted with the persistent secret key
(`monitoring-key-path`) and excluded from the configuration git sync. They survive a
restart of **replication-manager**. Rotating the secret key invalidates every token: reissue
them afterwards.

### 8.8.4 Settings

| Setting | Default | Description |
| --- | --- | --- |
| `api-user-tokens` | `true` | Allow users to issue tokens. `false` refuses both issuing and authenticating with a token. |
| `api-user-tokens-default-expire-days` | `120` | Lifetime of a token when none is given. `0` means no expiry. |

Both are server-wide settings.

### 8.8.5 Security log

Events `api_token_created`, `api_token_revoked` and `api_token_denied` (a token used out of
its scope or for a grant it does not carry) are written to the security log with the
owner's name and the client address. See [Security logging](/security/overview).
