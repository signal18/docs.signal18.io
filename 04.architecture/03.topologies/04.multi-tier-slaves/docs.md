---
title: Multi Tier Slaves
taxonomy:
    category: docs
---
| Support Status  | Test Case |  
| ----------------|-----------|
| Experimental    | 0 |       

**replication-manager** supports replication tree or relay slaves architecture, in case of master death one of the slaves under the relay is promoted as a master.

**replication-manager** does not manage yet the relay server crash to replace it with a slave.

##### `replication-multi-tier-slave` (2.0), `multi-tier-slave` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Enable relay slaves topology |
| Type | boolean |
| Default Value | false |   
