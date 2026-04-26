---
title: Enterprise Advisory Plugins
taxonomy:
    category: docs
---

## 10.8 Enterprise Advisory Plugins

> **Available since:** replication-manager **v3.1.24**

Enterprise advisory plugins are **built-in** — they are compiled into the repman binary and run on every instance without requiring external binaries. They match the running database and tool versions against a curated advisory database to surface known CVEs, replication bugs, and crash/performance issues.

---

## 10.8.1 Advisory Sources

The advisory database is assembled daily by the Signal18 back office from:

| Source | What it provides |
|---|---|
| **NIST NVD** | CVEs for MariaDB and MySQL with version ranges (`versionStartIncluding` / `versionEndExcluding`) |
| **MariaDB MDEV tracker** | Replication bugs (MDEV-20821, MDEV-28310, MDEV-19577), crash bugs (MDEV-31404, MDEV-30820), memory leaks (MDEV-29032) |
| **GitHub** | Open issues labelled `security` on signal18/replication-manager |
| **Hand-curated CSV** | Entries from Signal18 field experience |

---

## 10.8.2 The Three Plugins

### enterprise-security

Covers **all known MariaDB/MySQL CVEs** plus replication-manager GitHub security issues.

| Field | Value |
|---|---|
| Error keys | `ENT0001`–`ENT9999` (curated), `CVE-*` (NVD auto-imported) |
| Embedded count | ~500 issues |
| NVD query | `mariadb`, `mysql+oracle` |
| Findings route to | Security Logs tab |

### enterprise-replication

Covers **replication subsystem bugs** that cause silent data corruption, crash-safe parallel replication failures, or auto-increment divergence.

| Field | Value |
|---|---|
| Error keys | `RPL0001`–`RPL9999` (curated per affected branch), `CVE-*-replication-*` (NVD) |
| Embedded count | ~20 issues |
| NVD query | `mariadb+replication`, `mysql+replication+oracle` |
| Key MDEV issues | MDEV-20821 (parallel repl crash), MDEV-28310 (non-ROW data corruption), MDEV-19577 (AUTOINC gaps) |
| Findings route to | Security Logs tab |

### enterprise-workload

Covers **CRITICAL and HIGH severity** crash, deadlock, optimizer regression, and memory leak bugs not already covered by the other two plugins.

| Field | Value |
|---|---|
| Error keys | `WRK0001`–`WRK9999` (curated), `CVE-*-workload-*` (NVD) |
| Embedded count | ~260 issues |
| NVD query | `cvssV3Severity=CRITICAL` + `cvssV3Severity=HIGH`, deduplicated against security and replication |
| Key MDEV issues | MDEV-31404 (ALTER TABLE crash), MDEV-29644 (CTE optimizer crash), MDEV-30820 (InnoDB purge deadlock), MDEV-29032 (query cache memory leak), MDEV-31105 (fulltext KILL crash) |
| Findings route to | Security Logs tab |

---

## 10.8.3 Version Matching

Each advisory entry carries two version fields:

| Field | Meaning |
|---|---|
| `affected_from` | First version where the issue exists (empty = all versions) |
| `fixed_in` | First version where the issue is fixed (empty = not yet fixed) |

The plugin checks the server version against this range:

- If the server version is **below** `affected_from` → not affected, no finding
- If the server version is **at or above** `fixed_in` → already patched, no finding
- Otherwise → finding emitted

**Findings auto-resolve** — when the database is upgraded past `fixed_in` the finding silently disappears on the next monitoring tick.

### Non-database products

Advisories can also target non-database products using the `flavor` field:

| Flavor | Version source |
|---|---|
| `MariaDB`, `MySQL`, `Percona` | Database server version (per-server) |
| `repman` | `ToolVersions["repman"]` — replication-manager build version |
| `proxysql`, `maxscale`, `haproxy` | `ToolVersions[type]` — proxy version from monitoring |
| `restic`, `mydumper`, `sysbench` | `ToolVersions[name]` — detected binary version |

---

## 10.8.4 Subscription Plan Gating

### Back-office delivery

The advisory JSON is pushed daily to each registered instance's GitLab pull repository. The back office **only pushes to paid plans** (`support`, `support-services`, `partner`). Free-plan instances never receive updated files.

### Plugin-side alert

When the subscription plan is `free` or unset, each enterprise plugin emits a persistent security error:

| Error | Plugin |
|---|---|
| `ENTERR001` | enterprise-security — "enterprise security advisories are not refreshed on the free plan" |
| `RPLERR001` | enterprise-replication — "enterprise replication advisories are not refreshed on the free plan" |
| `WRKERR001` | enterprise-workload — "enterprise workload advisories are not refreshed on the free plan" |

The embedded advisory database still runs — it just won't receive new CVEs discovered after the binary was built.

---

## 10.8.5 Configuration

No per-plugin configuration is needed. The advisory JSON is managed entirely by the back office.

The only control is the standard enable/disable toggle available in **Settings → Plugins**:

```toml
[mycluster.plugin-config.enterprise-security]
enabled = true   # default: true

[mycluster.plugin-config.enterprise-replication]
enabled = true

[mycluster.plugin-config.enterprise-workload]
enabled = true
```

---

## 10.8.6 GUI

- **Settings → Plugins**: enable/disable toggle and help description for each enterprise plugin
- **Security button** (navbar): shows the count of open security alerts (not the score letter)
- **Security Score modal**: score letter grade, open security states including enterprise findings
- **Security Logs tab**: full security log history including enterprise advisory findings

---

## 10.8.7 Advisory JSON Format

All three plugins use the same JSON schema. The file is stored at `{ShareDir}/plugins/data/enterprise-{type}-issues.json`.

```json
{
  "version": "1",
  "generated_at": "2026-04-25T09:10:15Z",
  "source": "signal18-backoffice",
  "issues": [
    {
      "id": "ENT0001",
      "cve": "CVE-2022-27458",
      "mariadb_jira": "MDEV-26281",
      "github_issue": "",
      "severity": "SECURITY",
      "title": "MariaDB use-after-free in Binary_string::free_buffer()",
      "description": "Server {server_url} is running {flavor} {version} which is affected by ...",
      "flavor": "MariaDB",
      "affected_from": "10.7.0",
      "fixed_in": "10.7.3",
      "remediations": [
        {
          "type": "repman_config",
          "description": "Upgrade to MariaDB 10.7.3 or later.",
          "risk": "disruptive"
        }
      ]
    }
  ]
}
```

### Description placeholders

| Placeholder | Replaced with |
|---|---|
| `{server_url}` | The monitored server URL (e.g. `db1.example.com:3306`) |
| `{flavor}` | Database flavor (e.g. `MariaDB`) |
| `{version}` | Database version (e.g. `10.6.12`) |

Reference tags (CVE, MDEV, GitHub issue, fix version) are automatically appended to the description by the plugin.
