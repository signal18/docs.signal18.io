---
title: SlapOS
taxonomy:
    category: docs
---

SlapOS use replication API call to request actions to be performed and can bootstrap software configuration via giving the following parameters


##### `slapos-db-partitions` (2.1)

| Item | Value |
| ---- | ----- |
| Description | List databases slapos partitions path |
| Type | String |
| Default | ""  |
| Example | "/srv/lib/slapos/partitions/0/data" |

##### `slapos-proxysql-partitions` (2.1)

| Item | Value |
| ---- | ----- |
| Description |  List proxysql slapos partitions  |
| Type | String |
| Default | ""  |
| Example | "/srv/lib/slapos/partitions/0/data" |

##### `slapos-haproxy-partitions` (2.1)

| Item | Value |
| ---- | ----- |
| Description |  List haproxy slapos partitions  |
| Type | String |
| Default | ""  |
| Example | "/srv/lib/slapos/partitions/0/data" |

##### `slapos-maxscale-partitions` (2.1)

| Item | Value |
| ---- | ----- |
| Description |   List maxscale slapos partitions path  |
| Type | String |
| Default | ""  |
| Example | "/srv/lib/slapos/partitions/0/data" |

##### `slapos-shardproxy-partitions` (2.1)

| Item | Value |
| ---- | ----- |
| Description |   List spider proxy slapos partitions path  |
| Type | String |
| Default | ""  |
| Example | "/srv/lib/slapos/partitions/0/data" |

##### `slapos-sphinx-partitions` (2.1)

| Item | Value |
| ---- | ----- |
| Description |   List sphinx proxy slapos partitions path  |
| Type | String |
| Default | ""  |
| Example | "/srv/lib/slapos/partitions/0/data" |
