---
title: External Scripts
---

### External Scripts

**replication-manager** can automate route changes with popular proxies. however in some edge cases e.g. pacemaker or keepalived, logic like vip or hardware load balancer api calls can be managed through the external scripts option.

##### `failover-post-script` (2.0),  `post-failover-script` (0.7)

| Item          | Value |
| ----          | ----- |
| Description   | Full path of a post-failover script. This is called after the new leader has been setup. The previous master host and the newly elected master host are passed as sequential arguments. |
| Type          | string |
| Default Value | "" |
| Example | "/usr/local/bin/vip-up.sh" |

##### `failover-pre-script` (2.0),  `pre-failover-script` (0.7)

| Item          | Value |
| ----          | ----- |
| Description   | Full path of a pre-failover script. This is called after the new leader has been elected. The previous master host and the newly elected master host are passed as sequential arguments. |
| Type          | string |
| Default Value | "" |
| Example | "/usr/local/bin/vip-down.sh" |

### External Proxy & Route

**replication-manager** can inject heartbeat into the route and check that the internal master is alway the one reach via the proxies or the route.

##### `extproxy` (2.0)

| Item          | Value |
| ----          | ----- |
| Description   | External proxy can be used to specify a route manage with external scripts setup. |
| Type          | Boolean |
| Default Value | false|

##### `extproxy-address` (2.0)

| Item          | Value |
| ----          | ----- |
| Description   | Network address when route is manage via external script, host:[port] format. |
| Type          | String |
| Default Value | ""|
