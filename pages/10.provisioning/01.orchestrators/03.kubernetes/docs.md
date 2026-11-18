---
title: Kubernetes
taxonomy:
    category: docs
---


##### `kube-config` (2.1)

| Item | Value |
| ---- | ----- |
| Description | path to K8S cluster config file |
| Type | String |
| Default | ""  |
| Example | "/Users/apple/.kube/config" |

## Storage

### Database Storage and StorageClass Selection

replication-manager creates a PersistentVolumeClaim (PVC) for each database
server's data directory. Kubernetes and whichever storage provisioner or CSI
driver is installed in the cluster are responsible for provisioning and
binding that PVC to an actual PersistentVolume (PV) — replication-manager
does not install, manage, or otherwise interact with the storage provisioner
itself.

```
Storage backend
    ↓
Kubernetes provisioner / CSI driver
    ↓
StorageClass
    ↓
replication-manager
    ↓
PVC
    ↓
PV
    ↓
MariaDB /var/lib/mysql
```

##### `prov-kube-storage-class` (3.1.42)

| Item | Value |
| ---- | ----- |
| Description | StorageClass requested by newly created database PVCs |
| Type | String |
| Default | "" (unset) |
| Example | "shared-storage" |

When `prov-kube-storage-class` is left empty, replication-manager leaves the
PVC's `storageClassName` unset. Kubernetes then applies its own normal
StorageClass behavior — typically the cluster's default StorageClass, if one
is configured. An empty value is not itself a StorageClass selection.

To see which StorageClasses are available in the cluster:

```bash
kubectl get storageclass
```

```
NAME                  PROVISIONER
standard (default)    ...
fast-storage          ...
shared-storage        ...
```

To request a specific one:

```toml
prov-orchestrator       = "kube"
prov-kube-storage-class = "shared-storage"
prov-db-disk-size       = "10G"
```

Newly created database PVCs will request the `shared-storage` StorageClass.
Requested capacity comes from `prov-db-disk-size`; the mount path inside the
database container is always `/var/lib/mysql`.

Database PVCs currently request the `ReadWriteOnce` access mode. The selected
StorageClass and its provisioner must support that access mode.

`prov-kube-storage-class` is backend-neutral: it works with any Kubernetes
StorageClass, whether backed by local storage, NFS/network storage, cloud
block storage, distributed storage, or another CSI-based provisioner.
replication-manager has no preference between them and does not install or
configure the underlying storage system — a working StorageClass is a
prerequisite the operator provides.

Storage accessibility and Pod scheduling are separate concerns. Selecting a
shared/network-backed StorageClass does not by itself change how
replication-manager places database Pods.

> **Important:** The StorageClass is selected when replication-manager
> creates the PVC. Changing `prov-kube-storage-class` does not modify or
> migrate an existing PVC. Existing PVCs keep their original StorageClass.

#### Troubleshooting

**PVC remains `Pending`**

```bash
kubectl get storageclass
kubectl get pvc -A
kubectl describe pvc <pvc-name>
```

Common causes: the requested StorageClass does not exist, the
provisioner/CSI driver backing it is not running, or the provisioner cannot
satisfy the requested claim. The events shown by `kubectl describe pvc`
usually name which of these applies.

**Verify the StorageClass a PVC actually used**

```bash
kubectl get pvc -A
kubectl describe pvc <pvc-name>
```

Compare the `STORAGECLASS` column (or `spec.storageClassName` in the
`describe` output) against the effective `prov-kube-storage-class` value for
that cluster.

**A PVC still shows the old StorageClass after changing `prov-kube-storage-class`**

This is expected — changing the setting does not migrate an existing PVC. See
the note above.

### Database volume layout and generated configuration

replication-manager generates a configuration tree for every database server (`my.cnf`, `conf.d`, `replication-manager.d`, `custom.d`, `ssl`, `dbjob.cnf`). On OpenSVC that tree is the service directory `etc/mysql`. On Kubernetes the database PersistentVolumeClaim (PVC) plays the same role: it holds the data, the generated configuration and the initialization files in separate directories, and the generated `/etc/mysql` is mounted whole into the database and the `dbjobs` containers.

| Directory on the volume | Mounted at | In |
|---|---|---|
| `data` | `/var/lib/mysql` | init, database and `dbjobs` containers |
| `etc/mysql` | `/etc/mysql` | init, database and `dbjobs` containers |
| `init` | `/docker-entrypoint-initdb.d` | init and `dbjobs` containers |

Because the database container reads `/etc/mysql/my.cnf` from the volume, the options replication-manager generates (GTID mode, binary log, paths, memory, TLS) apply whatever the database image contains, and the `dbjobs` container can run backup tools against the same `my.cnf`. Before this layout only `conf.d` was installed, so an image that did not already include `/etc/mysql/conf.d` ignored the generated configuration (for example Percona Server 8.0 started with `gtid_mode = OFF`).

#### Which volumes use it

Only a PVC created by this version of replication-manager, which records the layout as the annotation `replication-manager.signal18.io/pvc-layout: service-dirs-v1`. A PVC without the annotation was created by an earlier version and keeps the behaviour it had, unchanged:
- the volume root is the datadir (`/var/lib/mysql`);
- only `/etc/mysql/conf.d` is mounted (from `.system/conf.d` on the volume) in the init and database containers, and the `dbjobs` container gets no `/etc/mysql` from the volume;
- only the generated `conf.d` fragments are installed, not `my.cnf`, `custom.d`, `ssl` or `dbjob.cnf`; `/docker-entrypoint-initdb.d` is mounted from `.system/init`.

Such a volume is not migrated automatically and is never reinterpreted as the new layout; moving it is an explicit operation that does not exist yet. An annotation value this version does not know is refused: the database is not provisioned on that volume.

To see which layout a volume has (`<namespace>` is the Kubernetes namespace of the cluster; replication-manager uses the cluster name as the namespace):

    kubectl -n <namespace> get pvc -o custom-columns='NAME:.metadata.name,LAYOUT:.metadata.annotations.replication-manager\.signal18\.io/pvc-layout'

An empty `LAYOUT` column means a volume of the earlier layout.

#### Configuration lifecycle

At every start of the Pod, the init container (`<server>-init`) asks replication-manager whether a new configuration is needed and, if so, fetches the configuration generated for the server and installs it on the volume:

| Event | What happens |
|---|---|
| First start on a new volume | the configuration is fetched and the whole `etc/mysql` tree is installed; the database starts only if the installed tree has a non-empty `my.cnf` and a `conf.d` with entries |
| Configuration changed in replication-manager | at the next Pod start the tree is fetched again: `conf.d` and `replication-manager.d` are cleared and rewritten from the generated tree; files you added under `custom.d` or elsewhere in `/etc/mysql` stay, and a generated file of the same name is overwritten |
| Stop then Start, restart of the Pod | the same volume is mounted again; the installed configuration and the data are kept |
| Fetch fails after a first successful install | nothing is replaced and the database starts on the last installed configuration |
| First fetch fails, or the installed tree has no `my.cnf` or an empty `conf.d` | the init container exits with an error, the Pod stays in `Init` and Kubernetes retries |

#### Requirements

- The generated tree must contain a `my.cnf`. replication-manager generates it for the platform tags `docker`, `pkg`, `rpm` and `deb`; the default `prov-db-tags` include `docker`. With none of these tags there is no `my.cnf`, and the init container does not let the database start.
- replication-manager must be reachable from the Pod at the first start.

#### Troubleshooting

A Pod stuck in `Init`:

    kubectl -n <namespace> logs <pod> -c <server>-init

`ERROR: no usable configuration in /etc/mysql ...` means the first fetch failed or the tags above are missing.

#### Sensitive content

The generated tree includes the `ssl` directory, which holds the server's TLS certificates and keys. The volume therefore contains key material, as the OpenSVC service directory does: give the volume, the nodes that hold it and `exec` access to the database Pods the access controls you give a secret.

### Proxy Storage and StorageClass Selection

Set `prov-proxy-disk-size` for the proxy PVC and
`prov-kube-proxy-storage-class` when a specific Kubernetes StorageClass is
required. The PVC is retained when a proxy is unprovisioned, so configuration
and state can be reused by a later same-type provisioning.

##### `prov-kube-proxy-storage-class` (3.1.42)

> **Available since:** replication-manager **v3.1.42**

`prov-kube-proxy-storage-class` is the version 3.1.42 variable for selecting
the StorageClass of proxy PVCs. It is independent from the database
`prov-kube-storage-class` setting.

The proxy config is fetched on pod startup. Changes that affect the Deployment,
Service, image, mounts, or proxy mode require a proxy reprovision or the
corresponding rolling operation. A simple pod restart does not regenerate the
Kubernetes Deployment definition.

For HAProxy, select the desired `haproxy-mode` before provisioning. Changing
the mode while the proxy is provisioned is rejected; unprovision, change the
mode, and provision again.

## Kubernetes Proxy Provisioning (Version 3.1.42)

The Kubernetes orchestrator can provision HAProxy and ProxySQL as native
workloads. For each supported proxy, replication-manager creates:

- A Deployment for the proxy container.
- A Service for client and administration traffic.
- A persistent volume for configuration and proxy state.
- An init container that fetches and applies the generated proxy config.

Native Kubernetes provisioning currently implements HAProxy and ProxySQL.
Other proxy types are not implemented yet.

### Supported Proxy Ports

| Proxy | Service ports |
|---|---|
| ProxySQL | Admin port (`proxysql-admin-port`) and SQL port (`proxysql-port`) |
| HAProxy | Runtime API/admin (`haproxy-api-port`), write (`haproxy-write-port`), read (`haproxy-read-port`), and statistics (`haproxy-stat-port`) |

The generated proxy host is the Kubernetes Service DNS name when
`prov-net-cni` is enabled. This allows replication-manager to address a
separately deployed proxy through the cluster network. A `standby` HAProxy is
different: it always runs locally with replication-manager and does not use the
Kubernetes Service DNS name.

### Proxy Service Names

The value in `haproxy-servers` or `proxysql-servers` becomes the Kubernetes
Service name. It must be one RFC 1035 DNS label:

- Lowercase letters, numbers, and hyphens only.
- It must start with a letter.
- It must not contain dots, colons, uppercase letters, or an IP address.

For example:

```toml
haproxy-servers = "haproxy-readwrite"
proxysql-servers = "proxysql-main"
```

Names must be unique across proxy types in the same Kubernetes cluster. A
retained PVC from a previous proxy type also counts as a name collision. Use a
different name, unprovision the conflicting workload, or deliberately remove
the retained PVC after confirming that its data is no longer needed.
