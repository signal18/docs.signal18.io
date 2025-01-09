---
title: API Client Usage
taxonomy:
    category: docs
---

## API Client Usage

The rest API is using JWT TLS and is served by default on port 10005 by the replication-manager monitor/

Credentials can be customized by setting your own user and password in configuration file.  

```
api-port ="10005"
api-credential = "admin:repman"
```

At startup of the monitor, X509 certificates are loaded from the replication-manager share directory or from keys defined from config files to ensure TLS https secure communication.

Replace those files with your own certificate signed or self signed to make sure your deployment is secured.

```
# Key considerations for algorithm "RSA" ≥ 2048-bit
openssl genrsa -out server.key 2048

# Key considerations for algorithm "ECDSA" ≥ secp384r1
# List ECDSA the supported curves (openssl ecparam -list_curves)
openssl ecparam -genkey -name secp384r1 -out server.key
openssl req -new -x509 -sha256 -key server.key -out server.crt -days 3650
```

At startup **replication-manager** monitor will generate in memory extra self-signed RSA certificate to ensure later token encryption exchange for JWT.

# Calling API via the client

API can be called via command line client to simplify curl syntax with JWT token.

```
./replication-manager-cli api  --url="https://127.0.0.1:10005/api/clusters/ux_dck_zpool_loop/servers/actions/add/192.168.1.73/3306"   --cluster="ux_dck_zpool_loop"
```

# Calling API via curl or wget

TOKEN=$(curl -s -k -X POST -H 'Accept: application/json' -H 'Content-Type: application/json' --data '{"username":"admin","password":"repman"}' https://demo.signal18.io/api/login | jq  -r '.token')

or without json response  

TOKEN=$(curl -s -k -X POST -H 'Accept: text/html' -H 'Content-Type: application/json' --data '{"username":"admin","password":"repman"}' https://127.0.0.1:10005/api/login)


follow by your request:

curl -k -H 'Accept: application/json' -H "Authorization: Bearer ${TOKEN}" https://demo.signal18.io/api/clusters

Or via wget

TOKEN=$(wget  -qO- --no-check-certificate --post-data '{"username":"admin","password":"repman"}' --header 'Accept: text/html' --header 'Content-Type: application/json'  https://127.0.0.1:10005/api/login)

wget -qO- --no-check-certificate --header 'Accept: application/json' --header "Authorization: Bearer ${TOKEN}"  https://127.0.0.1:10005/api/clusters