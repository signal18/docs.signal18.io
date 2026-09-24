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

A token can only **narrow** what its owner may do, never extend it:

- At creation, every grant put in the token must be a grant the owner holds.
- At every request, the effective grants are the token's grants **and** the owner's
  current grants. Remove a grant from the user, and every token of that user loses it at
  once. Delete the user, and the tokens stop working.
- A token scoped to named clusters can only reach those clusters' endpoints. Global
  settings and cluster management need the "every cluster" scope.
- A token cannot issue tokens. Creating one requires an interactive login.

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
| `GET` | `/api/clusters/{name}/tokens` | list every token covering a cluster (grant `grant-show`) |

### 8.8.2 Using a token

```
curl -k -H "Authorization: Bearer <token>" https://repman:10005/api/clusters/belair/topology/servers
replication-manager-cli --api-token <token> topology --cluster belair
```

A revoked token is refused immediately. Revoking another user's token needs the
`cluster-grant` grant on every cluster the token covers.

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
