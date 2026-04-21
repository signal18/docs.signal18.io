---
title: SBOM and CRA Compliance
taxonomy:
    category: docs
---

## 7.3.1 SBOM and EU Cyber Resilience Act

The **EU Cyber Resilience Act (CRA)** establishes mandatory cybersecurity requirements for products with digital elements placed on the European market. One of its core obligations is the publication of a **Software Bill of Materials (SBOM)** — a machine-readable inventory of every software component, library, and dependency included in a product.

Signal18 publishes a CycloneDX SBOM for every release of replication-manager to help operators, integrators, and auditors understand the full composition of the software they deploy.

---

## 7.3.2 The Cyber Resilience Act

The CRA entered into force in November 2024. Its obligations phase in over a transition period:

| Date | Obligation |
|---|---|
| September 2026 | Vulnerability and incident reporting obligations apply |
| December 2027 | All other CRA requirements fully apply |

### 7.3.2.1 What the CRA requires

For a product like replication-manager the main obligations are:

- **SBOM** — a structured, machine-readable list of all software components and their versions, published with each release
- **Vulnerability handling** — a documented process to receive, triage, and remediate reported vulnerabilities, and to notify affected users when fixes are available
- **Incident reporting** — actively exploited vulnerabilities must be reported to ENISA or the relevant national authority within 24 hours of discovery; significant security incidents within 72 hours
- **Security updates** — security fixes must be provided for the expected supported lifetime of the product and made available separately from feature updates
- **Secure development** — evidence of a security-aware software development lifecycle (dependency scanning, code review, signed releases)

### 7.3.2.2 Open-source software and the CRA

The CRA contains an exemption (Recital 18) for purely community-developed open-source software that is not commercialized. replication-manager is developed by Signal18 as part of a commercial offering. Signal18 therefore treats its CRA obligations as applying in full and has taken proactive steps to comply ahead of the mandatory deadlines.

---

## 7.3.3 Software Bill of Materials

### 7.3.3.1 Format

replication-manager's SBOM uses the **CycloneDX 1.6** format (JSON). CycloneDX is a widely supported open standard for SBOM, maintained by OWASP, and natively understood by most vulnerability scanners and supply-chain security tools.

The SBOM is generated automatically using [Anchore's SBOM Action](https://github.com/anchore/sbom-action) (backed by [Syft](https://github.com/anchore/syft)) on every published GitHub release.

### 7.3.3.2 What is included

Each SBOM lists the **279 direct and transitive Go module dependencies** of replication-manager, including:

- Module name and version (semver)
- Package URL (PURL) in `pkg:golang/` format
- License identifiers where detectable
- Hashes (SHA-256) for integrity verification

The root component entry identifies the exact build:

```json
{
  "type": "library",
  "name": "github.com/signal18/replication-manager",
  "version": "v3.0.x",
  "purl": "pkg:golang/github.com/signal18/replication-manager@v3.0.x?type=module&goos=linux&goarch=amd64"
}
```

### 7.3.3.3 How to get the SBOM

The SBOM is attached as a release asset to every GitHub release:

```
https://github.com/signal18/replication-manager/releases/latest
  → sbom-cyclonedx.json
```

A development snapshot (`sbom.json`, generated with `cyclonedx-gomod`) is also committed to the repository root and updated with significant dependency changes.

### 7.3.3.4 Generating the SBOM locally

To regenerate the SBOM from source (requires [cyclonedx-gomod](https://github.com/CycloneDX/cyclonedx-gomod)):

```bash
cyclonedx-gomod mod -json -licenses -output sbom.json .
```

Or using Syft (produces the same CycloneDX 1.6 output):

```bash
syft packages . --output cyclonedx-json=sbom-cyclonedx.json
```

---

## 7.3.4 Using the SBOM

### 7.3.4.1 Vulnerability scanning

Feed the SBOM into any CycloneDX-compatible scanner to identify known CVEs in replication-manager's dependencies:

```bash
# Grype (Anchore)
grype sbom:sbom-cyclonedx.json

# OSV-Scanner (Google)
osv-scanner --sbom sbom-cyclonedx.json

# Trivy (Aqua)
trivy sbom sbom-cyclonedx.json
```

### 7.3.4.2 Software composition analysis

Import the SBOM into your organization's SCA or ASPM platform (Dependency-Track, FOSSA, Snyk, etc.) to track replication-manager as a dependency in your own software inventory. Most platforms accept CycloneDX JSON natively.

### 7.3.4.3 License compliance

The SBOM includes SPDX license expressions for each dependency. Use this to verify that the license obligations of all included components are compatible with your organization's policies before deployment.

---

## 7.3.5 Vulnerability Disclosure

### 7.3.5.1 Reporting a vulnerability

Security vulnerabilities in replication-manager should be reported privately to Signal18 before public disclosure. Contact:

**security@signal18.io**

Please include:
- A description of the vulnerability and its potential impact
- Steps to reproduce or a proof-of-concept
- The version(s) of replication-manager affected
- Your preferred contact method for follow-up

### 7.3.5.2 Response commitment

| Milestone | Target |
|---|---|
| Acknowledgement of report | 48 hours |
| Triage and severity assessment | 7 days |
| Fix available for critical/high | 30 days |
| Public disclosure | Coordinated with reporter |

Signal18 follows a coordinated disclosure model: fixes are prepared and made available before public disclosure. Reporters are credited unless they request anonymity.

### 7.3.5.3 Security advisories

Confirmed vulnerabilities are published as GitHub Security Advisories on the [replication-manager repository](https://github.com/signal18/replication-manager/security/advisories). Subscribe to repository notifications to receive alerts.

---

## 7.3.6 Signed Releases

Release artifacts are signed to allow integrity verification. The public key and signature files are published alongside each release on the GitHub releases page.

---

## 7.3.7 Regulatory References

| Document | Summary |
|---|---|
| [EU CRA (Regulation 2024/2847)](https://eur-lex.europa.eu/legal-content/EN/TXT/?uri=OJ:L_202402847) | Full text of the Cyber Resilience Act |
| [ENISA CRA guidance](https://www.enisa.europa.eu/topics/cyber-resilience-act) | ENISA implementation guidance |
| [CycloneDX specification](https://cyclonedx.org/specification/overview/) | SBOM format specification |
| [OWASP CycloneDX](https://owasp.org/www-project-cyclonedx/) | OWASP project page |
