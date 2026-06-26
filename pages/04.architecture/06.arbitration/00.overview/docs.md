---
title: Overview
taxonomy:
    category: docs
---

### 4.7.1 Overview

Arbitration provides high availability for the monitoring layer by running two replication-manager instances across separate datacenters. An external arbitrator service ensures that exactly one instance is active at any time, preventing conflicting operations on your database clusters during network partitions. Arbitration requires a registered instance with a support or partner subscription plan.

Under the free plan, you typically deploy a single replication-manager instance in a third datacenter that acts as the arbitrator of your databases and proxies availability. Because such an instance does not hold critical data, it is easy to relocate in case of failure — you may lose monitored data and statistics, but you can restore configuration from personal backups or from the Signal18 GitLab for registered instances.

Both instances in the pair must share the same registration URI and the same encryption key. On the second instance, copy the following from the first:

- The encryption key file (path configured via `monitoring-key-path`, default `.replication-manager.key`)
- `cloud18-domain`
- `cloud18-sub-domain`
- `cloud18-sub-domain-zone`
- `cloud18-gitlab-user`
- `cloud18-gitlab-password`

When arbitration is enabled, one instance is elected **active** and the other enters **standby** mode. The active instance is the sole authority for all cluster operations. The standby instance monitors the same database clusters but does not modify them.

---

### 4.7.1.1 Architecture

When deploying across two datacenters, you can run two replication-manager instances in an active/standby pair. Both instances exchange heartbeats over HTTP via the replication-manager API, with an external arbitrator service resolving split-brain situations.

- **Peer heartbeat** — each replication-manager instance polls the other's `/api/heartbeat` endpoint on the standard API port. This traffic stays on the local network or VPN. No authentication is required (the endpoint is unprotected).
- **Arbitrator** — a lightweight external service (`replication-manager-arb`) that both instances contact when a split brain is detected. The arbitrator decides which instance becomes active based on heartbeat reports. The arbitrator can be a shared public service behind a TLS reverse proxy.

Make sure the web server of **replication-manager** is active on both instances — the peer heartbeat uses the API port.

---

### 4.7.1.2 What is protected on standby

The standby instance suppresses all operations that could conflict with the active instance or cause unintended changes to the monitored databases.

##### Originally safeguarded (all versions with arbitration)

- **Automatic failover** — a standby instance never triggers automatic failover
- **Slave rejoin** — a standby instance never initiates crash recovery or GTID-based rejoin of failed servers
- **Alert suppression** — all alerts (email, Slack, webhook, scripts) are suppressed on standby to prevent duplicate notifications

##### Added in 3.1.30

- **Maintenance operations** — all scheduled database maintenance is suppressed on standby:
  - Logical and physical backups
  - Log rotation and log backups
  - Rolling optimize, rolling restart, rolling reprovisioning
  - Database analyze jobs
  - SSH-based database jobs
  - Table checksum monitoring
- **Configuration push** — the standby instance stops pushing configuration to the git repository and instead pulls configuration changes published by the active instance, reloading them automatically

##### Operations that remain active on standby

The following operations remain active on standby because they are read-only or maintain local state only:

- SLA metric rotation (internal counters)
- Schema monitoring (read-only metadata collection)
- Alert schedule disable/enable (local toggle)

---

### 4.7.1.3 Standby config sync (3.1.30)

When arbitration is enabled, the active instance pushes cluster configuration to the git repository (`git-url`). Standby instances pull from the same repository and reload configuration changes automatically.

This ensures that configuration changes made on the active instance (enabling a backup schedule, changing a monitoring threshold, etc.) are propagated to the standby without manual intervention. If the standby is later promoted to active, it operates with the latest configuration.

The sync uses the existing `git-url` repository — no additional git configuration is required beyond what is already set up for configuration persistence.

---

### 4.7.1.4 Startup behavior

When the first instance starts, the peer is not yet reachable. This triggers split-brain detection and the instance contacts the arbitrator:

```
INFO Arbitrator: External check requested
INFO Arbitrator say winner
```

The arbitrator grants active status to the first instance.

When the second instance starts, it detects the peer is already active and enters standby mode:

```
INFO No peer split brain setting status to S
```

If the active instance goes down, the standby detects the peer is unreachable (split brain), contacts the arbitrator, and is elected as the new active instance.

>Failover in a **replication-manager** cluster requires arbitration. If the arbitrator cannot be contacted, you can use the command line or API to failover manually — but make sure all other replication-manager instances are stopped first.

---

### 4.7.1.5 Arbitrator availability and subscription requirements

Starting from the release following 3.1.30, the arbitrator binary (`replication-manager-arb`) will be included in all release editions, not only the Pro edition. This allows any deployment to set up active/standby pairs with external arbitration.

However, **automatic split-brain resolution requires a registered instance with a support or partner subscription plan**. Instances that are not registered to Cloud18 via a GitLab user, or that are on the free plan, will:

- Still participate in heartbeat exchange and initial active/standby election
- Display an `ERR00104` error state when split-brain is detected
- Not receive automatic arbitrator election to resolve the split-brain

This means the active/standby mechanism works for everyone, but automatic recovery from a network partition between the two instances is a supported-plan feature. Manual recovery via the API (`ForceArbitratorElection`) or the dashboard toggle remains available regardless of plan.
