---
title: Archive Plugins
taxonomy:
    category: docs
---

## 11.9.1 Archive Plugins

> **Available since:** replication-manager **v3.1.45**

An **archive** is where a cluster's backups go once they leave the backup directory: their versions, their retention and the way back for a restore or a reseed. In v3.1.45 a cluster no longer has one restic repository wired into its settings. It has a **list of archives**, each one made from an **archive plugin** with its own settings and secrets.

Each archive plugin declares:

- **typed settings**: `string`, `int`, `bool`, `enum` or `secret`. The add and edit forms of the Maintenance tab, and the checks of the API, are drawn from them.
- **capabilities**: what can be done with an archive of that plugin.

| Capability | Meaning |
|---|---|
| `edit` | its settings and secrets can be changed |
| `remove` | its content can be deleted (remove deletes the content, then takes the archive out of the list) |
| `probe` | opens an existing repository (a wrong password is refused), or initialises a new one |
| `size` | reports the bytes its content takes, shown in the list and in the remove confirmation |
| `purge` | applies the cluster's retention policy (`backup-keep-*`), or the archive's own `purge-policies` |
| `s3` | (external plugins) its archives are in a bucket: replication-manager adds the `s3-provider` setting and hands the provider's endpoint, region and keys |

### The plugins

| Plugin | Tier | Kind | Storage |
|---|---|---|---|
| `archive-restic-local` | everyone | internal (restic) | restic repository on a local path, by default `<working dir>/backups/archive/<cluster>` |
| `archive-restic-sftp` | everyone | internal (restic) | restic repository over sftp (`sftp:user@host:/path`) |
| `archive-restic-s3` | everyone | internal (restic) | restic repository in an S3 bucket |
| `archive-plakar-local` | enterprise | external, signed | plakar archive in a local folder: the plakar engine is embedded, no plakar program to install |
| `archive-plakar-s3` | enterprise | external, signed | plakar archive in an S3 bucket |
| `archive-pbs` | enterprise | external, signed | a namespace of a Proxmox Backup Server datastore, in pure Go: no `proxmox-backup-client` to install |

Enterprise plugins can be added or attached on the Partner and Support plans. The Developer plan also gets them, for testing.

---

## 11.9.2 Archives of a cluster

- **Names.** An archive has a name you choose (`archive-bso-s3`, `archive-rdem-pbs`) and can rename: lowercase letters, digits, `-` and `_`, at most 64 characters, unique in the cluster. An archive added without a name gets `archive-<type>-<n>`, for example `archive-local-1`.
- **Attached archive.** A cluster sends its backups to one archive, named by `backup-archive-name` (empty for none). Switching archives means changing that one setting. The archive left is **detached**: it stays in the list with its content and its size, and restoring from it needs it attached again.
- **Remove deletes the content.** Removing an archive deletes its content, then takes it out of the list. Content left behind would still be invoiced and no longer visible. The confirmation shows the size deleted. Removing the attached archive detaches it first.
- **Replacing a heavy archive:** add `archive-local-2`, attach it, then remove `archive-local-1`.

### Configuration

The archive list is a TOML array of tables in the cluster section. A plugin's settings and secrets are keys of its entry; secrets are stored encrypted and are never sent back by the API.

```toml
[mycluster]
backup-archive-name = "archive-bso-s3"

[[mycluster.archive]]
name   = "archive-local-1"
plugin = "archive-restic-local"

[[mycluster.archive]]
name   = "archive-bso-s3"
plugin = "archive-restic-s3"
aws-bucket   = "backups-paris"
aws-endpoint = "https://s3.fr-par.example"
aws-access-key-id = "..."
# password and aws-access-secret are secrets: set them from the GUI or the API
```

### Migration from `backup-restic-*`

When it loads a cluster that has no archive list, replication-manager builds archive 1 from `backup-archive-mode` and the `backup-restic-*` settings:

| Old mode | New archive |
|---|---|
| `restic-local` | `archive-local-1` |
| `restic-sftp` | `archive-sftp-1` |
| `restic-aws` | `archive-s3-1` |

- The password and the S3 secret move to that archive.
- With mode `none` and customised settings, the archive is kept but not attached, so nothing is lost.

The save writes the list instead of the legacy keys. Downgrading to v3.1.44 is not supported, and an active/standby pair must be upgraded together.

`backup-archive-mode` remains as a shortcut over the list: `none` detaches; another mode attaches an archive of that type, or creates one.

### Retention

Retention is the cluster's, `backup-keep-*`: by default the last 10, plus 1 hourly, 1 daily, 4 weekly, 12 monthly and 2 yearly. The plugin only says how it applies it, through its `purge-*` settings.

- **Tagged policies.** `backup-keep-tag-policies` keeps snapshots that carry a tag by their own rules, counted apart from the backups. The default is `trigger:binlogs keep-within=1d`, for the binlog-only snapshots taken between backups. Snapshots taken since the last backup are always kept.
- **Per-archive policies.** An archive's `purge-policies` setting replaces the cluster's tagged policies for that archive.
- **When the purge runs.** `backup-archive-purge-trigger` is `after-backup` (each backup the archive receives) or `schedule` (on `scheduler-archive-purge-cron`).

### Keeping the backup in the archive only

With `backup-archive-remove-original = true`, a backup is deleted from the backup directory once the archive's snapshot is checked to hold it. A restore or a reseed fetches it back from the archive first.

---

## 11.9.3 External plugins

An external archive plugin is a program named `archiveplugin-<name>`, run once per operation:

- the request goes on its stdin as one JSON document;
- the answer comes back on its stdout as one JSON document;
- progress is written on stderr;
- secrets are passed only in its environment (`REPMAN_ARCHIVEPLUGIN_SECRET_*`), never logged nor written in a file.

`archiveplugin-<name> --manifest` prints its manifests.

replication-manager looks for these programs in `<working dir>/plugins`, and for a source build in the `plugins` directory next to its binary. When `plugin-signing-public-key` is set, a program without a valid signature is left out, as for the log plugins (see [Plugin Architecture](../architecture)).

The operations are `probe`, `push`, `list`, `restore`, `forget`, `purge`, `size`, `remove` and `sync`, as the plugin's capabilities allow.

### plakar (`archiveplugin-plakar`)

Encrypted, deduplicated plakar archives, built on plakar's own engine (kloset).

| Setting | Plugin | Type | Meaning |
|---|---|---|---|
| `location` | local | string | the archive's folder |
| `bucket` | s3 | string | the bucket (created if missing when the keys allow it) |
| `prefix` | s3 | string | the folder in the bucket |
| `use-tls` | s3 | bool, advanced | https to the endpoint (default on) |
| `password` | both | secret | the archive's passphrase |
| `compression` | both | bool, advanced | compression of a new archive (default on) |
| `cache-dir` | both | string, advanced | the engine's local cache |

Deleting a snapshot only marks it as deleted. The purge reclaims the space of packfiles that were marked more than 7 days earlier, so space comes back about a week later, on a later purge.

### Proxmox Backup Server (`archiveplugin-pbs`)

Each cluster's backups go to one namespace of a PBS datastore.

| Setting | Type | Meaning |
|---|---|---|
| `base-url` | string | `https://<host>:8007` |
| `datastore` | string | the datastore, created on the PBS by its admin |
| `namespace` | string | the cluster's namespace (`a/b/c`); `remove` needs one |
| `auth-id` | string | the API token, `user@realm!token` |
| `fingerprint` | string | the server certificate's SHA-256 fingerprint, to pin a self-signed PBS |
| `secret` | secret | the API token's secret |
| `encryption-key` | secret | a `proxmox-backup-client` key file (JSON, or its paper key text) |
| `encryption-key-password` | secret | the key's passphrase, when it has one |
| `no-purge` | bool | the token may not prune: the PBS prune job keeps the retention |
| `no-modify` | bool | the token may not modify the datastore: no namespace created, `remove` refused |
| `no-encrypt` | bool | a new archive gets no generated key |
| `chunk-cache` | int, advanced | restore: chunks kept in memory (default 16, about 64 MB) |

- **Encrypted by default.** When a new archive is added without a key, its probe generates one, and replication-manager stores it as the archive's secret. Keep a copy of it with the configuration backup: without the key, the snapshots cannot be restored. An archive with no key and without `no-encrypt` raises WARN0263.
- **One token per cluster.** The plugin's `scripts/pbs-cluster-token.sh` creates the cluster's namespace and a token limited to it, so one cluster never reads or deletes another cluster's backups. `pbs-cluster-allow-purge.sh` lets that token prune its own backups.
- **Retention.** PBS prunes by counts. A `backup-keep-within*` window has no PBS equivalent, so that purge is refused rather than run with less than the policy. Space comes back with the datastore's garbage collection.

---

## 11.9.4 API

| Route | Action |
|---|---|
| `GET /api/clusters/{cluster}/archive-list` | the archives, their plugins and the attached one |
| `GET /api/clusters/{cluster}/archive-snapshots/{archive}` | an archive's snapshots |
| `POST /api/clusters/{cluster}/archive-actions/add` | add an archive: `{"name": "...", "plugin": "...", "settings": {...}}`, probed first; an archive that can't be opened is not added |
| `POST /api/clusters/{cluster}/archive-actions/edit/{archive}` | change settings or secrets (`unset` clears them) |
| `POST /api/clusters/{cluster}/archive-actions/rename/{archive}/{newName}` | rename |
| `POST /api/clusters/{cluster}/archive-actions/attach/{archive}` | attach (the backups go to it) |
| `POST /api/clusters/{cluster}/archive-actions/detach` | detach the attached archive |
| `POST /api/clusters/{cluster}/archive-actions/remove/{archive}` | delete its content and remove it |
| `GET /api/clusters/{cluster}/archive-actions/size/{archive}` | the bytes it takes |
| `POST /api/clusters/{cluster}/archive-actions/forget/{archive}/{snapshot}` | delete one snapshot |
| `POST /api/clusters/{cluster}/archive-actions/snapback/{snapshot}` and `snapback-release/{server}` | put a server's backup directory back as it was in a snapshot, and release it |
| `POST /api/clusters/{cluster}/archive-actions/rebuild-catalogue[/{archive}]`, `drop-missing-snapshots/{archive}` | rebuild the backup catalogue from the archives; resolve a difference between them |

See [Archiving](/maintenance/configuration-guide/backups/archiving) for the restic settings and disk space management.
