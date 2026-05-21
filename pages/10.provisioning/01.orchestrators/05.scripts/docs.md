---
title: Scripts
taxonomy:
    category: docs
---



In **replication-manager 2.2** provision can call some extra local scripts

##### `prov-db-bootstrap-script` (2.2)

| Item | Value |
| ---- | ----- |
| Description | Path to database bootstrap script |
| Type | String |
| Default | ""  |
| Example | "/home/deploy/install_database_ubuntu_2004.sh" |

##### `prov-proxy-bootstrap-script` (2.2)

| Item | Value |
| ---- | ----- |
| Description | Path to proxy bootstrap script |
| Type | String |
| Default | ""  |
| Example | "/home/deploy/install_proxy_ubuntu_2004.sh" |


##### `prov-db-cleanup-script` (2.2)

| Item | Value |
| ---- | ----- |
| Description | Path to database cleanup script |
| Type | String |
| Default | ""  |
| Example | "/home/deploy/cleanup_database_ubuntu_2004.sh" |


##### `prov-proxy-cleanup-script` (2.2)

| Item | Value |
| ---- | ----- |
| Description | Path to proxy cleanup script |
| Type | String |
| Default | ""  |
| Example | "/home/deploy/cleanup_proxy_ubuntu_2004.sh" |


##### `prov-db-start-script` (2.2)

| Item | Value |
| ---- | ----- |
| Description | Path to database start script |
| Type | String |
| Default | ""  |
| Example | "/home/deploy/start_database_ubuntu_2004.sh" |


##### `prov-db-stop-script` (2.2)

| Item | Value |
| ---- | ----- |
| Description | Path to database stop script |
| Type | String |
| Default | ""  |
| Example | "/home/deploy/stop_database_ubuntu_2004.sh" |

##### `prov-proxy-start-script` (2.2)

| Item | Value |
| ---- | ----- |
| Description | Path to proxy start script |
| Type | String |
| Default | ""  |
| Example | "/home/deploy/start_proxy_ubuntu_2004.sh" |

##### `prov-proxy-stop-script` (2.2)

| Item | Value |
| ---- | ----- |
| Description | Path to proxy stop script |
| Type | String |
| Default | ""  |
| Example | "/home/deploy/stop_proxy_ubuntu_2004.sh" |
