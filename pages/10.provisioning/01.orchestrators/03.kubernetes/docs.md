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

`ReadWriteOnce` does not by itself keep two database Pods apart: see
*Database lifecycle* below.

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

## Database lifecycle: stop, start, restart and unprovision

`ReadWriteOnce` does not keep two database Pods apart by itself: it limits how many *nodes*
attach a volume, not how many Pods on one node mount it, and the database Pod is pinned to
one node. replication-manager therefore protects the operations it starts in two ways:

- before a guarded Start (from zero replicas) or the creation of a missing Deployment, it
  waits until no Pod, terminating ones included, references the database PVC;
- the database Deployment uses the Kubernetes `Recreate` strategy, so a replacement managed
  by the Deployment stops the old Pod before the new one starts.

This is not a distributed lock and not storage fencing: it does not protect against other
actors, as described in *Limits of the protection* below.

### What to expect

| Operation | What replication-manager does |
| --------- | ----------------------------- |
| Stop | Scales the database Deployment to zero replicas and returns. The Pod then terminates (graceful stop). |
| Start | From zero replicas: waits until no Pod references the database PVC (terminating Pods included), then scales to one replica. It returns once the scale-up is requested, not when the database accepts connections. On a Deployment that already runs, Start does not wait for the PVC: it still sends a request for one replica (a `replicas: 1` patch), which changes nothing when the Deployment is already at one replica. |
| Restart, force re-pull, rolling restart, image change | The database Deployment uses the `Recreate` strategy: the old Pod stops, then the new one starts. Expect downtime: the old Pod's graceful stop plus the new Pod's start. |
| Unprovision | Requests the deletion of the Deployment (foreground) and of the Service, then waits until the Deployment is gone. The PVC is kept. |
| Provision | When the Deployment does not exist yet, waits the same way for any Pod still using the PVC (for example after an unprovision that did not complete), then creates it. |

In our tests, a Start issued right after a Stop waited about 20 to 30 seconds when the old
database was slow to stop (a 20-second `preStop` hook in the tests, or the jobs sidecar
killed at the end of the 30-second grace period), and did not wait when the old Pod was
already gone.

New database Deployments are created with the `Recreate` strategy. Deployments created by
an earlier release use the Kubernetes default `RollingUpdate` strategy. Their next restart, force re-pull or configuration update switches them to
`Recreate`; the switch alone does not replace any Pod.

A Start that is waiting reads the Deployment again before each check (every 2 seconds). If it
then sees that another operation has already scaled the Deployment to one replica, it stops
waiting and skips its own scale-up request. This is the only case covered. It does not know
who scaled the Deployment or whether that was safe, so it does not make concurrent Starts, or
changes made by other tools or people, safe in general.

### The 90-second limit

Each of these waits gives up after 90 seconds and the operation fails. The 90 seconds and the
5-second limit on each Kubernetes API request are fixed internal limits, not settings. What
a failure leaves behind depends on the operation:

- **Start**: the scale-up request is not sent. The error reads `Pod(s) still use PVC <name> …`
  and names the Pods. Wait for them to go, or remove a stale Pod object yourself (for example
  an evicted Pod that is still listed, after checking that its database process is not
  running), then retry.
- **Provision**: the database Deployment is not created. Resources created before the check
  (the namespace, the credentials Secret, the PVC, and the headless Service when
  `prov-net-cni` is enabled) may already exist; provisioning again reuses them. The error
  is the same `Pod(s) still use PVC <name> …`.
- **Unprovision**: the deletion of the Deployment and of the Service had already been
  requested and goes on by itself after the timeout. replication-manager sends no further
  or forced deletion. Retry once the Deployment is gone.

After a timed-out Unprovision, do not Start the database server: Start scales an existing
Deployment and fails when the Deployment has already disappeared. Recover the affected
database server only. Other database servers in the same namespace keep running, and
their Deployments and Pods must be left alone.

1. Identify the affected server's objects. Its Deployment is named after the server
   (`<server-name>`) and its data volume claim is `<cluster-name>-<server-name>-claim`.
2. Wait until that Deployment is gone and no Pod references that claim.
   `kubectl get deployment <server-name> -n <cluster-name>` must report `NotFound`. Pods
   of other servers reference their own claims and do not matter here. This lists each Pod
   with the claims it uses:

   ```
   kubectl get pods -n <cluster-name> -o custom-columns='POD:.metadata.name,PVC:.spec.volumes[*].persistentVolumeClaim.claimName'
   ```

   A Pod of the affected server stuck in `Terminating` has to be dealt with outside
   replication-manager, and only once you know that its node is reachable and its database
   process has stopped.
3. Provision that database server again. The PVC was kept, so the new database uses the
   same data volume. Provision is protected by the same check as Start: it refuses a
   Deployment that is still being deleted and, when the Deployment no longer exists, waits
   (up to 90 seconds) for any Pod still using the claim before it creates the new one. If it
   fails with `Pod(s) still use PVC <name> …`, go back to step 2.

replication-manager never force-deletes a Pod.

The database Deployment does not set `terminationGracePeriodSeconds`, so Kubernetes uses
its default of 30 seconds. If you raise it outside replication-manager above about 80
seconds, a Start right after a Stop can reach the 90-second limit while the old Pod is
still terminating; the failure is safe and a later retry succeeds once that Pod is gone.

### Permissions

The identity replication-manager uses needs, in each database namespace (the namespace is
the cluster name), these permissions for provisioning and for the lifecycle operations
above:

| Resource | Verbs | Used by |
| -------- | ----- | ------- |
| `deployments` (`apps`) | `get`, `create`, `patch`, `delete` | `get`: Start, Provision, Unprovision, restart and image change; `create`: Provision; `patch`: Stop, Start, restart, force re-pull, image change; `delete`: Unprovision |
| `pods` | `list` | Start and Provision, to find the Pods that still use the PVC |
| `services` | `create`, `delete` | `create`: Provision; `delete`: Unprovision |
| `persistentvolumeclaims` | `create` | Provision |
| `secrets` | `create`, `patch` | Provision writes the credentials Secret; these two are enough for it to proceed |
| `secrets` | `get` | Provision reads the existing Secret before writing, to skip an unchanged value and to keep the previous value in the `_OLD` entry of the one-deep credential history. Without `get`, Provision still proceeds, logs a warning, and writes the values without keeping `_OLD`. The credential drift check reads the same Secret |

A refused call fails the operation, except the Secret read described above. For the Pod
list, and for the Deployment read of a Start that is waiting, the error names the missing
permission. `get` on `deployments` and `list` on `pods` are the two permissions the waits
add; the others were already needed.

The recommended Role grants the complete set, `get` on `secrets` included. The minimum for
the operations to proceed is the same without `get` on `secrets`, at the price of losing the
`_OLD` credential history.

```yaml
apiVersion: rbac.authorization.k8s.io/v1
kind: Role
metadata: {name: replication-manager-database, namespace: <cluster-name>}
rules:
- {apiGroups: ["apps"], resources: ["deployments"], verbs: ["get","create","patch","delete"]}
- {apiGroups: [""], resources: ["pods"], verbs: ["list"]}
- {apiGroups: [""], resources: ["services"], verbs: ["create","delete"]}
- {apiGroups: [""], resources: ["persistentvolumeclaims"], verbs: ["create"]}
- {apiGroups: [""], resources: ["secrets"], verbs: ["get","create","patch"]}
```

Bind it, with a `RoleBinding` in each database namespace, to the identity replication-manager
uses. When that identity is a ServiceAccount, the subject carries the namespace the
ServiceAccount lives in, which is not the database namespace when replication-manager runs
in another namespace or outside the database namespaces:

```yaml
apiVersion: rbac.authorization.k8s.io/v1
kind: RoleBinding
metadata: {name: replication-manager-database, namespace: <cluster-name>}
roleRef: {apiGroup: rbac.authorization.k8s.io, kind: Role, name: replication-manager-database}
subjects:
- {kind: ServiceAccount, name: <replication-manager-serviceaccount>, namespace: <replication-manager-namespace>}
```

This Role and RoleBinding cover only the operations in this section. Other
replication-manager features need more than this, for example reading the cluster's nodes (a `ClusterRole`), listing StorageClasses, the resource sensor and the
native Pod resize (`list` on `replicasets`, `patch` on the Pod `resize` subresource), and
`create` on `namespaces` (a refusal when the namespace already exists is logged and
tolerated). They are not part of the database lifecycle described here.

The lifecycle was exercised on a real Kubernetes cluster with a broader set of rules than
the Role above, which also included `list` on `deployments`, `get` on `pods`,
`get` and `list` on `services`, `get` on `persistentvolumeclaims`, `list` on `secrets`, and
`list` on `replicasets`. The table lists what the code calls for the operations above; the
Role and RoleBinding above have not themselves been validated end to end against Kubernetes
RBAC enforcement.

### Limits of the protection

The protection covers operations started by replication-manager. It does not cover
someone else deleting or force-deleting a Pod, a change made to the Deployment by another
tool, a node or kubelet that is unreachable (a Pod can stay `Terminating` while its process
still runs), or missing storage fencing. The check reads, then acts: it is not a lock, and
it does not guarantee that only one database process uses the volume against other actors.
The PVC that Unprovision keeps does not mean that no database process is running: only the
absence of Pods that reference it, on nodes that are reachable, does. The check never
force-deletes anything.
The waits cannot be cancelled: a Start, Provision or Unprovision that is waiting holds the
operation that called it until the wait succeeds, fails, or reaches the 90 seconds, and
stopping or removing the cluster in replication-manager meanwhile does not interrupt it.
It was tested with MariaDB 11.8 on one Kubernetes cluster, with local storage.

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
