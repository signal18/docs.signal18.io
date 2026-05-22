---
title: Security & Configuration
taxonomy:
    category: docs
---

### 15.5.1 How do I change the default API credentials?

**Default credentials**: `admin:repman` (INSECURE)

**Security risk**: Default credentials must be changed before production use.

**Configuration parameter:**

```
[Default]
api-credentials = "myuser:mypassword"
```

**Effect**:
- All API and CLI access requires new credentials
- Web UI login uses new credentials
- CLI clients will prompt for password unless specified

**CLI usage with custom credentials:**
```
replication-manager-cli --user=myuser --password=mypassword status
```

**Best practice**: Use encrypted passwords (see password encryption question below).

**Reference**: `/pages/05.configuration/07.security/docs.md:31`

---

### 15.5.2 How do I encrypt passwords in configuration files?

**Three-step process:**

**Step 1: Generate encryption key** (as root)
```
replication-manager keygen
```

This creates an encryption key accessible only to root.

**Step 2: Encrypt your password**
```
replication-manager password secretpass
```

Output:
```
Encrypted password hash: 50711adb2ef2a959577edbda5cbe3d2ace844e750b20629a9bcb
```

**Step 3: Use encrypted password in config**
```
db-servers-credential = "root:50711adb2ef2a959577edbda5cbe3d2ace844e750b20629a9bcb"
replication-credential = "repl:50711adb2ef2a959577edbda5cbe3d2ace844e750b20629a9bcb"
```

**Automatic decryption**: When **replication-manager** starts and detects the encryption key, passwords are automatically decrypted.

**Security**: Encryption key is only readable by root, providing basic password obfuscation.

**Reference**: `/pages/05.configuration/07.security/docs.md:6`

---

### 15.5.3 How do I integrate with HashiCorp Vault?

**Two modes available:**

**Mode 1: config_store_v2** (store credentials in Vault secret)

```
[mycluster]
vault-server-addr = "http://vault.example.com:8200"
vault-auth = "approle"
vault-role-id = "your-role-id"
vault-secret-id = "your-secret-id"
vault-mode = "config_store_v2"
vault-mount = "kv"
db-servers-credential = "applications/repman"
replication-credential = "applications/repman"
```

Create Vault secret:
```
vault kv put kv/applications/repman \
  db-servers-credential=root:password \
  replication-credential=repl:password
```

**Mode 2: database_engine** (automatic password rotation)

```
[mycluster]
vault-mode = "database_engine"
db-servers-credential = "database/static-creds/repman-monitor"
replication-credential = "database/static-creds/repman-replication"
```

Configure Vault database role:
```
vault write database/config/my-mysql-database \
    plugin_name=mysql-database-plugin \
    connection_url="{{username}}:{{password}}@tcp(127.0.0.1:3306)/" \
    allowed_roles="repman-monitor,repman-replication" \
    username="vaultuser" \
    password="vaultpass"

vault write database/static-roles/repman-monitor \
    db_name=my-mysql-database \
    username="repman" \
    rotation_period=600
```

**Automatic rotation**: Vault rotates passwords at specified interval; **replication-manager** fetches new passwords on authentication errors.

**Reference**: `/pages/05.configuration/07.security/docs.md:123`

---

### 15.5.4 Do I need to replace the self-signed certificates?

**Yes, for production deployments.**

**Default behavior**: **replication-manager** ships with self-signed certificates for HTTPS and API access.

**Security risk**: Self-signed certificates:
- Can be MitM attacked
- Generate browser warnings
- Don't validate server identity

**Configuration parameters:**

```
[Default]
monitoring-ssl-cert = "/path/to/your/server.crt"
monitoring-ssl-key = "/path/to/your/server.key"
```

**Generate proper certificates:**

```bash
# RSA certificate
openssl genrsa -out server.key 2048
openssl req -new -x509 -sha256 -key server.key -out server.crt -days 3650

# ECDSA certificate (recommended)
openssl ecparam -genkey -name secp384r1 -out server.key
openssl req -new -x509 -sha256 -key server.key -out server.crt -days 3650
```

**Best practice**: Use certificates from your organization's PKI or a trusted CA like Let's Encrypt.

**Reference**: `/pages/05.configuration/07.security/docs.md:40`
