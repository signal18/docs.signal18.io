---
title: Server Configuration
---

## Minimal configuration

This is a minimal configuration sample required to run `replication-manager`:

```
[Default]
title = "ClusterTest"
db-servers-hosts = "127.0.0.1:5055,127.0.0.1:5056"
db-servers-credential = "skysql:skyvodka"
replication-credential = "skysql:skyvodka"
failover-mode = "manual"

```
