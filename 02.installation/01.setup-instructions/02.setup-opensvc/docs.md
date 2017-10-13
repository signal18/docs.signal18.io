---
title: Provisioning
taxonomy:
    category: docs
---

## Setup Provisioning

**replication-manager-pro** stands for provisioning and work in conjunction with OpenSVC.

[OpenSVC](https://www.opensvc.com/) is a container orchestration, configuration management, infrastructure monitoring tool, collector is a commercial software that collect informations sent from the OpenSVC open source agents.

**replication-manager-pro** use the collector to collect node configuration, as a message queue to trigger bootstrapping microservices configuration. The OpenSVC cluster agent receive a micro-service configuration, he is in charge of managing the resources requested with his own drivers.

**replication-manager-pro** can benefit from many drivers for virtualization, HA replication , or storage  management. A complete list of possible drivers can be found [here](https://docs.opensvc.com/latest/agent.feature.matrix.html) as docker already have most small images we put our effort to support it. We already deployed some specific services for Google Cloud but we did not yet serve them today. 
