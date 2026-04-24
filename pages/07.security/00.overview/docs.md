---
title: Overview
taxonomy:
    category: docs
---

## 7.1.1 Security

replication-manager enforces security at every layer: the REST API is TLS-only, all database connections support mutual TLS with CA validation, sensitive configuration values are AES-encrypted at rest, and secrets can be sourced from HashiCorp Vault at runtime. API access is controlled through a credential-based ACL system.

---

## 7.1.2 Feature Summary

### 7.1.2.1 Process Privilege Drop (`--user`)

replication-manager can start as `root` to bind a low-numbered HTTPS port and then immediately drop to a less-privileged OS user via `syscall.Setuid` / `syscall.Setgid` once the listeners are ready. The drop is irreversible — the process cannot regain root after this point. See [Configuration Guide](configuration-guide) for the full drop sequence, directory ownership requirements, and a systemd unit example.

### 7.1.2.2 TLS — API and Web Interface

The monitoring API and web GUI are served over HTTPS. Server certificate and key are configured per instance. Clients that cannot present a valid certificate can be denied.

### 7.1.2.3 TLS — Database Connections

All connections replication-manager opens to MariaDB/MySQL nodes (monitoring, replication setup, failover) can be secured with TLS. Supported options include server certificates, client certificates, CA chain validation, and per-cluster SSL mode overrides (`db-servers-tls-*`).

### 7.1.2.4 Encrypted Configuration

Passwords, API tokens, and Vault credentials stored in TOML files are AES-encrypted. replication-manager decrypts them at startup using a per-cluster key. Plain-text values are never written back after first encryption.

### 7.1.2.5 HashiCorp Vault Integration

Credentials can be retrieved from Vault at runtime using AppRole authentication. Vault paths, role ID, secret ID, and auth mode are configurable. The integration supports both KV v1 and v2 secret engines.

| Config key | Purpose |
|---|---|
| `vault-server-addr` | Vault server URL |
| `vault-role-id` | AppRole role ID |
| `vault-secret-id` | AppRole secret ID |
| `vault-mode` | Secret engine mode |
| `vault-auth` | Authentication method |

### 7.1.2.6 API Credentials and ACLs

The REST API authenticates all requests against a configurable credential list (`api-credentials`). ACL rules allow fine-grained whitelisting and blacklisting of credentials for both internal and external API access:

- `api-credentials-acl-allow` / `api-credentials-acl-allow-external`
- `api-credentials-acl-discard` / `api-credentials-acl-discard-external`

### 7.1.2.7 Provisioning TLS

TLS certificates for the provisioning orchestration layer (OpenSVC, Kubernetes) are configured separately from the monitoring API (`prov-tls-server-ca`, `prov-tls-server-cert`, `prov-tls-server-key`).

---

## 7.1.3 HTTPS Bastion — Browser Terminal Access

replication-manager doubles as a **secure bastion host**: operators can open `bash`, `mysql`, and `mytop` terminals to any managed database node or proxy directly in the browser, with all traffic carried over the existing HTTPS/WebSocket connection. No SSH port needs to be exposed to the operator's workstation.

Authentication uses the same RSA JWT as the REST API. Role-based credential selection ensures users connect to the database with the appropriate privilege level — the credential is injected server-side and never sent to the browser. Sessions can be made resumable via tmux or screen.

See [HTTPS Bastion and Terminal](https-bastion) for configuration, role mapping, OpenSVC integration, and ACL grants.

---

## 7.1.4 Network Traffic

All network flows required for a replication-manager deployment — internal cluster traffic and outbound connections to Signal18 cloud services (CRM, GitLab, Meet) — are documented in [Network Traffic](network-traffic). The page cross-references the inbound port requirements in [Network Ports](/installation/network-ports) and provides a firewall rule summary for egress-filtered environments.

---

## 7.1.5 Database Compliance

replication-manager includes a continuous compliance engine that audits every managed database server against **CIS MySQL/MariaDB Benchmark** controls. Findings are raised as typed `SEC01xx` codes, surfaced in the GUI, logged to `security.log`, and — where possible — fixed automatically at runtime or via a rolling restart, without manual DBA intervention.

The engine is powered by **security plugins** distributed through the Signal18 registry. Plugins are available on all plans, including Free. A registered instance (`cloud18 = true`, `monitoring-plugins = true`) downloads and updates the plugin library automatically.

See [Database Compliance](database-compliance) for the full control list, remediation API, plugin installation, and plan comparison.

---

## 7.1.6 Supply Chain and Regulatory Compliance

A **CycloneDX SBOM** (Software Bill of Materials) is published with every release, listing all 279 Go module dependencies with versions, PURLs, and hashes. Operators can feed the SBOM into standard vulnerability scanners (Grype, Trivy, OSV-Scanner) or SCA platforms (Dependency-Track, Snyk).

Signal18 publishes the SBOM and maintains a vulnerability disclosure process as part of compliance with the **EU Cyber Resilience Act (CRA)**. See [SBOM and CRA Compliance](sbom-cra) for the full details including where to download the SBOM, how to regenerate it, and how to report a vulnerability.

---

## 7.1.7 Configuration Guide

See [Configuration Guide](configuration-guide) for all TLS, Vault, encryption, and ACL configuration keys.
