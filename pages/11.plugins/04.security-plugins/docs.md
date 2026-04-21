---
title: Security Plugins
taxonomy:
    category: docs
---

## 11.4.1 Security Plugins

> **Tier:** Community — requires a free registered instance at gitlab.signal18.io

Security plugins audit the database server's configuration, user accounts, and activity logs for security weaknesses. They emit findings with `SEC` error keys. When a finding is open (not resolved), the **remediation engine** proposes and, for supported codes, automatically applies a fix.

Security findings are routed to `security.log`, separate from the main HA log.

---

## 11.4.2 plugin-security-no-password-user

**Binary:** `plugin-security-no-password-user`  
**Finding:** `SEC0100`  
**Source:** `cluster/logplugin/plugins/plugin-security-no-password-user/main.go`

Flags every database account that has an empty `authentication_string` and is not protected by a socket-based authentication plugin.

An account with no password and no socket auth can be logged into from any host that can reach the database port without any credentials.

**Logic:**
- Skip if `password_empty = false`
- Skip if `account_locked = true` (locked accounts cannot be used to log in)
- Skip if plugin is socket-based: `unix_socket`, `auth_socket`, `gssapi`, `authentication_pam`, `auth_pam`
- Skip if account is in `ignored-users`

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `ignored-users` | `""` | Comma-separated `'user'@'host'` pairs or bare usernames to suppress |

**Automated fix:** `ALTER USER … ACCOUNT LOCK` — reversible, safe.

---

## 11.4.3 plugin-security-weak-auth

**Binary:** `plugin-security-weak-auth`  
**Finding:** `SEC0101`  
**Source:** `cluster/logplugin/plugins/plugin-security-weak-auth/main.go`

Flags accounts using weak or deprecated authentication plugins.

**Strong plugins** (no finding): `ed25519`, `parsec`, `auth_gssapi`, `gssapi`, `unix_socket`, `auth_socket`, `authentication_pam`, `auth_pam`, `caching_sha2_password`, `sha256_password`

**Weak plugins** (trigger SEC0101):
- `mysql_native_password` — SHA-1 based, deprecated in MySQL 8.0, removed in MySQL 8.4
- `mysql_old_password` — DES-based, dangerously weak

Accounts with an empty plugin string fall back to the server default (often `mysql_native_password`) and are optionally flagged via `include-empty`.

**Configuration:**

| Key | Default | Description |
|---|---|---|
| `ignored-users` | `""` | Comma-separated accounts to suppress |
| `include-empty` | `true` | Also flag accounts with no explicit plugin set |

**Remediation:** advisory only. Migration from `mysql_native_password` to `ed25519` (MariaDB) or `caching_sha2_password` (MySQL 8+) requires updating application connection strings — no automated fix is provided.

---

## 11.4.4 plugin-security-local-infile

**Binary:** `plugin-security-local-infile`  
**Finding:** `SEC0102`  
**Source:** `cluster/logplugin/plugins/plugin-security-local-infile/main.go`

Raises SEC0102 when `local_infile = ON`.

With `local_infile` enabled a malicious or compromised server can instruct a client to read arbitrary local files and send their contents upstream via `LOAD DATA LOCAL INFILE` attack (CVE-2016-3440, CIS MySQL Benchmark control 4.5).

**No configuration keys** — the check is unconditional.

**Automated fix:** `DropDBTag("with_sec_localinfile")` → executes `SET GLOBAL local_infile = 0` on all servers immediately. Risk: **safe**.

---

## 11.4.5 plugin-security-hardening

**Binary:** `plugin-security-hardening`  
**Source:** `cluster/logplugin/plugins/plugin-security-hardening/main.go`

Evaluates a set of CIS MySQL/MariaDB Benchmark hardening controls. Raises findings for:

### 11.4.5.1 SEC0103 — require_secure_transport=OFF

Plaintext (non-TLS) client connections are permitted.

**Trigger:** `require_secure_transport = OFF` in SHOW GLOBAL VARIABLES.

**Remediation:** `cnf_template` advisory — operator must deploy the `with_sec_securetransport` tag (see Compliance Tags below). Risk: **moderate** (existing non-TLS sessions will be dropped).

---

### 11.4.5.2 SEC0104 — general_log=ON

All SQL statements, including those containing plaintext passwords, are written to the general query log.

**Trigger:** `general_log = ON`.

**Automated fix:** `DropDBTag("with_log_general")` → executes `SET GLOBAL general_log = 0` immediately. Risk: **safe**.

---

### 11.4.5.3 SEC0105 — secure_file_priv=''

The server can read or write any filesystem path via `LOAD DATA` / `SELECT INTO OUTFILE`.

**Trigger:** `secure_file_priv` is empty string.

**Remediation:** `cnf_template` advisory — `secure_file_priv` is a read-only variable, requires restart. Risk: **disruptive**.

---

### 11.4.5.4 SEC0106 — skip_name_resolve=OFF

DNS lookups are enabled for connecting clients; hostname spoofing is possible.

**Trigger:** `skip_name_resolve = OFF`.

**Remediation:** `cnf_template` advisory — `skip_name_resolve` is a read-only variable, requires restart. All GRANT statements using hostnames must be converted to IP addresses first. Risk: **disruptive**.

---

### 11.4.5.5 SEC0107 — Anonymous user account

An anonymous (`user=''`) account exists; anyone can connect without a username.

**Trigger:** a row with `User = ''` exists in `mysql.user`.

**Automated fix:** `DROP USER IF EXISTS ''@host` for every anonymous account across all servers. Risk: **safe**.

---

### 11.4.5.6 SEC0108 — Wildcard-host privileged user

An account uses host `%` and holds elevated privilege (SUPER, ADMIN, ALL, or similar).

**Trigger:** `Host = '%'` combined with a sensitive privilege.

Accounts listed in `wildcard-priv-ignored-users` and accounts using socket-based auth plugins are excluded.

**Remediation:** informational only — restricting wildcard-host accounts requires reviewing which applications depend on them.

---

### 11.4.5.7 SEC0109 / SEC0110 / SEC0111 — At-rest encryption (via plugin-score-encryption)

| Code | Variable | Condition |
|------|----------|-----------|
| SEC0109 | `innodb_encrypt_tables` / `aria_encrypt_tables` | Both OFF |
| SEC0110 | `encrypt_binlog` / `binlog_encryption` | Both OFF |
| SEC0111 | `encrypt_tmp_files` / `encrypt_tmp_disk_tables` | Both OFF |

**Automated fix:** `AddDBTag("with_sec_keyfileencrypt")` — deploys the file-key-management plugin configuration and triggers a **rolling restart**. Risk: **disruptive**.

**Pre-requisite:** the key file must exist on every server before the tag is applied.

---

### 11.4.5.8 SEC0112 — Audit logging not active

The `server_audit` plugin is not loaded or `server_audit_logging = OFF`.

**Automated fix:** `AddDBTag("audit")` → `INSTALL SONAME 'server_audit'` and enables logging at runtime. Risk: **safe**.

---

### 11.4.5.9 SEC0113 / SEC0114 / SEC0115 — Password validation plugins *(MariaDB only)*

| Code | Plugin | Variable |
|------|--------|----------|
| SEC0113 | `simple_password_check` | `simple_password_check_minimal_length` |
| SEC0114 | `cracklib_password_check` | `cracklib_password_check_dictionary` |
| SEC0115 | `password_reuse_check` | `password_reuse_check_interval` |

Each code fires when the corresponding plugin is not loaded **or** when `strict_password_validation = OFF` (making validation advisory only).

**Automated fix:** `INSTALL SONAME '<plugin>'` at runtime via the compliance tag, plus `plugin_load_add` deployed to `my.cnf` for persistence. Risk: **safe**.

SEC0115 requires MariaDB **10.7+** — servers below this version are skipped automatically.

---

### 11.4.5.10 SEC0118 — Hostname grants with skip_name_resolve=ON

When `skip_name_resolve = ON`, MySQL/MariaDB never resolves DNS hostnames. Any account whose `Host` column contains a hostname (not an IP, not `%`, not `localhost`) will silently fail every connection attempt — the grant is effectively dead.

**Trigger:** `skip_name_resolve = ON` AND at least one account in `mysql.user` has a hostname in `Host`.

**Remediation:** informational — operator must `RENAME USER 'user'@'hostname' TO 'user'@'<ip>'` for each affected account.

---

**Configuration for plugin-security-hardening:**

| Key | Default | Description |
|---|---|---|
| `wildcard-priv-ignored-users` | `""` | Accounts to exclude from the SEC0108 wildcard-host check |

---

## 11.4.6 Remediation Engine

### 11.4.6.1 Remediation Plan

```
GET /api/clusters/{clusterName}/security/remediation-plan
```

Returns a JSON document listing every open security finding with its available fixes:

```json
{
  "cluster": "prod-db",
  "generated_at": "2026-04-10T08:00:00Z",
  "open_findings": 3,
  "remediations": [
    {
      "err_key": "SEC0102",
      "server": "db1:3306",
      "description": "Server db1:3306: local_infile=ON ...",
      "auto_fixable": true,
      "fixes": [
        {
          "type": "drop_tag",
          "tag": "with_sec_localinfile",
          "description": "Drop 'with_sec_localinfile' tag ...",
          "risk": "safe"
        }
      ]
    }
  ]
}
```

**Fix types:**

| Type | Description |
|---|---|
| `drop_tag` | Remove a compliance tag; executes `mariadb_default:` SQL and removes the `.cnf` |
| `add_tag` | Add a compliance tag; deploys `.cnf` and executes `mariadb_command:` SQL |
| `cnf_template` | Informational — suggested `.cnf` content to create manually |
| `drop_anon_users` | Drop all anonymous accounts |
| `lock_no_password_users` | `ACCOUNT LOCK` on all no-password, non-socket accounts |

**Risk levels:**

| Level | Meaning |
|---|---|
| `safe` | Applied at runtime, no service interruption, fully reversible |
| `moderate` | Applied at runtime but may interrupt active connections |
| `disruptive` | Requires server restart — triggers rolling restart |

---

### 11.4.6.2 Apply a Fix

```
POST /api/clusters/{clusterName}/security/fix-state/{errKey}
```

No request body required. Returns `{"status":"ok"}` on success.

**What each fix does:**

| Code | Action |
|---|---|
| `SEC0100` | `ACCOUNT LOCK` on every no-password, non-socket account across all servers |
| `SEC0102` | `DropDBTag("with_sec_localinfile")` → `SET GLOBAL local_infile = 0` |
| `SEC0104` | `DropDBTag("with_log_general")` → `SET GLOBAL general_log = 0` |
| `SEC0107` | `DROP USER IF EXISTS ''@host` for every anonymous account |
| `SEC0109–SEC0111` | `AddDBTag("with_sec_keyfileencrypt")` → deploy encryption `.cnf` + rolling restart |
| `SEC0112` | `AddDBTag("audit")` → `INSTALL SONAME 'server_audit'` |
| `SEC0113` | `AddDBTag("with_sec_pwdchecksimple")` → `INSTALL SONAME 'simple_password_check'` |
| `SEC0114` | `AddDBTag("with_sec_pwdcheckcracklib")` → `INSTALL SONAME 'cracklib_password_check'` |
| `SEC0115` | `AddDBTag("with_sec_pwdcheckreuse")` → `INSTALL SONAME 'password_reuse_check'` |

---

### 11.4.6.3 Compliance Tags

Replication-manager's remediation engine works through the **compliance module tag mechanism**: safe and moderate fixes deploy or remove a `.cnf` fragment on all servers rather than executing raw SQL directly. This ensures the fix survives server restarts.

Special comment lines inside each `.cnf` file control runtime execution:

| Prefix | When executed |
|---|---|
| `# mariadb_command:` | Immediately when the tag is **added** |
| `# mariadb_default:` | Immediately when the tag is **removed** |
| `# mariadb_alert:` | Advisory note — logged, not executed |

If no runtime SQL line is present, the variables are read-only and a **restart cookie** is set — the settings take effect on the next server start.

**Existing security tags:**

| Tag | SEC Code | Dynamic | Description |
|---|---|---|---|
| `with_log_general` | SEC0104 | Yes | Enables/disables `general_log` |
| `with_sec_localinfile` | SEC0102 | Yes | Enables/disables `local_infile` |
| `with_sec_keyfileencrypt` | SEC0109/110/111 | No (restart) | File-key-management + InnoDB/binlog/tmp encryption |
| `with_sec_ssl` | (SSL prereq) | No (FLUSH SSL) | SSL certificate configuration |
| `with_sec_ed25519` | SEC0101 | No | Loads `auth_ed25519` plugin |
| `with_sec_pwdchecksimple` | SEC0113 | No | Loads `simple_password_check` |
| `with_sec_pwdcheckcracklib` | SEC0114 | No | Loads `cracklib_password_check` |
| `with_sec_pwdcheckreuse` | SEC0115 | No | Loads `password_reuse_check` |

**Proposed tags (cnf_template advisories until added to the module):**

| Tag | SEC Code | Dynamic | Description |
|---|---|---|---|
| `with_sec_securetransport` | SEC0103 | Yes (MariaDB 10.3.5+) | `require_secure_transport = ON` |
| `with_sec_securefilepriv` | SEC0105 | No (restart) | `secure_file_priv = /var/lib/mysql-files` |
| `with_sec_skipnameresolve` | SEC0106 | No (restart) | `skip_name_resolve = ON` |

---

## 11.4.7 SEC Code Reference

| Code | Plugin | Condition | Auto-fixable | Risk |
|---|---|---|---|---|
| SEC0100 | plugin-security-no-password-user | Empty password, no socket auth | Yes | Safe |
| SEC0101 | plugin-security-weak-auth | Weak/deprecated auth plugin | No | — |
| SEC0102 | plugin-security-local-infile | `local_infile = ON` | Yes | Safe |
| SEC0103 | plugin-security-hardening | `require_secure_transport = OFF` | Partial (cnf_template) | Moderate |
| SEC0104 | plugin-security-hardening | `general_log = ON` | Yes | Safe |
| SEC0105 | plugin-security-hardening | `secure_file_priv = ''` | Partial (cnf_template) | Disruptive |
| SEC0106 | plugin-security-hardening | `skip_name_resolve = OFF` | Partial (cnf_template) | Disruptive |
| SEC0107 | plugin-security-hardening | Anonymous account exists | Yes | Safe |
| SEC0108 | plugin-security-hardening | Wildcard-host privileged account | No | — |
| SEC0109 | plugin-score-encryption | Table encryption disabled | Yes | Disruptive |
| SEC0110 | plugin-score-encryption | Binlog encryption disabled | Yes | Disruptive |
| SEC0111 | plugin-score-encryption | Tmp file encryption disabled | Yes | Disruptive |
| SEC0112 | plugin-security-hardening | Audit plugin not loaded | Yes | Safe |
| SEC0113 | plugin-security-hardening | simple_password_check missing | Yes | Safe |
| SEC0114 | plugin-security-hardening | cracklib_password_check missing | Yes | Safe |
| SEC0115 | plugin-security-hardening | password_reuse_check missing | Yes | Safe (MariaDB 10.7+) |
| SEC0118 | plugin-security-hardening | Hostname grants + skip_name_resolve ON | No | — |

---

## 11.4.8 Security Considerations

- **Account operations (SEC0100, SEC0107)** use `ACCOUNT LOCK` and `DROP USER IF EXISTS` — executed directly via the server connection pool, not through the compliance module. `ACCOUNT LOCK` is reversible.
- **Encryption fixes (SEC0109/0110/0111)** require a pre-configured key file at the path specified in `with_sec_keyfileencrypt`. Applying the tag without a valid key file will cause the server to fail to start after the rolling restart.
- **Rolling restart** requires a functioning replication topology. Review cluster health before applying disruptive fixes.
- **SEC0108 (wildcard host)** is informational only — restricting wildcard-host accounts requires reviewing application dependencies.
- **SEC0101 (weak auth plugin)** migration requires updating application connection strings; no automated fix is provided.
