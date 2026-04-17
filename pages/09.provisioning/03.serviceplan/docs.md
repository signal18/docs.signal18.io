---
title: Service Plan
taxonomy:
    category: Provisioning
---


From **replication-manager 2.1** a service plan can be used to enable pre cap cluster resource deployment.
In this scenario external API can be used to select a plan and a placement to external available orchestrator clusters   

A list of plans can be exposed inside some external API:

http://gsx2json.com/api?id=130326CF_SPaz-flQzCRPE-w7FjzqU1NqbsM7MpIQ_oU&sheet=1&columns=false

If a plan is set for a cluster, **replication-manager** auto set provisioning variables for the resources describe by the plan  
Using ACL  **replication-manager** can restrict later user change of such resources.

Clusters and nodes selection will be enable via an external API that will send such request

in: {"region": "eu", "n_instances": 2, "req_cpu": 2, "req_mem": "100g", "req_swap": 0}

And is waiting for similar answers

resp: [{"cluster_id": <cid>, "cluster_name": "cl1", "nodes": [{"nodename": "node1", "az": "fr1"}, {"nodename": "node2", "az": "de1"}, ...]}, {"cluster_id": <cid>, "cluster_name": "cl2", "nodes": [{"nodename": "node1", "az": "it1"}, {"nodename": "node2", "az": "cz1"}, ...]}, ...]

ACL describe how **replication-manager** interact with user for placement
prov-placement-manual
prov-placement-first
prov-placement-interactive
