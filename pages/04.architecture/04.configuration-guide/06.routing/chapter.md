---
title: Routing
taxonomy:
    category: docs
---

# Configuration Routing Traffic

replication-manager can manage database traffic through HAProxy, ProxySQL, or
MaxScale. It can also expose health endpoints for an externally managed proxy.
The proxy is not a replacement for database monitoring: replication-manager
continues to determine the valid writer and readers, while the selected proxy
applies that decision to client traffic.

## Proxy Selection

| Proxy | Routing model | Best suited for |
|---|---|---|
| **HAProxy** | Layer 4 TCP routing with explicit writer and reader backends | Simple, fast read/write routing |
| **ProxySQL** | MySQL-aware hostgroups, query rules, and connection pooling | Query-aware routing and connection pooling |
| **MaxScale** | MariaDB-aware services, monitors, and read/write routing | MaxScale-native routing and binlog services |
| **External proxy** | Proxy owns its configuration and calls replication-manager health endpoints | Existing proxy deployments managed outside replication-manager |

See the detailed guides for [HAProxy](01.haproxy), [ProxySQL](01.proxysql),
and [MaxScale](02.maxscale).

## Recent Proxy Changes

The current 3.1.x proxy updates include the following user-visible behavior:

- HAProxy Runtime API mode can reconcile read and write backend membership
  without a proxy reload when `haproxy-api-bootstrap-servers` is enabled.
- HAProxy supports separate `runtimeapi`, `standby`, `externalcheck`, and
  `dataplaneapi` modes. The selected mode determines which component owns
  backend state.
- HAProxy external checks use `/reader-status` for newly provisioned proxies.
  Existing proxies continue using their deployed check script until they are
  reprovisioned.
- Kubernetes native proxy provisioning supports HAProxy and ProxySQL. It
  creates a Deployment, Service, and persistent storage for each proxy.
- MaxScale can use its REST API and can generate either legacy or pinloki
  configuration syntax.
- MaxScale read-on-writer policy changes can be pushed to supported REST API
  services without waiting for the next monitoring refresh.

## Proxy Version Changelog

The canonical release entries are also maintained in the
[Proxy and Routing Updates changelog](../../../14.change-logs/08.features31).

| Version | Proxy changes |
|---|---|
| `3.1.41` | Added `haproxy-api-bootstrap-servers` for HAProxy Runtime API dynamic server lifecycle: runtime add, drain, address correction, and stale-server removal support (PR #1731) |
| `3.1.42` | Updated `haproxy-mode` and `/reader-status` external checks (PR #1747); added Kubernetes `prov-kube-proxy-storage-class` and native HAProxy/ProxySQL lifecycle provisioning (PR #1767); added MaxScale `maxscale-mode`, `maxscale-rest-api`, and `maxscale-rest-port` plus GUI settings (PR #1768). Existing ProxySQL bootstrap controls are documented separately. |

See the [ProxySQL bootstrap control reference](01.proxysql#bootstrap-control-reference)
for the existing configuration keys and their dashboard/API setting names.

##### `proxy-servers-read-on-master` (2.0)

| Item | Value |
| ---- | ----- |
| Description |  Read Only route on managed proxies include master |
| Type | bool |
| Default Value | false |   
