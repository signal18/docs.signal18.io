---
title: Dynamic Resources
taxonomy:
    category: Provisioning
---

Available from **replication-manager 3.1.42**. Every setting below is new in 3.1.42 unless
another version is given.

## Units: DBU for stateful, APU for stateless

**replication-manager** measures, plans and bills resources in **units**, not in raw cores
and gigabytes. There are two kinds of unit, one per kind of workload:

| Unit | Workload | 1 unit = | IOPS |
|---|---|---|---|
| **DBU**, Database Unit | **stateful**: the database servers | 1 core, 4 GB memory, 40 GB disk | 1000 IOPS, locked in the unit |
| **APU**, Application Unit | **stateless**: proxies (ProxySQL, MaxScale, HAProxy) and applications | 1 core, 1 GB memory, 10 GB disk | none: a stateless service has no IO to reserve |

A unit is a **bundle with a fixed ratio**. A database that needs 2 cores, 8 GB and 80 GB is
2 DBU; one that needs 2 cores and 2 GB is still 2 DBU, because the unit follows the axis
that binds (here CPU) and the others come with it. The ratios are the same everywhere; only
the price per unit differs from one provider to another.

Why two units: a database holds data, so memory (the buffer pool), disk and IOPS are what it
lives on, and one core needs about 4 GB behind it to be useful. A proxy or an application
holds no data: it needs cores and a little memory, disk only for its binary and logs, and no
IOPS reservation at all. Pricing them with the same bundle would make a proxy pay for 40 GB
and 1000 IOPS it never uses.

- The **plan** is expressed in units: `prov-db-dbu` per database, `prov-proxy-apu` per proxy
  or application. The cluster total, `prov-service-plan-dbu` and `prov-service-plan-apu`, is
  the sum over its members and is what you see as the plan line on the graphs.
- What a service **consumes** is measured on its cgroup and converted to the same unit on
  each axis, so consumption, configuration and plan compare directly.
- Dynamic resources, below, move databases by whole **DBU** on the axis that binds.

## What it does

With dynamic resources enabled, **replication-manager** resizes a database's CPU and memory
**while it runs**, following the load it measures on the database's own cgroup:

- when a server saturates a resource, the resource **grows** by one unit;
- when every server of the cluster under-uses a resource, it **shrinks** back to what the
  load needs;
- the **plan** (what you pay for) is never changed by the resize. Growing past the plan is
  allowed within a commercial envelope and billed as overage; the plan itself is only ever
  raised by you.

Resources are counted in **DBU** (Database Units): 1 DBU = 1 core, 4 GB of memory,
40 GB of disk, 1000 IOPS. A resize moves by whole DBU on the axis that is binding.

## Requirements

- An orchestrator with a live resize primitive: **OpenSVC v3** (the service process group)
  or **Kubernetes 1.33+** (in-place Pod resize). On-premise, localhost and SlapOS can only
  resize through your own script, or at the next restart.
- The resource sensor on (`monitoring-system-resources`, default on): it reads the
  service cgroup and produces the consumed DBU the decisions are based on.
- On OpenSVC, the cap must live on the process group, not on the docker run arguments:
  set `prov-db-docker-run-args-limit = false` and rolling-restart once so the containers
  are recreated resize-ready. Until then WARN0214 tells you the live move cannot bind.

## Turning it on

```toml
[mycluster]
prov-db-dynamic-resource = true
prov-db-docker-run-args-limit = false   # OpenSVC: cap on the process group
prov-db-dbu = 1                         # the plan, per database
```

The same settings are in the dashboard under **Settings → Dynamic Config**.

## How a grow happens

Every monitoring tick, each server's consumption is compared with its configured resources.

1. A server is **saturated** on an axis when it uses at least `100 - prov-db-cap-safety-pct`
   percent of it (default 85 %), for `prov-db-scale-up-config-in-plan-speed` (default 1m).
2. One saturated server is enough: the cluster grows that axis by **one DBU on every
   database** (CPU first when cores are pinned, then memory, then IOPS when a memory step
   stopped improving throughput).
3. Within the plan the step is always free. Past the plan it must fit:
   - the **overcommit envelope** `prov-db-overcommit-pct` (default 50): never above
     ceil(plan × 1.5) DBU per database;
   - the free pool of the node;
   - your own veto script `prov-db-resource-raised-over-plan-script`, if set.
4. If the step is refused, nothing changes and the cluster carries the error **ERR00112**
   in the Workload panel with the reason, until a later step succeeds or the load drops.
   To go further, raise the plan or the overcommit percentage.

One step per window: the next grow is evaluated after `prov-db-scale-up-config-in-plan-speed`.

## How a shrink happens

1. An axis is **under-used** when a server uses at most `prov-db-cap-shrink-pct` percent of
   it (default 50 %), for `prov-db-scale-down-config-in-plan-speed` (default 5m).
2. **Every** database of the cluster must be under-used: one busy server blocks the shrink.
3. The axis is then set, **in one move**, to the smallest whole DBU that keeps every
   server's peak consumption under the saturation mark. A 4-core database using 0.2 core
   goes straight to 1 core; a 2-core database with a 1.0-core peak stays at 2, because
   1 core would be saturated at once.
4. The move never goes under the **undercommit floor** `prov-db-undercommit-pct`
   (default 50): floor(plan × 0.5) DBU per database, and never under 1 DBU.

Memory is shrunk before CPU. A memory shrink lowers the InnoDB buffer pool first and the
container limit only once the pool has actually released the memory, so the database is
never squeezed below what it holds.

Between 50 % and 85 % of the configured resources nothing moves: this dead band avoids
oscillation.

## What you see

- **Graphs → Consumed DBU**: the bars are the real consumption per axis; the dashed
  **plan** line is your contract; a dotted **configured** line appears when the configured
  resources differ from the plan, marked "(over plan)" when they sit above it.
- **Workload panel**: CINF0007 (a server saturates its resources), CINF0008 (a server
  under-uses them), WARN0213 (consumption at the plan for a long time: consider raising the
  plan by hand), ERR00112 (an automatic grow was refused, with the reason).
- **WARN0214**: dynamic resources are on but the container is still capped by the docker
  run arguments, the live move cannot take effect.
- The resize history (dimension, direction, applied or not, statements run) is kept in the
  resource resize log of the cluster.

## Settings

| Setting | Since | Default | Meaning |
|---|---|---|---|
| `prov-db-dynamic-resource` | 3.1.42 | false | Enable the live resize |
| `prov-db-docker-run-args-limit` | 3.1 | true | Cap at the docker level; set false on OpenSVC so the process group can be resized live |
| `monitoring-system-resources` | 3.1.42 | true | The resource sensor (global setting) the decisions read |
| `prov-db-dbu` | 3.1.42 | | The plan, in DBU per database |
| `prov-db-cap-safety-pct` | 3.1.42 | 15 | Saturated at 85 % of the configured resource |
| `prov-db-cap-shrink-pct` | 3.1.42 | 50 | Under-used at 50 % of the configured resource |
| `prov-db-overcommit-pct` | 3.1.42 | 50 | Grow ceiling: up to ceil(plan × 1.5) DBU per database |
| `prov-db-undercommit-pct` | 3.1.42 | 50 | Shrink floor: down to floor(plan × 0.5) DBU per database, min 1 |
| `prov-db-scale-up-config-in-plan-speed` | 3.1.42 | 1m | How long saturation must last before a grow |
| `prov-db-scale-down-config-in-plan-speed` | 3.1.42 | 5m | How long under-use must last before a shrink |
| `prov-db-scale-up-plan-speed` | 3.1.42 | 30m | How long consumption must sit at the plan before WARN0213 |
| `prov-db-dynamic-resize-policy` | 3.1.42 | scale-speed | `daily-time` defers the memory move to `prov-db-dynamic-resize-daily-time` (03:00) |
| `prov-db-dynamic-resource-can-change-script` | 3.1.42 | | Your feasibility check before a move (prints yes, no or migration) |
| `prov-db-dynamic-resource-change-script` | 3.1.42 | | Your own resize hook, replaces the native backend |
| `prov-db-resource-raised-over-plan-script` | 3.1.42 | | Your veto on a grow past the plan (non-zero exit refuses) |

## Limits and known behaviour

- Kubernetes cannot **decrease** memory in place with the default resize policy: a memory
  shrink is applied at the next restart. CPU moves both ways.
- IOPS are tuned in the database only; there is no live IO cap on the container.
- On OpenSVC, a cluster whose databases do not all run on the node repman talks to may see
  the new limit applied on the next reconciliation on the other nodes rather than
  immediately (issue #1795). The configuration is right at once; the running limit follows.
- A resource declared in the static cluster file is immutable: a dynamic move on it is kept
  only until the next restart. Leave `prov-db-cpu-cores` and `prov-db-memory` out of the
  static file when you enable dynamic resources.
