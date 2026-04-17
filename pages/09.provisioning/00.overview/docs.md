---
title: Overview
taxonomy:
    category: docs
---

## Provisioning

replication-manager-pro can provision and manage the complete lifecycle of database and proxy infrastructure — creating service definitions, deploying containerised or bare-metal instances, bootstrapping replication topology, and tearing everything down — across five orchestration backends.

> **Requires:** `replication-manager-pro` flavor. The `osc` release supports `onpremise` and `local` orchestrators only.

---

## Orchestration Backends

| Orchestrator | `prov-orchestrator` | Description |
|---|---|---|
| **OpenSVC** | `opensvc` | Service cluster with Docker/Podman micro-services across a pool of agents. Primary supported backend. |
| **Kubernetes** | `kube` | Native K8s deployment using a kubeconfig file. |
| **SlapOS** | `slapos` | Distributed service mesh from Nexedi. |
| **On-Premise SSH** | `onpremise` | SSH-based bootstrap onto existing bare-metal or VM hosts. No agent required. |
| **Local** | `local` | Processes started directly on the replication-manager host. For testing only. |

---

## What Can Be Provisioned

### Database Services

- **Containers**: MariaDB, MySQL, Percona — any Docker/Podman image
- **Resources**: CPU cores, memory, shared memory, tmpfs
- **Disk**: size, filesystem (ext4, ZFS, Btrfs), pool, compression, device type
- **Snapshots**: daily snapshots on preferred primary, configurable retention
- **Network**: interface, netmask, gateway, CNI virtual networks
- **Bootstrap**: replication topology, initial data load (SQL/CSV), benchmark table setup
- **Config**: dynamic config application, binary log settings, max connections, expire-logs-days

### Proxy Services

All proxy types share resource (memory, CPU, disk) and network configuration:

| Proxy | Notes |
|---|---|
| **HAProxy** | TCP load balancer, read/write split |
| **ProxySQL** | Query routing, connection pooling |
| **MaxScale** | MariaDB-native proxy with binlog routing |
| **ShardProxy** | Spider-based sharding proxy |
| **MySQL Router** | Lightweight routing for InnoDB Cluster |
| **Sphinx** | Full-text search engine as a service |

### Service Plans

Pre-defined plans set the number of database nodes and resource tiers in a single setting (`prov-service-plan`). Plans are registered in a central registry and can be switched dynamically, triggering automatic cluster resize.

---

## Bootstrap Workflow

When you provision a cluster, replication-manager executes in order:

1. Provision all database service containers/processes
2. Provision all proxy service containers/processes
3. Bootstrap the replication topology
4. Create the sponsor/monitoring user if configured
5. Wait for proxies to synchronise with the new topology
6. Optionally initialise a benchmark (Sysbench) table

Serialised provisioning mode (`prov-db-service-plan-batch`) controls whether services are started one-by-one (dependency ordering) or in parallel.

---

## Configuration Guide

See the sub-sections for detailed configuration of the [Software Configurator](configurator), [Orchestrators](orchestrators), and [Service Plans](serviceplan).
