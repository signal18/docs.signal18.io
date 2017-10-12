---
title: External Scripts
---

**replication-manager** automate some route change with popular proxies but not all, one can add own logic like VIP  or hardware load balancer API calls.

##### `failover-post-scripts` (2.0),  `post-failover-scripts` (0.7)

| Item          | Value |
| ----          | ----- |
| Description   | Full path of a post failover script. This is call after the new leader has been setup. |
| Type          | string |
| Default Value | "" |

##### `failover-pre-scripts` (2.0),  `pre-failover-scripts` (0.7)

| Item          | Value |
| ----          | ----- |
| Description   | Full path of a pre failover script. This is call after the new leader has been setup. |
| Type          | string |
| Default Value | "" |
