---
title: First Login
taxonomy:
    category: docs
---

## First Login

After starting replication-manager for the first time, access the web GUI at `https://<host>:10005` (or `https://localhost:10005` during local setup).

---

## Local Users

replication-manager ships with two built-in local accounts:

| Username | Default password | Default role |
|---|---|---|
| `admin` | `repman` | Full access — all cluster, proxy, provisioning, and global settings |
| `dba` | `repman` | Database operations — no provisioning, no global settings, no user management |

These accounts are defined by the `api-credentials` configuration key:

```toml
api-credentials = "admin:repman,dba:repman"
```

or via command-line flag:

```bash
replication-manager monitor --api-credentials "admin:repman,dba:repman"
```

**Change the default passwords immediately after first login.** Accounts using the default password `repman` trigger a cluster warning (see below).

---

## Changing Passwords

Passwords can be changed from the **Settings → Users** panel in the GUI, or by updating `api-credentials` in the cluster TOML file and reloading.

Each entry in `api-credentials` follows the format `username:password`. Multiple accounts are separated by commas.

---

## Default Password Warning — WARN0108

replication-manager continuously monitors whether the built-in accounts still use their default passwords. When either `admin` or `dba` retains the password `repman`, the cluster enters state:

```
WARN0108: Default users still use default password.
          Please change the credentials for users: (admin,dba)
```

This warning appears in the cluster dashboard and in the main HA log. It clears automatically once the passwords are updated.

The check is performed by `CheckDefaultUser()` on every monitoring tick — no restart is required after changing passwords.

---

## SSO Login via gitlab.signal18.io

Once your instance is [registered](../registration), users can log in using their **gitlab.signal18.io** identity instead of a local password. SSO login does not require a local `api-credentials` entry — authentication is handled by the Signal18 GitLab OAuth flow.

To log in with SSO:

1. Click **Sign in with GitLab** on the replication-manager login page
2. Authenticate at gitlab.signal18.io (your Signal18 SSO account)
3. replication-manager validates your identity against the GitLab group membership for your registered instance namespace

The role and grants assigned to an SSO user are managed entirely through the replication-manager GUI and API — not through GitLab group roles. See [Cluster Role Sharing](../registration#cluster-role-sharing-with-external-users) for the full role and grant model.

---

## Summary

| Step | Action |
|---|---|
| 1 | Open `https://<host>:10005` |
| 2 | Log in as `admin` / `repman` |
| 3 | Change the `admin` and `dba` passwords — clears WARN0108 |
| 4 | (Optional) Register the instance at gitlab.signal18.io to enable SSO and community plugins |
