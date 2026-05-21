---
title: Distributions & Rolling Upgrade
taxonomy:
    category: docs
---

## 10.3.5.1 Distribution Model

The configurator uses a **two-axis model** to determine how database software is installed and upgraded. Both axes are resolved from the cluster's active DB tags:

| Axis | What it controls | Tags |
|---|---|---|
| **OS family** | Package repository type, URLs, GPG keys | `rpm` → yum/dnf, default → apt |
| **Deploy method** | How software is delivered to the host | `docker` → container image, `package` → binary tarball, default → OS repository |

The axes are **orthogonal** — a cluster can combine any OS family with any deploy method:

| Tags | OS family | Deploy method | Example |
|---|---|---|---|
| *(none)* | apt (Debian/Ubuntu) | repository (`apt-get install`) | On-premise Debian server |
| `rpm` | yum (RHEL/Rocky) | repository (`yum install`) | On-premise RHEL server |
| `docker` | apt (Debian/Ubuntu) | docker (container image pull) | OpenSVC with Debian-based image |
| `docker,rpm` | yum (RHEL/Rocky) | docker (container image pull) | OpenSVC with RHEL-based image |
| `package` | apt (Debian/Ubuntu) | tarball (binary package extract) | On-premise with local binaries |
| `package,rpm` | yum (RHEL/Rocky) | tarball (binary package extract) | On-premise with local binaries on RHEL |

### 10.3.5.1.1 Orchestrator-Aware Defaults

For container orchestrators (OpenSVC, Kubernetes), the deploy method defaults to **docker** even without an explicit `docker` tag — because these orchestrators always deploy via container images. For on-premise, the default is **repository** (system packages via `apt-get` or `yum`).

| Orchestrator | No deploy tag | With `docker` tag | With `package` tag |
|---|---|---|---|
| **On-premise** | repository | docker | tarball |
| **OpenSVC / K8S** | docker | docker | tarball |

---

## 10.3.5.2 The `db_distributions.json` File

Distribution metadata is stored in `db_distributions.json` — a JSON file that maps tags to repository URLs, GPG keys, and supported OS versions. It follows the same pattern as the compliance module: an embedded default is compiled into the binary, and the back office can push an override to `PluginDataDir`.

### 10.3.5.2.1 File Location

| Priority | Location |
|---|---|
| 1 (highest) | `{share-dir}/plugins/data/db_distributions.json` (BO push via git pull) |
| 2 (lowest) | Embedded in the binary (`share/plugins/data/db_distributions.json`) |

### 10.3.5.2.2 Schema

```json
{
  "version": "1",
  "generated_at": "2026-05-19T00:00:00Z",
  "os_families": [
    {
      "filter": "rpm",
      "repo_type": "yum",
      "repo_base_url": "https://mirror.mariadb.org/yum",
      "repo_key_url": "https://mariadb.org/mariadb_release_signing_key.asc",
      "os_versions": ["8", "9"],
      "url_pattern": "{repo_base_url}/{major_minor}/rhel/{os_version}/x86_64"
    },
    {
      "filter": "",
      "repo_type": "apt",
      "repo_base_url": "https://mirror.mariadb.org/repo",
      "repo_key_url": "https://mariadb.org/mariadb_release_signing_key.pgp",
      "codenames": ["bookworm", "trixie", "jammy", "noble"],
      "url_pattern": "{repo_base_url}/{major_minor}/{os_id}"
    }
  ],
  "deploy_methods": [
    {
      "filter": "docker",
      "type": "docker",
      "image": "mariadb"
    },
    {
      "filter": "package",
      "type": "tarball",
      "tarball_base_url": "https://downloads.mariadb.org/interstitial"
    },
    {
      "filter": "",
      "type": "repository"
    }
  ]
}
```

| Field | Description |
|---|---|
| `filter` | Tag name to match (`rpm`, `docker`, `package`). Empty string = default fallback. |
| `repo_type` | `apt` or `yum` — determines the package manager. |
| `repo_base_url` | Base URL for the MariaDB mirror. The upgrade script appends version and OS info. |
| `repo_key_url` | URL for the GPG signing key. |
| `url_pattern` | Template for building the full repo URL. Placeholders: `{repo_base_url}`, `{major_minor}`, `{os_id}`, `{os_version}`. |
| `codenames` | Supported Debian/Ubuntu release codenames (informational). |
| `os_versions` | Supported RHEL major versions (informational). |

### 10.3.5.2.3 Customizing via Back Office

To point clusters at a private mirror or add support for a new OS version, push a customized `db_distributions.json` to the PluginDataDir alongside the compliance module:

```
{share-dir}/plugins/data/
├── moduleset_mariadb.svc.mrm.db.json      ← compliance module
├── moduleset_mariadb.svc.mrm.proxy.json    ← proxy compliance module
└── db_distributions.json                    ← distribution metadata (optional override)
```

Changes take effect on the next configurator reload — no restart required.

---

## 10.3.5.3 Environment Variables for SSH Scripts

When replication-manager executes SSH scripts (start, bootstrap, upgrade, dbjobs), it exports environment variables via `GetSshEnv()`. All values are single-quote-escaped to prevent injection from passwords containing special characters.

### 10.3.5.3.1 Standard Variables (all scripts)

Exported by `GetSshEnv()` and available to start, upgrade, and dbjobs scripts:

| Variable | Source | Used by | Example |
|---|---|---|---|
| `REPLICATION_MANAGER_USER` | Admin API user | start, upgrade | `admin` |
| `REPLICATION_MANAGER_PASSWORD` | Admin API password | start, upgrade | `repman` |
| `REPLICATION_MANAGER_URL` | API base URL | start, upgrade, dbjobs | `https://repman:10005` |
| `REPLICATION_MANAGER_URL_HOST` | API host (without port) | dbjobs | `repman` |
| `REPLICATION_MANAGER_URL_PORT` | API port | dbjobs | `10005` |
| `REPLICATION_MANAGER_CLUSTER_NAME` | Cluster name | start, upgrade, dbjobs | `production` |
| `REPLICATION_MANAGER_HOST_NAME` | Server hostname | start, upgrade, dbjobs | `db1.example.com` |
| `REPLICATION_MANAGER_HOST_PORT` | Server port | start, upgrade, dbjobs | `3306` |
| `REPLICATION_MANAGER_HOST_USER` | DB root user | upgrade, dbjobs | `root` |
| `REPLICATION_MANAGER_HOST_PASSWORD` | DB root password | upgrade | *(password)* |
| `MYSQL_ROOT_PASSWORD` | Alias for HOST_PASSWORD | upgrade, dbjobs | *(password)* |
| `REPLICATION_MANAGER_JOBS_MODE` | Job dispatch mode | dbjobs | `sql` or `api` |

### 10.3.5.3.2 Distribution Variables (upgrade scripts)

Exported conditionally when the corresponding config or `db_distributions.json` data is available:

| Variable | Source | Used by | Example |
|---|---|---|---|
| `REPLICATION_MANAGER_DB_DOCKER_IMG` | `prov-db-docker-img` config | upgrade | `mariadb:11.8.6` |
| `REPLICATION_MANAGER_DB_REPO_BASE_URL` | OS family from `db_distributions.json` | upgrade | `https://mirror.mariadb.org/repo` |
| `REPLICATION_MANAGER_DB_REPO_KEY_URL` | OS family from `db_distributions.json` | upgrade | `https://mariadb.org/mariadb_release_signing_key.pgp` |
| `REPLICATION_MANAGER_DB_REPO_TYPE` | OS family from `db_distributions.json` | *(reserved)* | `apt` or `yum` |
| `REPLICATION_MANAGER_DB_DEPLOY_METHOD` | Deploy method from `db_distributions.json` | *(reserved)* | `docker`, `tarball`, or `repository` |

The **target MariaDB version** is parsed from `REPLICATION_MANAGER_DB_DOCKER_IMG` (e.g. `mariadb:11.8.6` → `11.8.6`). This is the same `prov-db-docker-img` setting used for Docker image selection, making it the single source of truth for the target version across all deployment methods. When the version has a patch component (e.g. `11.8.6`), the upgrade scripts pin the package to that exact version.

### 10.3.5.3.3 Caller-Provided Variables (not exported by GetSshEnv)

These variables are NOT exported automatically — they must be set by the caller or the operating environment:

| Variable | Purpose | Default |
|---|---|---|
| `REPLICATION_MANAGER_FORCE_CONFIG` | When `true`, overwrite user-edited `my.cnf` with Signal18-generated config | *(not set)* — preserves existing `my.cnf` |
| `REPLICATION_MANAGER_WGET_OPTS` | Override wget certificate options (e.g. `--ca-certificate=/path/to/ca.pem`) | `--no-check-certificate` (self-signed cert support) |

---

## 10.3.5.4 Rolling Upgrade

### 10.3.5.4.1 Overview

A rolling upgrade updates the MariaDB version across all servers in a cluster without downtime. The process differs by orchestrator:

| Orchestrator | Mechanism |
|---|---|
| **OpenSVC / K8S** | Change Docker image tag → rolling restart pulls new image. Two-phase: set `image_pull_policy=always`, restart, then clean the policy and restart again. |
| **On-premise (SSH)** | Run the upgrade script on each server. The script updates the package repository, installs the new version, starts the database, and runs `mariadb-upgrade`. |

### 10.3.5.4.2 Triggering a Rolling Upgrade

Via API:

```
POST /api/clusters/{clusterName}/actions/rolling/upgrade
```

Via GUI: Cluster dashboard → Actions → Rolling Upgrade

### 10.3.5.4.3 Rolling Upgrade Sequence

For each server (slaves first, then switchover, then old master):

1. **Maintenance on** — remove from proxy backends
2. **Wait for binlog backup** — if a backup is in progress, wait for completion
3. **Clean stop** — `SET GLOBAL innodb_fast_shutdown = 0` via SQL, then orchestrator stop (see [Shutdown Behavior](#9355-shutdown-behavior-during-upgrades))
4. **Wait failed** — confirm server is down
5. **Upgrade** — orchestrator-specific:
   - *OpenSVC*: update service config (`image_pull_policy=always`), start (pulls new image), wait sync, then clean config and restart
   - *On-premise*: run upgrade script via SSH (updates repo, installs packages, starts MariaDB, runs `mariadb-upgrade`)
6. **Wait sync** — wait for replication to catch up with master
7. **Maintenance off** — re-add to proxy backends
8. **Switchover** — promote a slave, then repeat for the demoted master

**Important**: The rolling upgrade never stops a running master. The master is always switchovered first (step 8), becoming a slave before it is stopped and upgraded. This ensures zero downtime for writes.

### 10.3.5.4.4 On-Premise Upgrade Scripts

Located at:

```
/static/configurator/onpremise/repository/debian/mariadb/upgrade
/static/configurator/onpremise/repository/redhat/mariadb/upgrade
```

Selected automatically based on the `rpm` tag (same logic as start scripts). A custom script can be specified via:

```toml
onpremise-ssh-upgrade-db-script = "/path/to/custom/upgrade.sh"
```

Each script performs these steps:

1. **Detect current version** — reads `mariadbd --version` or package manager
2. **Parse target version** — from `REPLICATION_MANAGER_DB_DOCKER_IMG` env var
3. **Compare versions** — skip if already at target
4. **Pre-flight checks** — ensure MariaDB is stopped, check disk space
5. **Update repository** — configure apt/yum repo for the target version using `REPLICATION_MANAGER_DB_REPO_BASE_URL` and `REPLICATION_MANAGER_DB_REPO_KEY_URL`
6. **Upgrade packages** — `apt-get install --only-upgrade` or `yum upgrade`
7. **Deploy config** — fetch `config.tar.gz` from replication-manager
8. **Start MariaDB** — `systemctl start mariadb`
9. **Run `mariadb-upgrade`** — upgrades system tables for the new version
10. **Update repman CLI** — downloads new `replication-manager-cli` if version changed

### 10.3.5.4.5 Shutdown Behavior During Upgrades

Upgrade stops use `StopDatabaseServiceClean`, which differs from a normal stop:

| Step | Slave | Master (direct upgrade) |
|---|---|---|
| 1. `SET GLOBAL innodb_fast_shutdown = 0` | Yes | Yes |
| 2. `SHUTDOWN WAIT FOR ALL SLAVES` (SQL) | No | Yes (MariaDB 10.4+) |
| 3. Orchestrator stop | Yes | Yes |

**`innodb_fast_shutdown = 0`** forces InnoDB to perform a full purge and change buffer merge before the process exits. This ensures the InnoDB data files are in a clean state compatible with the new MariaDB version — required when the redo log format changes across major versions.

**`SHUTDOWN WAIT FOR ALL SLAVES`** is only issued when upgrading a master directly (not via rolling upgrade — rolling upgrade always switchovers before stopping). It ensures all connected replicas have received and acknowledged all pending binlog events before the master shuts down. Without this, replicas could miss transactions if the master is upgraded and restarted before they catch up.

**Orchestrator stop** always follows the SQL commands. This is critical for container deployments:

- On **OpenSVC/K8S**: the orchestrator stop shuts down **all containers** in the service (both `container#db` and `container#jobs`). When the service is started again, both containers pull the new image. If only the database process is killed via SQL `SHUTDOWN`, `container#jobs` (which runs the dbjobs maintenance script) stays on the old MariaDB image. This causes the config diff to be computed against the old version's `mariadbd --print-defaults` output, producing incorrect results.
- On **on-premise**: the orchestrator stop is equivalent to `systemctl stop mariadb`, which sends SIGTERM to the MariaDB process. Since `innodb_fast_shutdown` was already set to 0, the process performs a full purge during the SIGTERM shutdown.

**OpenSVC instance state**: before every V3 start or restart, replication-manager clears the instance monitor state via the per-node instance API (`POST /api/node/name/{node}/instance/path/{ns}/{kind}/{name}/clear`). This prevents 409 "failover object is warn state" errors from a previous failed operation blocking the new start request.

### 10.3.5.4.6 Direct Server Upgrade vs Rolling Upgrade

There are two ways to upgrade a server:

| Method | Scope | Safety |
|---|---|---|
| **Rolling upgrade** (cluster action) | All servers, automated | Safe — maintenance mode, switchover, sync checks |
| **Direct upgrade** (server menu) | Single server | Manual — user must ensure replication is healthy |

**Rolling upgrade** (`POST /api/clusters/{name}/actions/rolling/upgrade`) follows the full sequence described in [Rolling Upgrade Sequence](#9343-rolling-upgrade-sequence). The master is never stopped directly — it is switchovered first.

**Direct upgrade** (from the server context menu) upgrades a single server. If the target is a master, replication-manager issues `SHUTDOWN WAIT FOR ALL SLAVES` before stopping to protect replica consistency. However, the user is responsible for:
- Ensuring replicas are healthy before upgrading the master
- Handling any replication lag after the master restarts
- Upgrading the remaining servers separately

For production environments, always prefer rolling upgrade over direct server upgrade.

### 10.3.5.4.7 Version Source

The target version for all deployment methods comes from `prov-db-docker-img`:

```toml
prov-db-docker-img = "mariadb:11.8"
```

- **Docker**: used directly as the container image tag
- **On-premise**: the version portion (`11.8`) is extracted and used to configure the package repository

This ensures a single configuration change triggers the upgrade across all deployment methods. The back office manages this setting as part of the cluster's provisioning configuration.

---

## 10.3.5.5 Configuration Keys

##### `onpremise-ssh-upgrade-db-script`

| | |
|---|---|
| Description | Custom script to run for on-premise database upgrades via SSH. When empty (default), the configurator selects the built-in script based on the `rpm` tag. |
| Type | String |
| Default | `""` (auto-select) |

##### `prov-db-docker-img`

| | |
|---|---|
| Description | Docker image for database containers. Also used as the version source for on-premise upgrades (the version portion is parsed from the tag, e.g. `mariadb:11.8` → `11.8`). |
| Type | String |
| Default | `mariadb:latest` |

##### `opensvc-use-orchestrated-start`

| | |
|---|---|
| Description | Controls how replication-manager starts OpenSVC V3 database services. When `false` (default), uses instance-level start (`om start --local`) which bypasses the orchestrator's global monitor state check — this works reliably even when the service is in warn state after a failed operation. When `true`, uses orchestrated abort + restart for HA-safe recovery that respects failover volume coordination. |
| Type | Boolean |
| Default | `false` |

**Background**: When an OpenSVC HA service fails to start (bad config, image pull failure), the service enters **warn state**. The orchestrator then refuses new orchestrated start requests with HTTP 409. The `clear` command does not remove the resource-level warning — only `abort` (which also cancels pending orchestrations) followed by an orchestrated `restart` (atomic stop+start) recovers the service.

The instance-level start (`om start --local`) bypasses this orchestration gate entirely, which is simpler and more reliable but does not coordinate failover volumes across nodes. For services using shared/failover storage, enable `opensvc-use-orchestrated-start` to use the HA-safe abort+restart path.

| Mode | How it works | Pros | Cons |
|---|---|---|---|
| `false` (default) | `StartInstanceV3` on the server's agent node | Always works, no 409 | Does not coordinate failover volumes |
| `true` | `AbortServiceV3` → `RestartServiceV3` | HA-safe, respects failover placement | Depends on orchestrator health |

```toml
# Enable orchestrated start for HA services with shared volumes
opensvc-use-orchestrated-start = true
```
