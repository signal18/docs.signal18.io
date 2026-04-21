---
title: Software Configurator
taxonomy:
    category: docs
---

## 9.2.1 Software Configurator

The **Database Configurator** generates `my.cnf` files, directory structures, and bootstrap scripts for every database and proxy service managed by replication-manager. It is driven by:

- **Tags** (`prov-db-tags`) — feature flags that each activate a `.cnf` fragment from the embedded compliance module
- **Resource settings** (`prov-db-memory`, `prov-db-disk-size`, `prov-db-disk-iops`, `prov-db-cpu-cores`) — sizing parameters that the compliance module uses to calculate buffer pool sizes, log file sizes, IOPS capacity, and thread counts

The complete configurator documentation — tag reference, config tracking, config discovery, and all `prov-db-*` settings — is in the **[Database Configurator](../../10.configurator)** chapter.

---

## 9.2.2 Config Archive Delivery

The configurator packages everything into a `config.tar.gz` archive served at:

```
GET /api/clusters/{clusterName}/servers/{host}/{port}/config
```

An init container at service startup fetches and unpacks this archive:

```
# OpenSVC
[container#0002]
type    = docker
image   = busybox
netns   = container#0001
command = sh -c 'wget -qO- http://{env.mrm_api_addr}/api/clusters/{env.mrm_cluster_name}/servers/{env.ip_pod01}/{env.port_pod01}/config | tar xzvf - -C /data'
```

To require JWT authentication for the config download (prevents unauthenticated access to embedded passwords):

```toml
api-credentials-secure-config = true
```

---

## 9.2.3 Quick Config Reference

The most commonly set provisioning parameters:

```toml
prov-db-tags        = "innodb,semisync,row,slow,pfs,ssl,threadpool,noquerycache,docker,linux"
prov-db-memory      = "4096"          # MiB
prov-db-disk-size   = "50"            # GiB
prov-db-disk-iops   = "1000"
prov-db-cpu-cores   = "4"
prov-db-config-preserve = true
```

See the [Tag Reference](../../10.configurator/01.tags) for the full list of available tags and their effects.
