---
title: "Xtrabackup tools for database jobs"
taxonomy:
    category: docs
---

# Xtrabackup tools for database jobs

Official MySQL and Percona Server container images do not include every tool used by replication-manager physical backup jobs. Set `prov-db-docker-xtrabackup-img` to provide `xtrabackup`, `xbstream`, and `socat` to the database jobs container without replacing the database image.

The feature is disabled by default. It is supported only when `prov-orchestrator` is `opensvc` or `kubernetes`.

## Configure the helper image

Use `auto` to derive the matching official Percona XtraBackup image from the database image tag:

```toml
[my-cluster]
prov-db-docker-img = "mysql:8.4"
prov-db-docker-xtrabackup-img = "auto"
```

You can instead choose an explicit helper image:

```toml
[my-cluster]
prov-db-docker-xtrabackup-img = "percona/percona-xtrabackup:8.4"
```

Leave the value empty to turn the feature off:

```toml
[my-cluster]
prov-db-docker-xtrabackup-img = ""
```

In the dashboard, open **Configs**, then **Orchestrator images**, and select **Xtrabackup**. Choose **Off**, **Auto**, or an available catalog tag. Off is always allowed, including after the cluster has changed to an orchestrator that does not support the feature.

## Supported database images

Automatic selection and injection are intentionally limited to the official database image names:

| Database image | Result with `auto` |
| --- | --- |
| `mysql:<series>` or `library/mysql:<series>` | Selects the matching Percona XtraBackup series |
| `percona/percona-server:<series>` | Selects the matching Percona XtraBackup series |
| `mariadb:<tag>` | Disabled; MariaDB uses its own backup tooling |
| Custom, mirrored, digest-pinned, or untagged image | Disabled; select a supported official database image first |

For MySQL 5.7, `auto` selects the compatible `percona/percona-xtrabackup:2.4` image. For newer supported series, it selects the same major and minor series as the database image. The image catalog controls which newer series are available.

## What provisioning changes

On the next database reprovision, replication-manager:

1. Checks that the selected helper image can be pulled.
2. Runs an init container that copies and validates the tools.
3. Mounts the resulting bundle read-only in the database jobs container.
4. Adds the bundle to the end of the jobs container `PATH`.

The database container does not mount the bundle and continues to use its configured database image. The setting does not change running containers; reprovision the database service after changing it. A temporary registry outage does not make a database unavailable: when replication-manager cannot confirm a new helper image, it leaves the injection off so the database can start. A recently confirmed helper may remain enabled during a temporary registry outage.

## Kubernetes requirements

Kubernetes uses an `emptyDir` volume limited to 256 MiB for the bundle. The helper init container runs as root to populate that volume; namespaces enforcing the Kubernetes `restricted` Pod Security Standard reject it. Use a namespace compatible with replication-manager database provisioning, such as one enforcing the `baseline` profile.

The helper image must be reachable by both replication-manager and the Kubernetes workers. A registry check from replication-manager cannot prove that every worker has the same network and registry permissions.

## Troubleshooting

| Symptom | Action |
| --- | --- |
| The Xtrabackup selector is Off after selecting Auto | Confirm that the database image is an official MySQL or Percona Server image with a supported version tag. |
| The database starts but no helper init container is present | Verify that the helper image is reachable by replication-manager and that the setting is not empty. |
| The pod is rejected by admission | Check the namespace Pod Security policy. The helper requires a profile that permits a root init container. |
| The helper image cannot be pulled by a worker | Make the registry and image available to every Kubernetes worker, then reprovision. |
| A custom or mirrored database image needs these tools | Use a supported official database image for this feature, or provide the required tools in the custom image. |
