---
title: External Scripts
---

**replication-manager** can automate route changes with popular proxies. However in some edge cases e.g. Pacemaker or Keepalived, logic like VIP or hardware load balancer API calls can be managed through the external scripts option.

##### `failover-post-scripts` (2.0),  `post-failover-script` (0.7)

| Item          | Value |
| ----          | ----- |
| Description   | Full path of a post failover script. This is called after the new leader has been setup. The previous master host and the newly elected master host are passed as sequential arguments. |
| Type          | string |
| Default Value | "" |

##### `failover-pre-scripts` (2.0),  `pre-failover-script` (0.7)

| Item          | Value |
| ----          | ----- |
| Description   | Full path of a pre failover script. This is called after the new leader has been elected. The previous master host and the newly elected master host are passed as sequential arguments. |
| Type          | string |
| Default Value | "" |
