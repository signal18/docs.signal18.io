---
title: Score Plugins
taxonomy:
    category: docs
---

## 11.5.1 Score Plugins

Score plugins compute **binary pass/fail security checks** from the server snapshot. They emit `ScoreCheck` entries (not findings) that feed the **SecurityScore** gauge visible in the cluster dashboard. A passing check contributes positively to the score; a failing check deducts points.

Score plugins can also emit **SEC findings** alongside score checks when a condition is both measurable as a score and significant enough to warrant an alert entry.

Score plugins are external binaries with no configurable parameters — their calculation is derived entirely from the current server state (SHOW GLOBAL VARIABLES, mysql.user, cluster context).

---

## 11.5.2 plugin-score-ssl

**Binary:** `plugin-score-ssl`  
**Source:** `cluster/logplugin/plugins/plugin-score-ssl/main.go`

Checks whether TLS/SSL is enabled and properly configured on the server.

**Score checks:**

| Check name | Pass condition |
|---|---|
| `HasSSL` | `have_ssl = YES` |
| `HasSSLCA` | `ssl_ca` is set |

---

## 11.5.3 plugin-score-encryption

**Binary:** `plugin-score-encryption`  
**Source:** `cluster/logplugin/plugins/plugin-score-encryption/main.go`

Evaluates at-rest encryption status for tables, binary logs, and temporary files. Also emits SEC findings when encryption is disabled.

**Score checks:**

| Check name | Pass condition |
|---|---|
| `HasTableEncryption` | `innodb_encrypt_tables = ON` OR `aria_encrypt_tables = ON` |
| `HasBinlogEncryption` | `encrypt_binlog = ON` OR `binlog_encryption = ON` |
| `HasTmpEncryption` | `encrypt_tmp_files = ON` OR `encrypt_tmp_disk_tables = ON` |
| `HasBackupEncryption` | Cluster has Restic backup encryption configured |

**Also emits:** SEC0109 (table), SEC0110 (binlog), SEC0111 (tmp) when the corresponding check fails.

---

## 11.5.4 plugin-score-auth

**Binary:** `plugin-score-auth`  
**Source:** `cluster/logplugin/plugins/plugin-score-auth/main.go`

Checks whether strong password validation is enforced on the server.

**Score checks:**

| Check name | Pass condition |
|---|---|
| `HasStrongPwd` | MariaDB: `strict_password_validation = ON` AND (`simple_password_check` OR `cracklib_password_check`) loaded. MySQL: `validate_password = ON` or `validate_password_policy` set |
| `HasPwdReuse` | `password_reuse_check_interval` variable present (MariaDB 10.7+) |

---

## 11.5.5 plugin-score-network

**Binary:** `plugin-score-network`  
**Source:** `cluster/logplugin/plugins/plugin-score-network/main.go`

Checks network security configuration.

**Score checks:**

| Check name | Pass condition |
|---|---|
| `HasSecureTransport` | `require_secure_transport = ON` |
| `HasLocalInfileOff` | `local_infile = OFF` |

---

## 11.5.6 plugin-score-proxy

**Binary:** `plugin-score-proxy`  
**Source:** `cluster/logplugin/plugins/plugin-score-proxy/main.go`

Checks whether the cluster is using a traffic routing proxy for HA and read/write splitting.

**Score checks:**

| Check name | Pass condition |
|---|---|
| `HasProxy` | Cluster has at least one proxy configured (MaxScale, ProxySQL, HAProxy, etc.) |

---

## 11.5.7 plugin-score-passwords

**Binary:** `plugin-score-passwords`  
**Source:** `cluster/logplugin/plugins/plugin-score-passwords/main.go`

Checks for the presence of no-password and weak-auth accounts.

**Score checks:**

| Check name | Pass condition |
|---|---|
| `HasNoEmptyPwd` | No accounts with empty `authentication_string` (excluding socket-auth accounts) |
| `HasNoWeakAuth` | No accounts using `mysql_native_password` or `mysql_old_password` |

---

## 11.5.8 plugin-score-lts

**Binary:** `plugin-score-lts`  
**Source:** `cluster/logplugin/plugins/plugin-score-lts/main.go`

Checks whether the server is running a Long Term Support (LTS) release of MariaDB.

**Score checks:**

| Check name | Pass condition |
|---|---|
| `HasLTS` | Server version matches a known MariaDB LTS release series (10.6, 10.11, 11.4, …) |

---

## 11.5.9 plugin-score-audit

**Binary:** `plugin-score-audit`  
**Source:** `cluster/logplugin/plugins/plugin-score-audit/main.go`

Checks whether the MariaDB audit plugin is active.

**Score checks:**

| Check name | Pass condition |
|---|---|
| `HasAuditPlugin` | `server_audit_logging = ON` |

---

## 11.5.10 SecurityScore Dashboard

The SecurityScore is a percentage computed from all `ScoreCheck` results across all enabled score plugins:

```
SecurityScore = (passing_checks / total_checks) × 100
```

The score is displayed per-server in the cluster dashboard and can be used to:
- Track hardening progress over time
- Gate automated operations (e.g. block provisioning if score is below threshold)
- Generate compliance reports

Score plugins have no configurable parameters. To change what is scored, enable or disable individual score plugins via the GUI or TOML configuration.
