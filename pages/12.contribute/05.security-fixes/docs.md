---
title: Security Fixes and SBOM
taxonomy:
    category: docs
---

## 12.5 Security fixes and the SBOM

Every **replication-manager** release publishes a CycloneDX SBOM,
`sbom-cyclonedx.json`, among the assets of its
[GitHub release](https://github.com/signal18/replication-manager/releases). Besides the
components, it lists the **vulnerabilities** that concern the release:

- our own security advisories, once published;
- the vulnerabilities of the dependencies (Go modules, the dashboard's npm packages,
  the Go standard library).

A security fix is finished only when the SBOM of the fixed release, and of the releases
it concerns, says so. This page is the procedure.

### 12.5.1 Reporting a vulnerability

Never open a public issue for a vulnerability. Use the repository's **Security** tab,
then **Report a vulnerability**: the report is a private draft advisory that only the
maintainers see. Describe the issue, the affected versions you know of, and how to
reproduce it.

### 12.5.2 Fixing it

1. **The advisory stays a draft until the fixed release is out.** A draft is never
   listed anywhere public, the SBOM included: an unfixed issue is not disclosed.
2. **Fill the advisory**: the affected package `github.com/signal18/replication-manager`
   (ecosystem Go), the **vulnerable version range** (for example `< 3.1.43`), the
   **patched version**, the severity with its CVSS vector, and the CWE. The SBOM reads
   these fields: a wrong range means a wrong status for every release.
3. **Write the fix** on a branch and a pull request like any other change, without
   describing the attack in the commit message, the pull request or the issue until the
   advisory is published.
4. **Release** the fixed version.
5. **Publish the advisory**, with the patched version set to that release.
6. **Refresh the SBOM** of the releases whose status changed, at least the latest one:
   **Actions**, then **Generate SBOM on release**, then **Run workflow**, with the release
   tag (for example `v3.1.44`). The workflow rebuilds the SBOM of that tag with the
   published advisories and the current vulnerability databases, and replaces the asset.
   A new release gets its SBOM automatically when it is published.
7. **Check** the result:

   ```
   gh release download v3.1.44 -R signal18/replication-manager -p sbom-cyclonedx.json
   jq '.vulnerabilities[] | select(.id=="GHSA-xxxx-xxxx-xxxx") | {analysis, affects}' sbom-cyclonedx.json
   ```

   A release in the vulnerable range shows `"state": "exploitable"` and the status
   `affected`; the fixed release shows `"state": "resolved"` and `unaffected`.

### 12.5.3 Dependency vulnerabilities

The SBOM workflow scans the components with **grype**, then runs **govulncheck** on the
release source to know whether our code calls the vulnerable Go function:

| `analysis.state` | Meaning |
| --- | --- |
| `exploitable` | govulncheck finds a call path from our code to the vulnerable function, or our own advisory covers this release. |
| `not_affected` (`code_not_reachable`) | The vulnerable Go module is in the build but our code never calls the vulnerable function. |
| `in_triage` | Not decided automatically: npm packages, GitHub Actions, or a vulnerability the Go database does not know. |
| `resolved` | Our advisory, fixed in an earlier release than this one. |

An `exploitable` dependency vulnerability is a security fix: bump the module (or the Go
toolchain for a standard library entry) in a pull request, release, and the next SBOM
shows it gone. When a fix changes the status of an already published release, refresh
its SBOM as in step 6.

### 12.5.4 Reading the SBOM as a user

```
jq '[.vulnerabilities[] | select(.analysis.state=="exploitable") | {id, severity: .ratings[0].severity, recommendation}]' sbom-cyclonedx.json
```

lists what concerns the release you run, with the version that fixes it.
