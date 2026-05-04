---
title: Enterprise Plugins
taxonomy:
    category: docs
---

## 10.8 Enterprise Plugins

> **Available since:** replication-manager **v3.1.24**

Enterprise plugins are **built-in** — compiled into the repman binary and running on every instance. Some plugins surface known CVEs, replication bugs, and crash/performance issues by matching the running database and tool versions against a curated advisory database. Others keep the database and proxy configuration compliance up to date.

On **paid plans** (Support, Partner), the Signal18 back office pushes frequent updates to advisory databases, configuration compliance, and documentation — all without requiring a new replication-manager release. Supported users receive the latest CVE advisories, MDEV bug tracking, configuration best practices, and variable documentation as soon as the back office publishes them.

On the **free plan**, these plugins still run using the version embedded in the binary at build time. The embedded data is refreshed periodically with each new replication-manager release, but between releases the coverage is frozen.

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

## 10.8.5 Enterprise Configurator Helpers

### Tag content viewer

Each configuration tag in the **Database Configurator** and **Proxy Configurator** has an eye icon that opens a modal showing:

1. **Config file content** — the my.cnf snippet generated by the compliance module for that tag, including comments with SQL commands and documentation references
2. **Documentation links** — MariaDB KB and MySQL official documentation URLs for each variable in the tag, plus community blog references from Percona, jfg-mysql, MySQL On ARM, Petrunia, and other MariaDB/MySQL experts

The documentation database contains 755+ variable mappings with 154 blog references from 20 sources. On paid plans, the documentation database is refreshed by the back office. On the free plan, the version shipped with the binary is used.

### Compliance diff

When a compliance update is pending (WARN0168), operators can review what changed before accepting:

```
GET /api/clusters/{name}/configurator/compliance-diff
```

Returns per-tag changes: which tags were **added**, **removed**, or **modified**, with the old and new cnf content for each.

---

## 10.8.6 Configuration

No per-plugin configuration is needed for the advisory plugins. The JSON databases are managed entirely by the back office.

The only control is the standard enable/disable toggle available in **Settings → Plugins**:

```toml
[mycluster.plugin-config.enterprise-security]
enabled = true   # default: true

[mycluster.plugin-config.enterprise-replication]
enabled = true

[mycluster.plugin-config.enterprise-workload]
enabled = true

[mycluster.plugin-config.enterprise-compliance]
enabled = true
```

---

## 10.8.7 GUI

- **Settings → Plugins**: enable/disable toggle and help description for each enterprise plugin (security, replication, workload, compliance)
- **Security button** (navbar): shows the count of open security alerts (not the score letter)
- **Security Score modal**: score letter grade, open security states including enterprise findings
- **Security Logs tab**: full security log history including enterprise advisory and compliance findings
- **Database Configurator**: eye icon on each tag shows config content + documentation links
- **Cluster warnings**: WARN0168 when a compliance update is pending (in approval mode)

---

## 10.8.8 Enterprise Compliance Refresh

> **Available since:** replication-manager **v3.1.25**

The Signal18 back office maintains and updates the database and proxy configuration compliance modulesets. On paid plans, updated compliance is pushed to registered instances automatically — no restart required.

The `enterprise-compliance` plugin detects when a new compliance version is available and manages the approval workflow.

### How it works

1. The back office publishes updated compliance modulesets to registered instances every 30 minutes
2. replication-manager detects the new files on its next sync cycle (~5 min)
3. The compliance CRC32 is compared against the last accepted version
4. If different, **WARN0168** is raised on the cluster
5. Depending on `prov-auto-update-compliance`:
   - **`true`** (default): auto-updated immediately, WARN0168 clears. Your preserved variables are never overwritten — they always take priority over compliance defaults.
   - **`false`**: WARN0168 stays open. The operator can review the changes in the Configurator page (Review Changes button) and accept when ready.

The same mechanism detects compliance changes from **binary upgrades**: on restart, the previously accepted compliance is loaded from disk. If the new build ships a different compliance module, WARN0168 fires.

### Configuration

```toml
# Default: auto-update compliance best practices (backward compatible)
# Preserved variables are never overwritten.
prov-auto-update-compliance = true

# Require manual review before compliance best practices are updated
prov-auto-update-compliance = false
```

The setting is available as a toggle in **Database Configurator → Auto-Update Compliance**.

### Cluster state: WARN0168

When a compliance change is detected (BO push or binary upgrade):

```
WARN0168: New compliance moduleset available from back office
  (DB CRC: 1a2b3c4d → 5e6f7a8b, Proxy CRC: 9c0d1e2f → 3a4b5c6d).
  Accept the update via Settings to apply.
```

In trust mode (default), this warning fires and auto-clears in the same monitoring tick. In approval mode, it persists until the user accepts.

### Accepting a compliance update

```bash
# Via CLI
curl -X POST -H "Authorization: Bearer <token>" \
  https://<host>:10005/api/clusters/<cluster>/settings/actions/accept-compliance
```

**Required grant:** `db-config-accept-compliance` or `proxy-config-accept-compliance`

Both grants are included in the `db` and `proxy` ACL shorthands, so admin users with `admin:cluster db proxy ...` can accept without additional configuration.

### Persisted compliance

The accepted compliance modules are saved as full JSON files in the cluster working directory:

- `{WorkingDir}/{cluster}/accepted_compliance_db.json`
- `{WorkingDir}/{cluster}/accepted_compliance_proxy.json`

On restart, these are loaded **instead of the embedded modules** to preserve the accepted version across binary upgrades. On first run (no files on disk), the embedded modules are used as the initial baseline.

### Security log findings

| Error key | Condition |
|---|---|
| `ENTCOMP001` | Compliance moduleset refreshed (CRC changed) |
| `ENTCOMPERR001` | Free plan — no compliance files pushed by back office |

### Grants

| Grant | Shorthand | Purpose |
|---|---|---|
| `db-config-accept-compliance` | Part of `db` | Accept DB compliance update |
| `proxy-config-accept-compliance` | Part of `proxy` | Accept Proxy compliance update |

---

## 10.8.9 Advisory JSON Format

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
