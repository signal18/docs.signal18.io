---
title: Password Rotation and Credential Stores
taxonomy:
    category: docs
---

## 8.9 Password rotation and credential stores

Since **3.1.49** an administrator can **rotate the password of a replication-manager user**:
the user gets a new generated password, shown once to the person who rotated it. This is
how a compromised user is **banned**:

- its open sessions are refused from their next request,
- every [API token](/security/api-tokens) it owns is revoked,
- the old password no longer signs in, on every cluster that knows the user.

The new password is kept by replication-manager itself, in its versioned secret store. A
rotation needs no OpenSVC, no Kubernetes and no Vault. When such stores are configured,
they receive the new value as well (see 8.9.3).

### 8.9.0 Rotating a password

**In the dashboard:** open the cluster, then the **Users** tab. A user you may rotate shows
a key button (**rotate password**). Confirm: the next window shows the new password with a
copy button. **Copy it now**: it is shown once, never logged, and nobody can read it later.

Since **3.1.50**, the new password is **mailed to you as a one-time link** instead, when your
login is an email address and your role covers the user's level: a user of the instance's list
needs you to be **sysops or extsysops**; a user of a cluster's own list also accepts **dbops,
extdbops or sponsor** of that cluster. The window then says where it was mailed, and the
password is not on the screen. Signed in as the local `admin`, your email is the account the
instance is registered with (the one the user menu shows). It is never mailed to the rotated account itself: if that
account is compromised, its mailbox may be too (see 8.9.7).

**With the API:**

```
POST /api/clusters/{cluster}/users/rotate-password
Authorization: Bearer <token>
{"username": "alice"}
```

The answer carries the new password once:

| Field | Meaning |
| --- | --- |
| `password` | The new password, 24 characters. |
| `scope` | `instance`: the user is in the instance's list (`api-credentials`) and was rotated on every cluster. `cluster`: the user is in this cluster's own list (`api-credentials-external`) and was rotated on this cluster. |
| `clusters` | The clusters where the new password is in force. |
| `tokensRevoked` | How many API tokens of the user were revoked. |
| `tokensError` | Set when the token revocation could not be saved. **The ban is not complete**: rotate again once the cause is fixed. |
| `persistError` | Set when the new password could not be written to disk. It is in force, but a restart would bring the old one back: fix the cause and rotate again. |
| `etcFile` | The user list is set in a configuration file under `/etc`: `written`, or why it was not written (see 8.9.4). |
| `revealMailedTo`, `revealExpires` | Since 3.1.50: the new password was mailed to you (the connected user) as a one-time link, valid until `revealExpires`; `password` is then empty (see 8.9.7). |
| `revealError` | Since 3.1.50: why the password was not mailed (your login has no email, your role does not cover the level, no mail server, links off); `password` then carries it, shown once. |
| `storesBehindCount`, `storesBehind` | Server credential stores already behind when you rotated: they may still hold the old password until they catch up (see 8.9.3). The names are given to the instance's owners only. |

A rotation that fails half-way keeps what it did: the password stays rotated where it was
changed, and the answer names those clusters. Going back would give the compromised
password back.

### 8.9.1 Who may rotate

- The **`cluster-rotate-passwords`** grant: held by **sysops** and **extsysops**. dbops,
  extdbops and sponsors do not hold it.
- A user of the **instance's list** (`api-credentials`, read by every cluster) is a server
  secret: rotating **another** user of that list also needs the server credentials grant
  (`cluster-server-credentials`) on **every** cluster of the instance.
- **No escalation**: you rotate your own password, or the password of a user who holds no
  grant you lack, on every cluster concerned. The new password is handed to you, so rotating
  a user with more rights would hand you those rights.
- You may always rotate **your own** password.
- **Refused**: the `system` account (its key is derived from the instance's key), the
  user that registered the instance with Cloud18, and accounts without a local password
  (they sign in with Cloud18: change the password at the identity provider).

### 8.9.2 Where the new password is kept

The new password is a new **version** in replication-manager's versioned secret store
(`monitoring-secret-versioning`, on by default, `monitoring-secret-versioning-keep-last`
versions kept). It needs the instance's encryption key: a version is always kept
encrypted.

- The instance's list (`api-credentials`) is kept once for the instance, in
  `<working-dir>/secret_store.json`. It is on disk **before** the answer, so a crash or a
  failover right after a ban keeps the ban.
- A cluster's own list (`api-credentials-external`) is kept in that cluster's
  `cluster.d/<cluster>_secret_store.json`.
- Both files travel with the configuration to the standby and the peers (config git push).
  **A standby takes the new list at its next pull**: if it becomes active before that,
  the old password still works there until then. Rotate again on the new active monitor, or
  share the data directory between the pair.
- With `monitoring-secret-versioning = false`, the configuration is the store: the new
  value is written, encrypted, in the cluster's saved configuration. This needs the
  encryption key and `monitoring-save-config` on.
- A list set in a configuration file under `/etc` is rotated too: the newest version wins
  over the file at the next start. replication-manager also rewrites the file, so it never
  keeps the banned password (8.9.4).

The clocks of the two monitors must stay close (NTP): versions are ordered by time when
two monitors' stores are merged.

### 8.9.3 Server credential stores

The **server secrets** are the instance's own credentials: the user list `api-credentials`,
the SMTP password, the GitLab password and the like. They belong to the instance, never to
a cluster: they are **never written to a cluster's Vault entry**, which a sponsor may switch
on and read.

To keep a copy of the server secrets outside replication-manager, open the **global
settings**, section **Server Credential Stores**. Only the instance's **sysops and
extsysops** see and change this list (the server credentials grant on every cluster). Each
change is a security event. Three kinds of store:

| Store | Where the server secrets are written | Who can read them there |
| --- | --- | --- |
| **Vault** (or OpenBao) | `<mount>/<path-prefix>/system/repman/<instance name>/<key>` | Whoever your Vault policies allow. |
| **OpenSVC** | the secret `system/sec/repman`, keys `<instance name>_<key>`, through the agent of a cluster you name (a cluster on OpenSVC) | The administrators of the OpenSVC system namespace. |
| **Kubernetes** | the Secret `repman-system` of the namespace you give, keys `<instance name>_<key>`, through the client of a cluster you name (a cluster on Kubernetes) | Whoever reads Secrets in that namespace. Give the cluster's client the narrowest role: create, get and patch on that Secret. |

Every change of a server secret, a rotation included, is written to each store that is on,
in the background: a store that is down never blocks a rotation or the monitoring. In
OpenSVC and Kubernetes, each key also keeps the value it replaced under `<key>_OLD`, one
deep. replication-manager never deletes what a store holds.

**A store behind:** it is retried every `secret-kv-retry-interval`, and the state stays open
until it holds the current secrets:

- **GWARN024** (server state, seen by the owners): the store's name and why: address, sign-in,
  the cluster that carries it.
- **WARN0320** (security state of every cluster): the store's name only. A cluster's members
  and sponsor learn that the instance's credentials are not current there, not where the store is.

**The Cloud18 vault, for marketplace partners.** An instance registered with Cloud18 on the
**Market Place Partner** plan, with a public `api-public-url`, gets the Cloud18 vault in this
list automatically: on when no other server store is on, off next to your own vault. Its
settings come from Cloud18. You may switch it off. It is then never switched on again,
and it cannot be deleted. An on-premise instance never gets it: add your own vault, or none.

#### The instance's name

In a shared store, the instance's folder or key prefix is its **name**:
`cloud18-domain/cloud18-sub-domain/cloud18-sub-domain-zone`, whether or not the instance is
registered with Cloud18. The parts mean what you want them to mean: an office or a country, a
machine or a data centre. Never the URL: an instance may be registered and not public.

- All three parts must be set and be valid folder names (a part must not start or end with `_`).
  Until then the server secrets stay in replication-manager only, and every server store is
  behind with that reason.
- Registering with Cloud18 sets the name you give. Unregistering does not change it.
- **Renaming** (changing one of the three settings in the global settings, while Cloud18 is off,
  or registering with other values) needs the server credentials grant on every cluster. Every
  kept version of every server secret is then copied to the new name, oldest first. The old place
  keeps what it held and gets a `moved-to` key naming the new place. Until the copy ends, the store
  shows as behind ("moving from … to …").
- Editing a store's place (address, mount, path prefix, cluster, namespace) copies the same
  history to the new place.
- The same parts make the DNS names, the GitLab repository path and the licence identity: a
  licensed instance that is renamed needs a licence for its new name.

### 8.9.4 The configuration file under /etc

When `api-credentials` (or another server secret) is set in `/etc/replication-manager/config.toml`
or a `cluster.d` include, a rotation would leave the banned password in that file. The file wins
again if the versions are ever lost or versioning is turned off. So replication-manager also
writes the new value into the file:

- **`monitoring-etc-credential-writer`**, server scope, **on** by default. In the global settings:
  **Write Rotated Credentials To /etc**. Only the instance's sysops and extsysops may switch it.
  Off stops the writes at once. On is read at the start: switched on later, it writes from the
  next start.
- A packaged install starts as root and drops to the `repman` user (`--user repman`). Just before
  the drop, replication-manager starts a small helper that stays root. It can only replace the value
  of a server secret that is already set as a one-line string in a `[default]` section, in the
  configuration files read at the start. It keeps every other line, comment, owner and mode, and
  keeps the file's form: an encrypted value (`hash_…`) is written encrypted, a plain one plain.
  It never adds a key, and it logs the key and the file, never the value.
- The helper refuses a file that the `repman` user could change itself: the file, one of its
  folders, or a link on the way that belongs to that user, or is writable by one of its groups or
  by anyone. replication-manager then writes that file itself, since it may. Permissions are
  checked at the start: a later `chown` or `chmod` is seen at the next start.
- A replication-manager that never drops root writes the files itself. One started as a
  non-root user (the `-rootless` images) has no helper and writes no file under `/etc`:
  **GWARN025** says so at each rotation of a credential set there. Edit the file by hand.
- **GWARN025** says the file still holds the old value, and why: the writer is off, the key is not a
  one-line string in `[default]`, a file was refused, the helper stopped. The rotation's answer
  says it too (`etcFile`). Fix the cause and rotate again, or edit the file.
- A standby's own `/etc` file is not written: it takes the new version from the configuration
  pull, and the version wins at its start.

### 8.9.5 States

| State | Where | Meaning | What to do |
| --- | --- | --- | --- |
| **GWARN024** | Server | A server credential store is behind: it does not hold the current server secrets. | Check the store's address and sign-in, the cluster that carries it, and the instance's name. |
| **WARN0320** | Each cluster, security | The same, with the store's name only. | Ask the instance's sysops. |
| **GWARN025** | Server | A configuration file under `/etc` still holds the old value of a credential. | Fix the cause given, then rotate again or edit the file. |

### 8.9.6 Limits

- **The standby** has the old list until its next configuration pull (8.9.2).
- **A cluster renamed** does not move its cluster secrets to the new name in a vault yet.
- **A store list set in a configuration file** (`server-secret-kv` pinned in `config.toml`) is
  not saved by replication-manager: a rename made while replication-manager is stopped is not
  copied to the new name.

### 8.9.7 One-time password links

Since **3.1.50** replication-manager **never puts a password in a mail**. A mail is copied,
forwarded and kept in mailboxes for years; the mail carries a link that works **once**:

    https://<api-public-url>/reveal#<code>

- **Who gets one:** the person who rotates a password (at their login's email, when their
  role covers the level, see 8.9.0), the recipients of the **credentials mails** (sponsor,
  DBA, sysadmin: the server addresses stay in the mail, the password is behind the link, one
  link per recipient), and the recipients of a cluster's **Vault token**.
- **Opening it:** the page asks for a click before showing anything, so a mail scanner that
  opens the link burns nothing. After the click the secret is shown once and deleted from the
  server: copy it to a safe place (a password manager) at once. Opening the link again says it
  was already opened. If you did not open it yourself, someone else saw the secret: tell your
  administrator, who rotates the password again.
- **Expiry:** 24 hours by default (`api-secret-reveal-expire-hours`).
- **Where the secrets wait:** in replication-manager's working directory, encrypted with the
  instance's key, never pushed to git. Only a fingerprint of each link's code is kept.
- **Requirements:** a mail server (`mail-smtp-addr`, `mail-from`) and an **API Public URL**
  (`api-public-url`) that the recipients can reach, in https. Without them, a rotated password
  is shown once to the person who rotated it, as before.
- **Turning it off:** `api-secret-reveal = false` (global settings, **Mail Passwords as
  One-time Links**). A rotated password is then shown to the person who rotated it, and the
  credentials mails are **not sent**: a password is never mailed in clear.

