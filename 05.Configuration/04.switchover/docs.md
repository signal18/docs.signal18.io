---
title: Switchover Configuration
---
## Switchover Configuration

##### `switchover-at-equal-gtid` (1.1), `gtidcheck` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Switchover only when slaves are fully in sync. |
| Type | boolean |
| Default Value | false |   

##### `switchover-at-sync` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Switchover Only when state semisync is sync for last status. |
| Type | boolean |
| Default Value | false |

##### `switchover-wait-kill` (1.1), `wait-kill` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Switchover wait this many milliseconds before killing threads on demoted master. |
| Type | integer |
| Default Value | 5000 |


##### `switchover-wait-write-query` (1.1), `wait-write-query` (0.7)

| Item | Value |
| ---- | ----- |
| Description | Switchover is canceled if a write query is running for this time in second. |
| Type | integer |
| Default Value | 10 |


##### `switchover-wait-trx` (2.0), `wait-trx` (1.0)

| Item | Value |
| ---- | ----- |
| Description | Switchover is cancel after this timeout in second if can't return from FTWRL. |
| Type | integer |
| Default Value | 10 |
