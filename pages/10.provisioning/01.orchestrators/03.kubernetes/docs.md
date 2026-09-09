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

### Storage and Configuration Lifecycle

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
