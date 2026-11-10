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

## In-Cluster replication-manager Connectivity

### Example: Using a Headless Service for SST Connectivity

This is **one possible deployment approach**, not a requirement. It applies when
a single replication-manager Pod runs inside the cluster and database Pods have
to reach it by a stable DNS name (see `monitoring-address`).

The replication-manager API listens on known ports (`http-port` 10001 and
`api-port` 10005 by default). Physical backup (SST) streaming can also use a TCP
receiver port that replication-manager allocates dynamically for each transfer.
A regular ClusterIP Service only routes traffic to the ports listed in its spec,
so a connection from a database Pod to a dynamic receiver port through that
Service name, for example
`repman-incluster.repman-system.svc.cluster.local:44975`, is not routed to the
replication-manager Pod.

A Headless Service (`clusterIP: None`) has no virtual IP. Its DNS name resolves
directly to the IP of the selected Pod, so a database Pod connects straight to
replication-manager on whatever port the receiver opened, provided the Pod
network allows it.

#### Example Service

The names, namespace, labels and ports below are an example. Adapt them to your
own deployment: the `selector` must match the labels of your replication-manager
Pod, and the ports must match your `http-port` and `api-port`.

```yaml
apiVersion: v1
kind: Service
metadata:
  name: repman-incluster-direct
  namespace: repman-system
spec:
  clusterIP: None
  selector:
    app: repman-incluster
  ports:
    - name: http
      port: 10001
      targetPort: 10001
    - name: api
      port: 10005
      targetPort: 10005
```

This Service is created in addition to the regular replication-manager Service,
which can stay in place for other clients.

#### Example configuration

Point `monitoring-address` at the headless Service name:

```toml
monitoring-address = "repman-incluster-direct.repman-system.svc.cluster.local"
```

replication-manager uses `monitoring-address` when it generates the database job
configuration, so the `dbjobs` scripts connect back to this name. For servers
that already exist, the two places that use the address are updated differently:

- The bootstrap URL used by the init container to fetch the configuration is
  embedded in the Kubernetes Deployment when the server is provisioned.
  Existing Deployments retain that URL until their specification is updated
  (for example, through reprovisioning).
- The generated `dbjobs` configuration can be refreshed without a full
  reprovision.

#### Verification

Check that the Service is headless:

```bash
kubectl -n repman-system get svc repman-incluster-direct -o jsonpath='{.spec.clusterIP}{"\n"}'
```

```
None
```

Check that its endpoint is the expected replication-manager Pod:

```bash
kubectl -n repman-system get endpoints repman-incluster-direct
kubectl -n repman-system get pod -l app=repman-incluster -o wide
```

The endpoint IP must be the IP of the replication-manager Pod.

Check that the name resolves from a database Pod (run from the `dbjobs`
container, `<server>-dbjobs`):

```bash
kubectl -n k8s-mdb exec <db-pod> -c <server>-dbjobs -- \
  getent ahostsv4 repman-incluster-direct.repman-system.svc.cluster.local
```

Check that the `dbjobs` script uses the intended hostname:

```bash
kubectl -n k8s-mdb exec <db-pod> -c <server>-dbjobs -- \
  grep -E '^readonly REPLICATION_MANAGER_(URL|HOST|ADDR)' /docker-entrypoint-initdb.d/dbjobs_new
```

A successful physical backup is an additional end-to-end validation of the
dynamic receiver port.

#### Limitations

- The example assumes a single replication-manager Pod selected by the Service.
  A Headless Service that selects several replication-manager Pods does not
  guarantee that a database Pod reaches the Pod that opened the SST receiver.
- NetworkPolicy and the Pod network must allow traffic from the database Pods to
  the replication-manager Pod on the required TCP ports, including the dynamic
  receiver ports.
- Other networking approaches may also work. A Headless Service is not a
  universal requirement.
