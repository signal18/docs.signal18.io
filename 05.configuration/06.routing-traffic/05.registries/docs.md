---
title: Registry Configuration
---

#### Consul


*replication-manager (2.0)* support consul registry, the registration service will be extended to other registries in futur releases.

##### `registry` (2.0)

| Item | Value |
| ---- | ----- |
| Description | registry service. |
| Type | List |
| Values | consul,mdns,etcd,kubernetes,zookeeper|
| Default Value | "consul" |  

##### `registry-servers` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Comma-separated list of registry addresses (default ) |
| Type | String |
| Values | consul,mdns,etcd,kubernetes,zookeeper|
| Default Value | "127.0.0.1" |


```
dig @127.0.0.1 -p 8600 write_cluster_haproxy_multimasterring.service.consul SRV

; <<>> DiG 9.8.3-P1 <<>> @127.0.0.1 -p 8600 write_cluster_haproxy_multimasterring.service.consul SRV
; (1 server found)
;; global options: +cmd
;; Got answer:
;; ->>HEADER<<- opcode: QUERY, status: NOERROR, id: 56071
;; flags: qr aa rd; QUERY: 1, ANSWER: 1, AUTHORITY: 0, ADDITIONAL: 1
;; WARNING: recursion requested but not available

;; QUESTION SECTION:
;write_cluster_haproxy_multimasterring.service.consul. IN SRV

;; ANSWER SECTION:
write_cluster_haproxy_multimasterring.service.consul. 0	IN SRV 1 1 3315 macbook-pro-de-apple-2.local.node.dc1.consul.
```
