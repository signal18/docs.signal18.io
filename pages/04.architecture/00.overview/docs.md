---
title: Overview
taxonomy:
    category: docs
---

## High Availability

replication-manager provides continuous, automated high availability for MariaDB and MySQL clusters. It monitors replication topology on every tick, enforces a target topology, and takes corrective action — switchover or failover — only when it is safe to do so. Every decision passes through the same monitoring loop that detected the problem, so no action is ever taken on incomplete or ambiguous information.

---

## Topology Discovery

On every monitoring loop replication-manager connects to all configured servers and reconstructs the full replication graph: which server is the current primary, which are replicas, what GTID positions they hold, whether replication threads are running, and how far each replica lags behind.

The **default target topology is primary/replica** (also called master/slave). Other topologies — multi-primary, multi-tier replicas, binlog server, Galera, and more — can be declared as the target, and replication-manager will discover and validate the current state against that target on every loop.

**replication-manager will not perform any automated action until the observed topology matches the declared target topology.** This guarantee means that a partially-converged cluster, a cluster mid-rejoin, or a cluster where replica lag has not yet resolved will be left alone until the situation is fully understood. The same loop that detects a problem also validates that the cluster is in a known, stable state before acting.

---

## False Positive Protection

Alongside topology discovery, every monitoring loop runs a series of false-positive checks before concluding that a primary is truly unreachable:

- Can the replication-manager host reach the primary directly?
- Can the replicas still reach the primary?
- Does an external arbitrator agree that the primary is down?
- Has the primary been unreachable for longer than the configured grace period?

Only when all applicable checks agree that the primary is genuinely lost will replication-manager consider an automated failover. This prevents split-brain and unnecessary failovers caused by transient network events, monitoring host isolation, or slow primaries under load.

---

## Switchover — Planned Handoff

A **switchover** is a controlled primary promotion performed while the current primary is still reachable and healthy. It is the correct operation for planned maintenance, host migrations, software upgrades, and load rebalancing.

During a switchover replication-manager:

1. Makes the primary read-only and flushes all pending transactions
2. Waits for the best replica to catch up to the primary's GTID position
3. Promotes that replica to primary
4. Redirects all other replicas to replicate from the new primary
5. Optionally updates proxy routing (HAProxy, ProxySQL, MaxScale) to point to the new primary

Because the old primary is still alive, there is no data loss and the operation is fully reversible — another switchover brings the old primary back as the new leader.

---

## Failover — Automatic Recovery

A **failover** is an emergency promotion triggered when the primary can no longer be reached and all false-positive checks have been exhausted. It is the operation that keeps the cluster writable when a primary host has crashed, lost power, or become permanently partitioned.

During a failover replication-manager:

1. Confirms primary loss through all configured arbitration checks
2. Elects the most advanced replica (highest GTID, lowest lag, best replication health score)
3. Promotes that replica to primary
4. Redirects surviving replicas to the new primary
5. Updates proxies

Because the old primary was not available to flush transactions, a small amount of data loss (transactions committed but not yet replicated) is possible depending on the replication mode configured (`semi-sync`, `binlog-commit-wait`, etc.). The number of failovers can be capped to prevent cascading failures.

---

## Rejoin — Reintroducing an Old Primary

After a failover the old primary eventually comes back online. **Rejoin** is the process of safely reintroducing that server as a replica of the new primary.

Rejoin must handle the case where the old primary may have accepted writes that never replicated — those transactions must be rolled back or skipped before the server can safely join. replication-manager automates this using:

- **GTID-based flashback** — roll back diverging transactions on the old primary
- **Resync from backup** — restore the old primary from a recent backup and replay binlogs
- **Manual gate** — hold the server in a maintenance state until an operator approves the rejoin

The rejoin method is configurable per cluster based on acceptable data risk and recovery time objectives.

---

## Reseeding — Adding a New Server

**Reseeding** is the process of provisioning a brand-new server and bringing it into the cluster as a replica. Unlike rejoin (which handles a server that was previously a member), reseeding starts from scratch:

1. A physical or logical backup is taken from the primary (or a designated donor replica)
2. The backup is restored on the new server
3. Replication is configured from the backup's GTID position
4. The server joins the cluster and begins catching up

replication-manager automates reseeding using the configured backup tools (mydumper, mariabackup, xtrabackup, or restic snapshots) and can target any server in the cluster as the donor to avoid load on the primary.

---

## Summary

| Operation | Trigger | Primary state | Data loss risk |
|---|---|---|---|
| **Switchover** | Planned / manual | Alive and reachable | None |
| **Failover** | Automatic / manual | Unreachable | Possible (depends on sync mode) |
| **Rejoin** | After failover | Returning online | Handled by rollback or restore |
| **Reseeding** | Adding new node | Running normally | None (backup-based) |
