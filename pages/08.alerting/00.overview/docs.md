---
title: Overview
taxonomy:
    category: docs
---

## 1. Alerting

replication-manager continuously monitors cluster state and emits alerts the moment a condition is detected — and resolves them automatically once it clears. Every alert is sent through all configured channels simultaneously, and a matching resolve notification follows when the state returns to normal.

---

## 2. Alert Channels

| Channel | Description |
|---|---|
| **Email** | SMTP with optional TLS, configurable sender and recipients |
| **Slack** | Incoming webhook, custom channel and username |
| **Microsoft Teams** | Webhook with proxy support |
| **Mattermost** | Webhook with message attachments |
| **Pushover** | Mobile push notifications (app token + user token) |
| **Custom script** | Shell script or binary called on each alert/resolve event |

---

## 3. What Triggers an Alert

Alerts fire on any cluster state transition — new error or warning states detected on the monitoring tick that were absent on the previous tick. Common triggers:

- Primary down or unreachable
- Replica lag exceeding the failover threshold
- SQL or IO replication thread stopped on a replica
- Split-brain or arbitration failure
- Binlog inconsistency between nodes
- Backup or disk space warnings
- Configuration or grant mismatches

---

## 4. Alert Lifecycle

1. **Trigger** — `SetState()` adds an error or warning code to the current cluster state
2. **Detect** — next monitoring tick compares current state to the previous tick's state
3. **Send** — codes that are new (present now, absent before) dispatch an alert to all channels
4. **Resolve** — codes that cleared (absent now, present before) dispatch a resolve notification
5. **Suppress** — the scheduler can silence alerts during planned maintenance windows using a cron expression (`alert-disable`)

---

## 5. Configuration Guide

See [Configuration Guide](configuration-guide) for all SMTP, Slack, Teams, Mattermost, Pushover, and script settings.
