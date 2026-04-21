---
title: Overview
taxonomy:
    category: docs
---

## Security

replication-manager enforces security at every layer: the REST API is TLS-only, all database connections support mutual TLS with CA validation, sensitive configuration values are AES-encrypted at rest, and secrets can be sourced from HashiCorp Vault at runtime. API access is controlled through a credential-based ACL system.

---

## Feature Summary

### TLS — API and Web Interface

The monitoring API and web GUI are served over HTTPS. Server certificate and key are configured per instance. Clients that cannot present a valid certificate can be denied.

### TLS — Database Connections

All connections replication-manager opens to MariaDB/MySQL nodes (monitoring, replication setup, failover) can be secured with TLS. Supported options include server certificates, client certificates, CA chain validation, and per-cluster SSL mode overrides (`db-servers-tls-*`).

### Encrypted Configuration

Passwords, API tokens, and Vault credentials stored in TOML files are AES-encrypted. replication-manager decrypts them at startup using a per-cluster key. Plain-text values are never written back after first encryption.

### HashiCorp Vault Integration

Credentials can be retrieved from Vault at runtime using AppRole authentication. Vault paths, role ID, secret ID, and auth mode are configurable. The integration supports both KV v1 and v2 secret engines.

| Config key | Purpose |
|---|---|
| `vault-server-addr` | Vault server URL |
| `vault-role-id` | AppRole role ID |
| `vault-secret-id` | AppRole secret ID |
| `vault-mode` | Secret engine mode |
| `vault-auth` | Authentication method |

### API Credentials and ACLs

The REST API authenticates all requests against a configurable credential list (`api-credentials`). ACL rules allow fine-grained whitelisting and blacklisting of credentials for both internal and external API access:

- `api-credentials-acl-allow` / `api-credentials-acl-allow-external`
- `api-credentials-acl-discard` / `api-credentials-acl-discard-external`

### Provisioning TLS

TLS certificates for the provisioning orchestration layer (OpenSVC, Kubernetes) are configured separately from the monitoring API (`prov-tls-server-ca`, `prov-tls-server-cert`, `prov-tls-server-key`).

---

## Supply Chain and Regulatory Compliance

A **CycloneDX SBOM** (Software Bill of Materials) is published with every release, listing all 279 Go module dependencies with versions, PURLs, and hashes. Operators can feed the SBOM into standard vulnerability scanners (Grype, Trivy, OSV-Scanner) or SCA platforms (Dependency-Track, Snyk).

Signal18 publishes the SBOM and maintains a vulnerability disclosure process as part of compliance with the **EU Cyber Resilience Act (CRA)**. See [SBOM and CRA Compliance](sbom-cra) for the full details including where to download the SBOM, how to regenerate it, and how to report a vulnerability.

---

## Configuration Guide

See [Configuration Guide](configuration-guide) for all TLS, Vault, encryption, and ACL configuration keys.
