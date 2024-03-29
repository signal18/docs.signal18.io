---
title: Security
taxonomy:
    category: docs
---
## Configuration File Security

**replication-manager** provides password obfuscating security by implementing AES encryption.

An encryption key must be generated by running `replication-manager keygen` as root. This ensures that no unprivileged user can read the contents of the encryption key.

With the key now generated, you can create encrypted passwords using `replication-manager password`. Example:
```
# replication-manager password secretpass
Encrypted password hash: 50711adb2ef2a959577edbda5cbe3d2ace844e750b20629a9bcb
```

You can now replace your password in the configuration file using this encrypted hash:
```
user = "root:50711adb2ef2a959577edbda5cbe3d2ace844e750b20629a9bcb"
```

When an encryption key is detected at `replication-manager monitor` start, the encrypted passwords will be automatically decrypted by the application. There is no further configuration change required.

## API Security Configuration

**replications-manager-cli** clients and API use JWT TLS protocol over https.

The REST API is secured using encrypted token that is used to validate user:password credential of the API, the client will use same default password and so enter the API without asking a password but if the configuration `api-credential` is changed, all client will prompt for the password unless given the correct parameter `user` and `password` flags.

##### `api-credential` (2.0), `user` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Rest API credential in [user]:[password] format |
| Type | string |
| Default Value | "admin:repman" |


At startup of the monitor some x509 certificates are loaded from the *replication-manager* share directory to ensure TLS https secure communication.

Replace the files with your own certificate to make sure your deployment is truly secured.


##### `monitoring-ssl-cert  (2.1)   

| Item | Value |
| ---- | ----- |
| Description | HTTPS & API TLS certificate |
| Type | string |
| Default Value | "" |

##### `monitoring-ssl-key (2.1)   


| Item | Value |
| ---- | ----- |
| Description |   HTTPS & API TLS key |
| Type | string |
| Default Value | "" |


```
# Key considerations for algorithm "RSA" ≥ 2048-bit
openssl genrsa -out server.key 2048

# Key considerations for algorithm "ECDSA" ≥ secp384r1
# List ECDSA the supported curves (openssl ecparam -list_curves)
openssl ecparam -genkey -name secp384r1 -out server.key
openssl req -new -x509 -sha256 -key server.key -out server.crt -days 3650
```

In addition at startup replication-manager monitor will generate in memory extra self signed RSA certificate to ensure token encryption exchange for JWT   

## Database Security Configuration

##### `db-servers-tls-ca-cert` (2.0)

| Item          | Value |
| ----          | ----- |
| Description   | Path to the database connection TLS authority certificate. |
| Type          | string |
| Default Value | "" |

##### `db-servers-tls-client-cert` (2.0)

| Item          | Value |
| ----          | ----- |
| Description   | Path to the database connection TLS client certificate. |
| Type          | string |
| Default Value | "" |

##### `db-servers-tls-client-key` (2.0)

| Item          | Value |
| ----          | ----- |
| Description   | Database TLS client key. |
| Type          | string |
| Default Value | "" |

##### `replication-use-ssl` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Replication is created using SSL encryption to replicate from master. |
| Type | boolean |
| Default Value | false |   

Replication-Manager does not set MASTER_SSL_CA , MASTER_SSL_CERT , MASTER_SSL_KEY in CHANGE MASTER command, instead it relies on MySQL MariaDB to get setup for the replication to be using SSL. with this flag replication just add MASTER_SSL=1 to the replication command.   

```
[client]
ssl-ca=cacert.pem
ssl-cert=client-cert.pem
ssl-key=client-key.pem

[mysqld]
ssl-ca=cacert.pem
ssl-cert=server-cert.pem
ssl-key=server-key.pem
```

## Vault Security Configuration

**replication-manager** provides password obfuscating security by using Vault services.

To access your vault server, you must specify its address in the parameter `vault-server-addr` flag. [Here](https://developer.hashicorp.com/vault/tutorials/getting-started/getting-started-dev-server), the documentation to setup a Vault server.

##### `vault-server-addr` (2.3)

| Item          | Value |
| ----          | ----- |
| Description   | Vault server address |
| Type          | string |
| Default Value | "" |

To authenticate and access your data stored in your Vault server, you need to use an authentication method. For now, it is only possible to connect using the Approle method. 
[Here](https://developer.hashicorp.com/vault/docs/auth/approle), the documentation for creating an Approle role.
Be carefull to create the necessary policies for your auth role, you can find documentation [here](https://developer.hashicorp.com/vault/tutorials/auth-methods/approle#step-2-create-a-role-with-policy-attached)


##### `vault-auth` (2.3)

| Item          | Value |
| ----          | ----- |
| Description   | Vault auth method |
| Type          | string |
| Default Value | "approle" |

Specify your role and secret id obtained by creating your approle role with the parameters `vault-role-id` and `vault-secret-id` flags.

##### `vault-role-id` (2.3)

| Item          | Value |
| ----          | ----- |
| Description   | Vault role id |
| Type          | string |
| Default Value | "" |


##### `vault-secret-id` (2.3)

| Item          | Value |
| ----          | ----- |
| Description   | Vault secret id |
| Type          | string |
| Default Value | "" |

```
[mycluster]
vault-server-addr="http://vault.infra.svc.cloud18:8200"
vault-auth="approle"
vault-role-id="bgsg2f1af-7ce4-e938-46d0-4fbuzibfe4r0d"
vault-secret-id="91b1a039-1597-54b7-f304-c703gyct9d4e"

```

Vault allow multiple services to store secret data. **Replication-manager** provides two modes to use Vault services.
Vault can be used to store keys in a secret on a Vault server, the config_store_v2 mode. Vault also provides a feature for automatic password rotation, implemented in the database_engine mode.

##### `vault-mode` (2.3)

| Item          | Value |
| ----          | ----- |
| Description   | Vault mode |
| Type          | string |
| Default Value | "config_store_v2" |

### Config_store_v2 

In this case, the secret path to the remote secret must be specified in the parameters `db-server-credential` flag for the database credential and `replication-credential` flag for the replication-manager credential as `user:password`. The key of your secret, which is store at your secret path, has to be `db-server-credential` for your database credentials and `replication-credential` for the replication credential.

Exemple :
```
secret path : kv/applications/repman
key : db-server-credential
secret key : user:password
```

That means you can store different keys at the same secret path! 

We also need to specify the mount directory of the secret in the `vault-mount` variable.

##### `vault-mount` (2.3)

| Item          | Value |
| ----          | ----- |
| Description   | Vault mount for the secret |
| Type          | string |
| Default Value | "secret" |

Exemple of configuration for a secret store at /kv/applications/repman secret Vault path.

```
[mycluster]
vault-mode="config_store_v2"
db-servers-credential = "applications/repman"
replication-credential = "applications/repman"
vault-mount="kv"

```
In this case, we store database server credential and replication credential at the same secret path, in two different key named by their respective parameters flags.

Be carefull to create the necessary policies to acces for your secret Vault path, you can find documentation [here](https://developer.hashicorp.com/vault/tutorials/getting-started/getting-started-policies)

### Database_engine

 Vault also allow to perform automatic password rotations with a management service of database credential. It allow to create a vault user that will performs password rotations on the specified database(s) at the desired regular time interval.
 First, you have to create a database role in which you will specify the connection url of your database, the username and password which allows Vault to access the database, the allowed role for the database and a SQL statement that will perform the rotation password. [Here](https://developer.hashicorp.com/vault/docs/secrets/databases), the documentation to create a database role.
 Then, you have to create a static-role, with this [documentation](https://developer.hashicorp.com/vault/docs/secrets/databases). A static-role is define by a username, a password and a rotation period.

 Exemple of a database and static-role configuration :

```
#Database configuration
vault write database/config/my-mysql-database \
    plugin_name=mysql-database-plugin \
    connection_url="{{username}}:{{password}}@tcp(proxysql1.emma.svc.cloud18:3306)/" \
    allowed_roles="repman-monitor, repman-replication" \
    username="vaultuser" \
    password="vaultpass"

#SQL statement to perform password rotation
tee rotation.sql <<EOF
ALTER USER '{{name}}'@'%' IDENTIFIED BY '{{password}}';
EOF

#Static-role configuration
vault write database/static-roles/repman-monitor \
    db_name=my-mysql-database \
    rotation_statements=@rotation.sql \
    username="repman" \
    rotation_period=600

vault write database/static-roles/repman-replication \
    db_name=my-mysql-database \
    rotation_statements=@rotation.sql \
    username="repman-rep" \
    rotation_period=600
```

 Once these roles are set up, Vault will perform automatic password rotations for all roles defined in the database role configuration.
 To allow the repman to access the created roles, you have to specify the secret path to access the roles must be specified in the parameters `db-server-credential` flag for the database credential role and `replication-credential` flag for the replication-manager credential role.

 
Exemple of configuration for two static-role store at "database/static-creds/repman-monitor" and "database/static-creds/repman-replication" secret Vault path.

```
[mycluster]
vault-mode="database_engine"
db-servers-credential = "database/static-creds/repman-monitor"
replication-credential = "database/static-creds/repman-replication"

```

 ## Rotation of Database Credentials
 
 So far, replication-manager is taking care of the monitoring and the replication credentials. When an authentication error state is trigger, replication-manager will presume that the password has been rotated and will fetch Vault secret path again.
 Also, we provide a password rotation service which can be accessible from the replication-manager interface or with an api call. This features is available in both Vault mode. In the config_store_v2 mode, replication-manager will generate a new password and update it in the vault secret and the databases. In the database_engine mode, replication-manager will send a resquest of rotation password to vault services, which will generate all the rotation password in the databases.

