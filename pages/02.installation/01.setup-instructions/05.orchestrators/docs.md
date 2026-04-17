---
title: Install Pro Orchestrators
taxonomy:
    category: docs
---

## Install Pro Version Orchestrators

> **Requires:** `replication-manager-pro` flavor

The **pro** flavor of replication-manager can provision and manage the full lifecycle of database clusters — bootstrapping containers, deploying configuration, starting/stopping services — by delegating to an external orchestrator.

Set the active orchestrator with:

```toml
prov-orchestrator = "opensvc"   # opensvc | kube | slapos | onpremise | local
```

| Orchestrator | `prov-orchestrator` value | Description |
|---|---|---|
| **OpenSVC** | `opensvc` | Service orchestration cluster — the primary supported orchestrator. Default for `pro`. |
| **Kubernetes** | `kube` | Container orchestration via a K8s cluster. |
| **SlapOS** | `slapos` | Distributed service mesh from Nexedi. |
| **On-Premise SSH** | `onpremise` | SSH-based bootstrap onto existing bare-metal or VM hosts. No orchestrator agent required. |
| **Local** | `local` | Bootstrap processes directly on the same host as replication-manager. For testing only. |

`osc` supports `onpremise` and `local` only. All five modes are available in `pro`.

---

## OpenSVC

[OpenSVC](https://www.opensvc.com/) is a service orchestration cluster that manages Docker/Podman micro-services across a pool of agents. replication-manager uses the OpenSVC API to push service definitions and trigger provisioning.

### Install the OpenSVC Agent

On each database host, install the OpenSVC agent:

```bash
# Debian/Ubuntu
curl -o /tmp/opensvc.latest.deb https://repo.opensvc.com/deb/current
dpkg -i /tmp/opensvc.latest.deb

# RHEL/CentOS
curl -o /tmp/opensvc.latest.rpm https://repo.opensvc.com/rpm/current
rpm -ivh /tmp/opensvc.latest.rpm
```

Start and join the cluster:

```bash
om daemon start
om cluster set --param cluster.secret --value <shared-secret>
om daemon join --node <first-node-ip>
```

Full OpenSVC documentation: [docs.opensvc.com](https://docs.opensvc.com/)

### Configure replication-manager for OpenSVC

replication-manager can use either the **Cluster API** (recommended, direct agent HTTP2 API) or the legacy **Collector API** (Signal18 SaaS or on-premise collector).

#### Cluster API (recommended)

Point replication-manager at any OpenSVC cluster node:

```toml
prov-orchestrator           = "opensvc"
opensvc-cluster-host        = "opensvc-node1:1215"
opensvc-cluster-admin-user  = "root@localhost.localdomain:opensvc"
```

#### Collector API (legacy)

```toml
prov-orchestrator    = "opensvc"
opensvc-host         = "collector.mycompany.com:9443"
opensvc-admin-user   = "root@mycompany.com:secret"
```

The Signal18 SaaS collector (`ci.signal18.io:9443`) can be used for evaluation — contact Signal18 to obtain your `account.yaml` and place it in the `share/opensvc/` directory of replication-manager-pro.

### OpenSVC Video Walkthrough

[plugin:youtube](https://www.youtube.com/watch?v=3eYlxZo8rRc)

---

## Kubernetes

replication-manager can deploy database services into a Kubernetes cluster using a standard kubeconfig file.

### Prerequisites

- A running Kubernetes cluster (1.20+)
- `kubectl` configured and working on the replication-manager host
- Sufficient RBAC permissions to create namespaces, deployments, services, and PersistentVolumeClaims

### Configure replication-manager for Kubernetes

```toml
prov-orchestrator = "kube"
kube-config       = "/etc/replication-manager/kube/config"
```

Copy your cluster's kubeconfig to the path referenced above. replication-manager uses the default context from the file.

```bash
cp ~/.kube/config /etc/replication-manager/kube/config
chmod 600 /etc/replication-manager/kube/config
```

---

## SlapOS

[SlapOS](https://slapos.nexedi.com/) is a distributed service mesh used in the Nexedi ecosystem. replication-manager-pro can deploy database instances as SlapOS services.

### Configure replication-manager for SlapOS

```toml
prov-orchestrator = "slapos"
```

Contact Signal18 for SlapOS deployment templates specific to your environment.

---

## On-Premise SSH

The `onpremise` mode bootstraps database configuration and services on existing hosts via SSH — no orchestrator agent required. replication-manager connects as a sudoer user and deploys configuration files, starts/stops services using the host's init system.

### Prerequisites

- SSH access from the replication-manager host to each database host
- A user with passwordless sudo on the database hosts
- MariaDB or MySQL server packages already installed on the database hosts

### Configure SSH access

```toml
prov-orchestrator         = "onpremise"
onpremise-ssh             = true
onpremise-ssh-port        = 22
onpremise-ssh-credential  = "deploy"
```

replication-manager uses its own SSH private key (`~/.ssh/id_rsa` by default). Add the corresponding public key to the `authorized_keys` of the `deploy` user on each database host.

---

## Local (Testing Only)

The `local` orchestrator boots MariaDB or MySQL processes directly on the same host as replication-manager. It is intended for local regression testing and benchmarking — not for production use.

### Prerequisites

- MariaDB or MySQL server binaries installed locally
- HAProxy and/or ProxySQL if proxy testing is needed
- Sysbench for load injection

```toml
prov-orchestrator       = "local"
prov-db-binary-basedir  = "/usr/bin"
haproxy-binary-path     = "/usr/sbin/haproxy"
proxysql-binary-path    = "/usr/bin/proxysql"
```

---

## Next Steps

After installing and configuring the orchestrator, proceed to [Provisioning Configuration](/configuration/provisioning) to define service templates and bootstrap your first managed cluster.
