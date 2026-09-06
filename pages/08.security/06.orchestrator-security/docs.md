---
title: Orchestrator Security
taxonomy:
    category: docs
---

## 8.7.1 Orchestrator security: OpenSVC vs Kubernetes

replication-manager provisions databases through a pluggable orchestrator
(OpenSVC, Kubernetes, SlapOS, on-premise). This page compares the two container
orchestrators — **OpenSVC** and **Kubernetes** — on the three security concerns
that matter most for a multi-tenant database service: **tenant isolation**,
**resource monitoring without weakening that isolation**, and **secrets**.

The short version: OpenSVC's "a service is a cgroup slice" model and its
first-class, encrypted secret objects give a smaller, simpler security surface
for this specific job. Kubernetes reaches the same outcomes, but usually with
more moving parts (admission policy, shared PID namespaces, external secret
managers).

## 8.7.2 Tenant isolation

In both models a **tenant maps to a namespace**, and the hard boundary that
must never be crossed is **inter-namespace** (inter-tenant).

| | OpenSVC | Kubernetes |
|---|---|---|
| Tenant boundary | namespace | namespace |
| Workload unit | **service** (all its containers under one cgroup slice) | **pod** (containers share network, not cgroup) |
| Inter-tenant isolation | namespace-scoped objects and secrets | namespace + RBAC + (optional) NetworkPolicy/PodSecurity |

Both keep tenants isolated at the namespace boundary. The difference shows up in
*how much extra machinery* you need to keep that boundary intact while still
operating and observing the databases.

## 8.7.3 Resource monitoring without weakening isolation

replication-manager measures each database's **actually consumed** system
resources (memory, CPU, IO, disk) at the cgroup level, to bill and size in DBU.
Reading a cgroup safely, without opening a hole in tenant isolation, is where the
models differ most.

**OpenSVC — a read-only bind of the service's own cgroup slice.** OpenSVC groups
all of a service's containers under one slice, so replication-manager binds
*that slice, read-only* into the jobs container. It reads the whole service's
consumption directly. This is **least privilege by construction**:

- it exposes **only this service's own slice** — never a co-tenant's, never the
  node's cgroup tree;
- it needs **no shared process namespace** and **no node filesystem access**.

**Kubernetes — pod-scoped, still no node access.** The monitoring sidecar is a
*separate container* from the database, so it cannot see the database's cgroup by
default. replication-manager closes that gap **without any hostPath / node
access**, two ways:

**shared PID namespace** (`shareProcessNamespace`) so the sidecar reads the
database container's *own* cgroup that Kubernetes already mounts. This is
**pod-scoped**: it never crosses the pod boundary, so it does **not** affect
inter-pod or inter-tenant isolation. It is enabled only after a server-side
**dry-run** confirms the cluster's admission (PodSecurity/webhooks) accepts it —
so it can never break a deployment. It never mounts the node filesystem
(`hostPath`) or shares the node's process namespace (`hostPID`).

If a namespace policy refuses `shareProcessNamespace`, the sensor stays off on
that cluster and replication-manager raises an alert (**WARN0212**) so the
operator can allow it in the namespace policy. The secure mechanism is the only
one used — there is no privileged fallback. The whole feature has a single
off-switch (`monitoring-system-resources`).

**Takeaway:** OpenSVC reads consumption with one least-privilege bind confined to
the tenant's own service; Kubernetes reaches the same data with a pod-scoped
mechanism that is admission-gated so it never weakens isolation.

## 8.7.4 Secrets

Databases need credentials (root password, replication user, API tokens). How
those are stored and delivered is a core security property.

**OpenSVC — centralized, encrypted secret objects.** Secrets are first-class
cluster objects (`om <namespace>/sec/<name>`), **encrypted at rest** with the
cluster key and **scoped and delegated per namespace**. replication-manager
delivers a database's credentials from the namespace's own secret store, so a
secret is centrally managed, encrypted, and never leaves its tenant's namespace.

**Kubernetes — Secret objects, base64 by default.** A Kubernetes `Secret` is
**base64-encoded, not encrypted**, in etcd unless the cluster operator has
explicitly enabled **encryption at rest** (an `EncryptionConfiguration` on the
API server). They are namespace-scoped and RBAC-controlled, and in practice
teams often add an external manager (Vault, External Secrets Operator,
sealed-secrets) to get real encryption and central rotation.

| | OpenSVC | Kubernetes |
|---|---|---|
| Secret storage | encrypted at rest (cluster key) | base64 in etcd; encryption at rest is opt-in |
| Scope | namespace-delegated | namespace + RBAC |
| Central management | native (`sec` objects) | usually via an external manager |
| Extra tooling for encryption | none | commonly required |

> replication-manager never writes an unencrypted secret to a tracked store
> (e.g. GitLab config sync): secrets are encrypted with the local key before they
> leave the instance, and redacted from logs — independently of the orchestrator.

## 8.7.5 Summary

| Concern | OpenSVC | Kubernetes |
|---|---|---|
| Tenant boundary | namespace | namespace (+ RBAC/PodSecurity/NetworkPolicy) |
| Resource read | least-privilege slice bind (own service only) | pod-scoped shared PID ns (dry-run gated; alert if refused) |
| Node filesystem access | none | none (no hostPath) |
| Node process namespace | none | none (no hostPID) |
| Secrets at rest | encrypted (cluster key) | base64 unless encryption-at-rest enabled |
| Central secret management | native | usually external tooling |

Both orchestrators can run a secure multi-tenant database service. OpenSVC gets
there with a smaller surface — a slice bind and native encrypted secrets — while
Kubernetes achieves the same result with additional, carefully-gated mechanisms.
