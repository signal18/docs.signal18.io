---
title: Database Compliance
taxonomy:
    category: docs
---

## 7.4.1 Overview

replication-manager ships a **continuous compliance engine** that audits every managed database server against a library of security controls. Findings are raised as typed error codes (`SEC01xx`), logged to `security.log`, surfaced in the GUI, and — where possible — fixed automatically or semi-automatically without a DBA touching the server by hand.

The compliance engine runs as part of the normal monitoring loop. It costs nothing to run and adds no load to the database servers — all checks are read-only queries against `SHOW GLOBAL VARIABLES`, `mysql.user`, and `INFORMATION_SCHEMA`.

---

## 7.4.2 Covered Controls

The security plugins implement controls drawn from the **CIS MySQL / MariaDB Benchmark** and Signal18's own operational experience operating thousands of database clusters.

| SEC Code | Control | Description | Auto-fixable |
|----------|---------|-------------|--------------|
| SEC0100 | No-password accounts | Accounts with empty `authentication_string` and no socket auth | Yes — `ACCOUNT LOCK` |
| SEC0101 | Weak auth plugin | `mysql_native_password` or `mysql_old_password` in use | No — requires app changes |
| SEC0102 | `local_infile` enabled | Allows `LOAD DATA LOCAL INFILE` file-read attack (CIS 4.5) | Yes — `SET GLOBAL local_infile=0` |
| SEC0103 | Plaintext transport | `require_secure_transport=OFF` — non-TLS connections accepted | Partial — cnf advisory |
| SEC0104 | General query log | All SQL including plaintext passwords logged to disk | Yes — `SET GLOBAL general_log=0` |
| SEC0105 | `secure_file_priv` unset | Server can read/write any filesystem path | Partial — requires restart |
| SEC0106 | DNS hostname resolution | `skip_name_resolve=OFF` enables hostname spoofing | Partial — requires restart |
| SEC0107 | Anonymous accounts | `User=''` in `mysql.user` — passwordless login for anyone | Yes — `DROP USER IF EXISTS ''@host` |
| SEC0108 | Wildcard-host privileged user | `Host='%'` with SUPER/ALL/ADMIN privilege | No — informational |
| SEC0109 | Table encryption off | `innodb_encrypt_tables` / `aria_encrypt_tables` both OFF | Yes — rolling restart |
| SEC0110 | Binlog encryption off | `encrypt_binlog` / `binlog_encryption` both OFF | Yes — rolling restart |
| SEC0111 | Tmp file encryption off | `encrypt_tmp_files` / `encrypt_tmp_disk_tables` both OFF | Yes — rolling restart |
| SEC0112 | Audit logging off | `server_audit` plugin not loaded or logging disabled | Yes — `INSTALL SONAME` |
| SEC0113 | No password length check | `simple_password_check` plugin not active (MariaDB) | Yes — `INSTALL SONAME` |
| SEC0114 | No dictionary check | `cracklib_password_check` plugin not active (MariaDB) | Yes — `INSTALL SONAME` |
| SEC0115 | No password reuse check | `password_reuse_check` plugin not active (MariaDB 10.7+) | Yes — `INSTALL SONAME` |
| SEC0118 | Hostname grants + skip_name_resolve | Dead grants when name resolution is disabled | No — informational |

For full details on each code, logic, and configuration keys see [Security Plugins](/plugins/security-plugins).

---

## 7.4.3 How It Works

### 7.4.3.1 Detection

At every monitoring tick, each loaded security plugin queries the server and emits zero or more findings. Each finding carries:

- **SEC code** — uniquely identifies the control
- **Server** — the specific `host:port` where the issue was found
- **Description** — human-readable explanation of what was detected
- **Remediation** — what needs to change and whether it can be applied automatically

Findings are written to `security.log` and exposed in the cluster state so the GUI can display them as badge alerts on each server card.

### 7.4.3.2 Remediation Plan

The remediation engine aggregates all open findings into a plan you can retrieve at any time:

```bash
curl -s https://repman-host:10005/api/clusters/{cluster}/security/remediation-plan \
  -H "Authorization: Bearer $TOKEN" | jq .
```

Each entry in the plan lists the finding, whether it is auto-fixable, and the risk level (`safe` / `moderate` / `disruptive`).

### 7.4.3.3 Applying a Fix

Auto-fixable findings can be resolved with a single API call:

```bash
curl -s -X POST \
  https://repman-host:10005/api/clusters/{cluster}/security/fix-state/SEC0102 \
  -H "Authorization: Bearer $TOKEN"
```

Safe fixes (`local_infile`, `general_log`, anonymous accounts, audit logging, password plugins) are applied at runtime with no service interruption. Disruptive fixes (at-rest encryption) trigger a **rolling restart** that respects the replication topology — primaries are restarted last.

---

## 7.4.4 Getting the Security Plugins

Security plugins are distributed through the **Signal18 plugin registry** and require a **registered instance**.

### 7.4.4.1 Registration — Free

Registration is **free** and takes under two minutes. It gives you:

- Full access to all community security plugins (`plugin-security-*`, `plugin-security-hardening`)
- Automatic plugin updates via the registry — no manual binary management
- Configuration backup and restore via GitLab
- Access to the Signal18 community chat

To register, see [Registration & SSO](/installation/registration). Once connected, plugins are downloaded on the next replication-manager startup and updated automatically.

**Required configuration to enable the compliance engine:**

```toml
cloud18              = true
monitoring-plugins   = true
```

Or as flags:

```bash
replication-manager monitor \
  --cloud18 \
  --monitoring-plugins \
  ...
```

### 7.4.4.2 Subscription Plans

All security plugins are available on **every plan including Free**. The plan affects support and services, not plugin access.

| Plan | Security plugins | Support | DBA services | Alert forwarding |
|------|-----------------|---------|--------------|-----------------|
| Free | All | Community chat | — | — |
| Support | All | Signal18 engineering | — | Yes |
| Support + Services | All | Signal18 engineering | 12 days/year | Yes |
| Partner | All | Signal18 engineering | — | Yes |

> **Alert forwarding** — Support, Support+Services, and Partner plans forward cluster ALERT/ALERTOK events (including compliance findings at alert level) to the Signal18 operations team automatically. Free plan findings are surfaced locally only.

To change your plan: **Global Settings → Cloud18 → Marketplace** in the GUI, or see [Subscription Plans](/installation/registration#2-5-6-subscription-plans).

---

## 7.4.5 Compliance Tags

When a fix deploys a configuration change, it works through the **compliance module tag mechanism** rather than writing raw SQL. This ensures the fix survives server restarts by deploying a `.cnf` fragment alongside the runtime `SET GLOBAL` call.

Key security tags:

| Tag | Controls | Dynamic |
|-----|----------|---------|
| `with_sec_localinfile` | SEC0102 | Yes |
| `with_log_general` | SEC0104 | Yes |
| `with_sec_keyfileencrypt` | SEC0109/0110/0111 | No — rolling restart |
| `audit` | SEC0112 | Yes |
| `with_sec_pwdchecksimple` | SEC0113 | Yes |
| `with_sec_pwdcheckcracklib` | SEC0114 | Yes |
| `with_sec_pwdcheckreuse` | SEC0115 | Yes (MariaDB 10.7+) |
| `with_sec_securetransport` | SEC0103 | Yes (MariaDB 10.3.5+) — advisory |
| `with_sec_securefilepriv` | SEC0105 | No — advisory |
| `with_sec_skipnameresolve` | SEC0106 | No — advisory |

---

## 7.4.6 Considerations Before Applying Fixes

- **At-rest encryption (SEC0109–0111):** requires a key file pre-deployed to every server at the path specified in the `with_sec_keyfileencrypt` tag. Applying the tag without a valid key file will cause the server to fail to start after the rolling restart. Verify the key file path on all nodes before applying.
- **Rolling restarts:** the remediation engine respects replication topology and restarts replicas before the primary. Review cluster health before applying any disruptive fix.
- **Weak auth plugin (SEC0101):** migrating away from `mysql_native_password` or `mysql_old_password` requires updating application connection strings. No automated fix is available — plan the migration carefully.
- **Wildcard-host accounts (SEC0108):** informational only. Restricting `%`-host accounts requires auditing which applications connect with that account before changing.
- **`secure_file_priv` and `skip_name_resolve` (SEC0105/0106):** require server restart. The compliance engine sets a restart cookie and performs a rolling restart — plan for brief per-node downtime.
