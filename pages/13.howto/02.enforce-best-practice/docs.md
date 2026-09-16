---
title: Enforce Best Practices
taxonomy:
    category: docs
---

### 13.2.0.1 Enforcing best practices

**replication-manager** (1.1) can dynamically enforce best database practices around the replication usage.

It can dynamically configure the server it monitors via SET GLOBAL VARIABLES when it is possible to do so without database restart.

> Enforcement can be lost if replication-manager monitoring is shutdown and the database is restarted.

**replication-manager** (2.0) produces warnings if one of the possible practices is not found in the database nodes and not dynamically enforced. Some non-tunable warnings are produced for:

* missing log-slave-update directive
* non-strict GTID mode  

**replication-manager** ignores enforcement and warnings on the nodes excluded from election using the ignore list.   


* force-slave-heartbeat
* force-slave-gtid-mode
* force-slave-semisync
* force-slave-readonly
* force-binlog-row
* force-binlog-annotate
* force-binlog-slowqueries
* force-inmemory-binlog-cache-size
* force-disk-relaylog-size-limit
* force-sync-binlog
* force-sync-innodb
* force-binlog-checksum
* force-slave-parallel-mode (see below)


**replication-manager** default enforcements are `force-slave-readonly` and `force-slave-heartbeat`

We advise to permanently set the variables inside your database node configuration, to disable most dynamic enforcements on the long run.

### 13.2.0.2 Parallel replication mode

MariaDB applies replicated transactions in parallel according to `slave_parallel_mode`.
The Software Configurator templates ship `conservative`: a replica only runs in parallel the
transactions the primary committed in the same binlog group. That is safe, but it only pays
off when the primary actually groups commits. The **Replication parallelism** chart of the
Graphs page shows it: the *binlog group commit size* line is the concurrency the primary
offers, the *parallel workers* line what the replicas are configured with. On a primary
where transactions commit one at a time the group size stays at 1 and the workers are idle
whatever their number.

`optimistic` lifts that limit: the replica applies transactions in parallel speculatively
and retries the ones that conflict, which is where the replicas of an OLTP workload catch
up.

**Manual enforcement.** `force-slave-parallel-mode` sets the mode on every replica and
keeps it there. Values: `serialized`, `minimal`, `conservative`, `optimistic`, `aggressive`.
In the dashboard: **Settings → Replication Config**.

**Enforcement under dynamic configuration.** Since 3.1.42, when the cluster runs with
`prov-db-apply-dynamic-config = true` (**Settings → Dynamic Config**), replication-manager
owns the replica's runtime configuration and enforces `optimistic` on every MariaDB replica
as long as `force-slave-parallel-mode` is left empty. Set `force-slave-parallel-mode` to
another value to keep that value instead. With dynamic configuration off nothing is enforced
unless you set `force-slave-parallel-mode` yourself.

| Setting | Since | Default | Meaning |
|---|---|---|---|
| `force-slave-parallel-mode` | 2.0 | (empty) | Mode enforced on the replicas; wins over the dynamic-configuration default |
| `prov-db-apply-dynamic-config` | 2.1 | false | replication-manager applies configuration changes to the running databases; with it on and no forced mode, replicas run `optimistic` |
| `prov-db-replication-parallel-threads` | 3.1.42 | 32 | `slave_parallel_threads` deployed by the configurator (the workers; not tied to the core count) |
| `prov-db-replication-domain-parallel-threads` | 3.1.42 | 0 | `slave_domain_parallel_threads` (0 = no per-domain cap) |

**What happens on the replica.** Changing the mode needs the SQL thread stopped: the
enforcement does `STOP SLAVE`, `SET GLOBAL slave_parallel_mode`, `START SLAVE`, once. If the
change fails the cluster shows **WARN0216** with the database error and the enforcement is
retried after five minutes, never every monitoring tick. Galera clusters are not touched.

> The mode is a runtime setting: if the database restarts while replication-manager is
> down, the value from the configuration file comes back until enforcement runs again. On
> a cluster provisioned by replication-manager the Software Configurator writes the file, so
> set the mode there as well to make it permanent.
