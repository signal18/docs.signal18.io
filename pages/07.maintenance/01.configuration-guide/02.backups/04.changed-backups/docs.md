---
title: Changed Backups
taxonomy:
    category: docs
---
### 7.2.2.4.0.1 Changed Backups (3.1.45)

Replication-manager remembers what each logical backup looked like when it was written or registered. If the backup's files change afterwards, by hand or by another tool, replication-manager **stops using that backup until you register the change**. Your files are never deleted or modified: the backup stays recoverable, but replication-manager will not reseed from it on its own.

This page explains what counts as a change, what is refused, and how to edit a backup on purpose, for example removing tables from a splitdump or mydumper folder.

#### What is recorded

When a logical backup completes (mysqldump, splitdump, mydumper), or when you register a backup found in the backup folder, replication-manager records a **fingerprint** of its files:

- for a single file: its size, modification time, inode and change time (ctime);
- for a folder (splitdump, mydumper): the number of files, their total size, the newest modification and change times, and the folder's inode.

The change time is there because the modification time can be put back (`touch`, `cp -p`, `rsync -t`), the change time cannot: the kernel updates it on every change to the file, so a backup replaced by a copy with the same size and date is still detected.

Physical backups (mariabackup, xtrabackup) have no fingerprint and are not checked. Off Linux no fingerprint is taken.

#### What counts as a change

Anything that makes the files differ from the fingerprint:

- removing, adding or editing files of a splitdump or mydumper folder;
- replacing the backup file, even with one of the same size and date;
- restoring the backup from its archive (the file comes back as a new file);
- a change of owner or permissions (`chown`, `chmod`), an ACL, or an attribute restore: these update the change time without touching the content.

> Development builds before #2114 changed, at each start, the owner of every file in its working directory, so every registered backup was reported changed after each restart. Replication-manager now leaves the files already owned by its user alone.

#### What you see

The Maintenance tab marks the backup **Changed since registered: register the change first**, and the cluster raises **WARN0249** per server, listing each changed backup and how it changed (sizes, file counts, modification time, or "replaced or rewritten, same size and modification time").

When no other logical backup is usable, **WARN0190** (no logical backup available for rejoin) follows.

#### What is refused, and what is not

Until the change is registered, replication-manager refuses that backup for:

- an automatic rejoin or reseed of a server;
- a manual reseed from that backup, from the GUI or the API;
- the flashback restore of a rejoin;
- archiving it.

It does not stop you from restoring it yourself: the files are untouched, `myloader` or `mysql` run by hand work as before.

#### Editing a backup on purpose

Moving files out of a dump is a normal operation, for example:

- removing the tables of clients whose data must not be reloaded;
- moving a very large table aside so the rest restores faster, and loading it separately.

The steps:

1. Edit the backup folder.
2. In the Maintenance tab, click **Register** on that backup, or call `POST /api/clusters/{cluster}/backups/{id}/register`.
3. Replication-manager reads the backup again, as a restore would, in the background. It accepts it if it is still a proper dump (at least one table, complete) and records the new fingerprint. On a large backup this takes as long as reading it once.
4. The backup is usable again, as you left it: a reseed loads only the tables still in it.

If Register refuses it, the reason is shown on the backup. Unregister removes the backup from the catalogue without touching its files.

To go back to the original instead, restore it from its archive and register it: the restored file is a new file, so it is a change too.

#### After a chown or chmod

The content did not change, but the backup is reported changed all the same. Register it: replication-manager reads it again and records it.
