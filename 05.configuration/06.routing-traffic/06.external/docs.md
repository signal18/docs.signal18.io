---
title: External Scripts
---

### External Scripts

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
