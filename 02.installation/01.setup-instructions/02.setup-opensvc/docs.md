---
title: Provisioning
taxonomy:
    category: docs
---

## Setup Provisioning

**replication-manager-pro** stands for provisioning and work best and by default in coordination with OpenSVC to deliver docker or podman services.

prov-orchestrator : onpremise|opensvc|kube|slapos|local (default "opensvc")

**replication-manager-osc** default is to monitoring exiting deployment prov-orchestrator:onpremise and restrict prov-orchestrator to local embedding code to bootstrap process with exiting binaries found on same node, this could be extend in some futur to ssh deployment in coordination with binaries dowloaded from various repositories API     

[OpenSVC](https://www.opensvc.com/) is a container orchestration, configuration management, infrastructure monitoring tool, the collector is a commercial software that collect and report informations sent from the OpenSVC open source agents.

If you are familiar with other service orchestration tools like Veritas Cluster or PowerHA or more new players like Kubernetes you can ramp up with OpenSVC [cheat sheet](https://docs.opensvc.com/latest/agent.rosettastone.html)  

**replication-manager-pro** can use the OpenSVC collector API to push services configuration and as a message queue to trigger bootstrapping micro-services configuration. The OpenSVC cluster agent receive the micro-service configuration, and is in charge of managing the resources requested with his internal drivers.

**replication-manager-pro** using OpenSVC can benefit from many drivers for virtualization, disk replication , or storage management. A complete list of possible drivers can be found [here](https://docs.opensvc.com/latest/agent.template.conf.html) as docker already have most images, we focus our efforts to provide docker deployments. Our team deployed some specific services for Google Cloud but we are not exposing them today.

Once done with your Orchestrator install learn how to configure and use it [here](/configuration/provisioning).


[plugin:youtube](https://www.youtube.com/watch?v=3eYlxZo8rRc)
