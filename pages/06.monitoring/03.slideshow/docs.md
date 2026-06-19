---
title: Cluster Slideshow
taxonomy:
    category: docs
---

# Cluster Slideshow

The slideshow (`/slideshow`) is a full-screen NOC-style presentation that loops through every managed cluster automatically. It is designed for wall-mounted screens or unattended monitoring displays.

## How to enable

Configure a dashboard viewer user in `replication-manager.toml`:

```toml
api-credentials           = "admin:repman,dba:repman,viewer:viewerpassword"
api-credentials-acl-allow = "admin:cluster db proxy prov global grant show sale extrole terminal,dba:cluster proxy db,viewer:show cluster-show-backups cluster-show-routes cluster-show-certificates cluster-show-graphs cluster-show-agents"
api-dashboard-user        = "viewer"
```

Then open `http://<host>:10005/dashboard` in any browser — no login required. The slideshow starts immediately and runs indefinitely.

## What it shows

For each cluster the slideshow displays two slides in sequence:

| Slide | Content |
|-------|---------|
| **Dashboard** | Cluster detail, HA state, database servers, proxies, application servers, workload, logs |
| **Maintenance** | Backup list, Restic snapshots, current backup task progress, job queue, job logs |

After cycling through all clusters it loops back to the first one.

## Timing and progress

Each slide is shown for **30 seconds**. A thin blue progress bar across the top of the page tracks time remaining on the current slide. The header shows the cluster name, the current view (Dashboard / Maintenance), and a cluster counter ("Cluster 2 of 5").

Underlying cluster data is refreshed in the background every `refresh_interval` seconds (default 4 s) so the information stays current throughout the display.

## Pause and resume

The slideshow can be paused and resumed with the play/stop buttons in the top bar. While paused, the current slide stays on screen and data continues to refresh.

## Access control

The slideshow uses the same `viewer` JWT issued by `/api/dashboard-token`. Action buttons that require write grants (switchover, failover, configuration changes, provisioning, backup triggers) are hidden because the `viewer` ACL does not include those grants — the page is safe to display on a shared or public screen.

> **Security note:** `GET /api/dashboard-token` returns a token to any visitor without credentials. Set the `viewer` user's ACL to the minimum required (e.g. `show` only). The endpoint is disabled when `api-dashboard-user` is empty — remove or clear the key to turn it off.

## See also

- [Web Interface](/usage/http) — full HTTP/API server configuration including the `/dashboard` endpoint
