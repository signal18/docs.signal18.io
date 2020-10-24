---
title: Registries Configuration
taxonomy:
    category: docs
---
The first supported micro service registry is Consul from *HashiCorp*, we are planning to add others like mdns, etcd, kubernetes, zookeeper.

#### Consul

**replication-manager (2.0)** support consul registry, the registration service will be extended to other registries in future releases.

A Consul server and agent need to be install locally with **replication-manager** as advised in the HashiCorp documentation https://www.consul.io/docs/index.html

##### `registry-consul` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Registry service write_cluster and read_cluster to a local consul agent . |
| Type | Boolean |
| Values | true |
| Default Value | "consul" |  

##### `registry-servers` (2.0)

| Item | Value |
| ---- | ----- |
| Description | Comma-separated list of registry addresses (default ) |
| Type | String |
| Values | Unused today but can be use later to point to remote servers|
| Default Value | "127.0.0.1" |


**replication-manager (2.0)** create a DNS entry **write_clustername** that follow the leader on failover and switchover.


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

**replication-manager (2.0)** create a DNS entry **read_clustername** that round robin on all cluster nodes.


```
; <<>> DiG 9.8.3-P1 <<>> @127.0.0.1 -p 8600 read_cluster_haproxy_multimasterring.service.consul SRV
; (1 server found)
;; global options: +cmd
;; Got answer:
;; ->>HEADER<<- opcode: QUERY, status: NOERROR, id: 43134
;; flags: qr aa rd; QUERY: 1, ANSWER: 2, AUTHORITY: 0, ADDITIONAL: 1
;; WARNING: recursion requested but not available

;; QUESTION SECTION:
;read_cluster_haproxy_multimasterring.service.consul. IN	SRV

;; ANSWER SECTION:
read_cluster_haproxy_multimasterring.service.consul. 0 IN SRV 1 1 3316 macbook-pro-de-apple-2.local.node.dc1.consul.
read_cluster_haproxy_multimasterring.service.consul. 0 IN SRV 1 1 3317 macbook-pro-de-apple-2.local.node.dc1.consul.
```

The services can be remove via the Consul agent entry are pending , **replication-manager** will not remove DNS entry on shutdown.

```
curl --request PUT http://localhost:8500/v1/agent/service/deregister/18259094474708091725
```


For querying catalogue service informations:
```
curl http://localhost:8500/v1/catalog/service/write_cluster_haproxy_multimasterring
[
    {
        "ID": "795b5921-0b0f-c914-6145-5570ba346b89",
        "Node": "macbook-pro-de-apple-2.local",
        "Address": "127.0.0.1",
        "Datacenter": "dc1",
        "TaggedAddresses": {
            "lan": "127.0.0.1",
            "wan": "127.0.0.1"
        },
        "NodeMeta": {
            "consul-network-segment": ""
        },
        "ServiceID": "write_cluster_haproxy_multimasterring",
        "ServiceName": "write_cluster_haproxy_multimasterring",
        "ServiceTags": [
            "v-789c32d033d03300040000ffff02c900ed"
        ],
        "ServiceAddress": "127.0.0.1",
        "ServicePort": 3317,
        "ServiceEnableTagOverride": false,
        "CreateIndex": 34410,
        "ModifyIndex": 34557
    }
]
```
