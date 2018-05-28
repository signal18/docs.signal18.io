---
title: Provisioning
taxonomy:
    category: docs
---

## Setup Provisioning

**replication-manager-pro** stands for provisioning and work in conjunction with OpenSVC.

[OpenSVC](https://www.opensvc.com/) is a container orchestration, configuration management, infrastructure monitoring tool, the collector is a commercial software that collect and report informations sent from the OpenSVC open source agents.

If you are familiar with other service orchestration tools like Veritas Cluster or PowerHA or more new players like Kubernetes you can ramp up with OpenSVC [cheat sheet](https://docs.opensvc.com/latest/agent.rosettastone.html)  

**replication-manager-pro** use the collector to push services configuration and as a message queue to trigger bootstrapping micro-services configuration. The OpenSVC cluster agent receive the micro-service configuration, and is in charge of managing the resources requested with his internal drivers.

**replication-manager-pro** can benefit from many drivers for virtualization, disk replication , or storage management. A complete list of possible drivers can be found [here](https://docs.opensvc.com/latest/agent.features.html) as docker already have most images, we focus our efforts to provide docker deployments. Our team deployed some specific services for Google Cloud but we are not exposing them today.

Once done with the settings learn how to configure and use it [here](/configuration/provisioning).
