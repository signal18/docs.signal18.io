---
title: Configuration Guide
taxonomy:
    category: docs
---

## 8.2.1 Alerting Configuration Guide

replication-manager can deliver alerts through multiple channels when cluster state changes. Alerts fire when a monitored error or warning code first appears in the cluster state and again when it clears.

---

## 8.2.2 Alert Triggering

##### `monitoring-alert-trigger`

| | |
|---|---|
| Description | Comma-separated list of error/warning codes that trigger an alert notification. Only codes in this list cause notifications to be sent — all other state changes are logged but do not fire the alert channels. |
| Type | String |
| Default | `"ERR00027,ERR00042,ERR00087,ERR00002,WARN0023,WARN0100,WARN0115,WARN0116,WARN0139,WARN0140,WARN0141"` |
| Example | `"ERR00027,ERR00042,WARN0100"` |

##### `monitoring-ignore-errors`

| | |
|---|---|
| Description | Comma-separated list of error or warning codes to suppress entirely. Matching codes are never logged and never trigger alerts. Use this to silence known false positives. |
| Type | String |
| Default | `""` |
| Example | `"WARN0067,WARN0066"` |

---

## 8.2.3 Alert Blackout Window

Alerts can be suppressed on a schedule — useful for planned maintenance windows or known noisy periods (e.g. nightly batch jobs).

##### `scheduler-alert-disable`

| | |
|---|---|
| Description | Enable the scheduled alert blackout. When `true`, replication-manager silences all alert channels during the window defined by `scheduler-alert-disable-cron` and `scheduler-alert-disable-time`. Can also be toggled at runtime from the GUI. |
| Type | Boolean |
| Default | `false` |

##### `scheduler-alert-disable-cron`

| | |
|---|---|
| Description | Cron expression (6 fields: second minute hour day month weekday) that defines when the alert blackout window starts. |
| Type | String |
| Default | `"0 0 0 * * 0-4"` (midnight, Monday–Friday) |
| Example | `"0 30 22 * * *"` (22:30 every day) |

##### `scheduler-alert-disable-time`

| | |
|---|---|
| Description | Duration of the alert blackout window in seconds. |
| Type | Integer |
| Default | `3600` (1 hour) |

---

## 8.2.4 External Script

An external script is called on every state change for every monitored server, regardless of `monitoring-alert-trigger`. It receives the affected server information as arguments.

##### `alert-script`

| | |
|---|---|
| Description | Full path to a script to execute when a server state changes. The script is called with three positional arguments: server URL, previous state, current state. |
| Type | String |
| Default | `""` (disabled) |
| Example | `"/opt/scripts/repman-alert.sh"` |

```bash
#!/bin/bash
# Arguments: $1=server_url  $2=previous_state  $3=current_state
echo "$(date) $1 changed from $2 to $3" >> /var/log/repman-alerts.log
```

---

## 8.2.5 Email

Email alerts require a reachable SMTP server. The mail alert fires for all state transitions.

##### `mail-from`

| | |
|---|---|
| Description | Sender address for alert emails. Must be a valid user on the configured SMTP server. |
| Type | String |
| Default | `"mrm@localhost"` |

##### `mail-to`

| | |
|---|---|
| Description | Comma-separated list of recipient addresses. Leave empty to disable email alerting. |
| Type | String |
| Default | `""` |
| Example | `"ops@example.com,dba@example.com"` |

##### `mail-smtp-addr`

| | |
|---|---|
| Description | SMTP server address in `host:port` format. |
| Type | String |
| Default | `"localhost:25"` |
| Example | `"smtp.example.com:587"` |

##### `mail-smtp-user`

| | |
|---|---|
| Description | SMTP authentication username. Leave empty for unauthenticated relay. |
| Type | String |
| Default | `""` |

##### `mail-smtp-password`

| | |
|---|---|
| Description | SMTP authentication password. |
| Type | String |
| Default | `""` |

##### `mail-smtp-tls-skip-verify`

| | |
|---|---|
| Description | Connect to SMTP with TLS but skip certificate verification. |
| Type | Boolean |
| Default | `false` |

##### `mail-max-pool`

| | |
|---|---|
| Description | Maximum number of persistent SMTP connections to keep open. `0` disables connection pooling (a new connection is opened for each alert). |
| Type | Integer |
| Default | `0` |

##### `mail-timeout`

| | |
|---|---|
| Description | Timeout in seconds when waiting for an SMTP connection from the pool. `0` means no timeout. |
| Type | Integer |
| Default | `5` |

---

## 8.2.6 Slack

Slack alerts use an incoming webhook URL. Slack alerting is global to the replication-manager instance — all clusters share the same webhook.

##### `alert-slack-url`

| | |
|---|---|
| Description | Slack incoming webhook URL. Leave empty to disable Slack alerting. |
| Type | String |
| Default | `""` |
| Example | See Slack → App Management → Incoming Webhooks for your workspace URL |

##### `alert-slack-channel`

| | |
|---|---|
| Description | Slack channel to post alerts to. |
| Type | String |
| Default | `"#support"` |

##### `alert-slack-user`

| | |
|---|---|
| Description | Display name for the Slack bot posting the alert. |
| Type | String |
| Default | `""` |

---

## 8.2.7 Pushover

Pushover delivers mobile push notifications to iOS and Android via the [Pushover](https://pushover.net) service.

##### `alert-pushover-app-token`

| | |
|---|---|
| Description | Pushover application token (created at pushover.net). Leave empty to disable Pushover alerting. |
| Type | String |
| Default | `""` |

##### `alert-pushover-user-token`

| | |
|---|---|
| Description | Pushover user or group key — identifies who receives the notification. |
| Type | String |
| Default | `""` |

---

## 8.2.8 Microsoft Teams

Teams alerts are sent via an incoming webhook. Alerting can be filtered to specific severity levels.

##### `alert-teams-url`

| | |
|---|---|
| Description | Microsoft Teams incoming webhook URL. Leave empty to disable Teams alerting. |
| Type | String |
| Default | `""` |

##### `alert-teams-proxy-url`

| | |
|---|---|
| Description | HTTP proxy URL to use when calling the Teams webhook (for environments where outbound HTTP requires a proxy). |
| Type | String |
| Default | `""` |

##### `alert-teams-state`

| | |
|---|---|
| Description | Comma-separated list of severity prefixes to forward to Teams. Only state changes whose code starts with one of these prefixes are sent. Leave empty to send all triggered alerts. |
| Type | String |
| Default | `""` |
| Example | `"ERR"` (errors only) · `"ERR,WARN"` (errors and warnings) · `"ERR,WARN,INFO"` (all) |

---

## 8.2.9 Syslog

##### `log-syslog`

| | |
|---|---|
| Description | Mirror all log output to the local UDP syslog port in addition to the normal log file. Allows log aggregation systems (Graylog, Splunk, ELK) to receive replication-manager events via syslog. |
| Type | Boolean |
| Default | `false` |

---

## 8.2.10 Cloud18 Integration

When the instance is registered with the Signal18 SSO, alert notifications can also be forwarded to the Signal18 Mattermost instance. These settings are global (not per-cluster).

##### `cloud18-alert`

| | |
|---|---|
| Description | Enable forwarding of alerts to the Cloud18 Slack/Mattermost channel. |
| Type | Boolean |
| Default | `true` |

##### `cloud18-alert-slack-channel`

| | |
|---|---|
| Description | Mattermost channel name to post Cloud18 alerts to. |
| Type | String |
| Default | `"signal18_alert"` |

##### `cloud18-alert-slack-url`

| | |
|---|---|
| Description | Webhook URL for the Cloud18 Mattermost instance. |
| Type | String |
| Default | `"https://meet.signal18.io/hooks/…"` |

##### `cloud18-alert-slack-user`

| | |
|---|---|
| Description | Display name for the Cloud18 alert bot. |
| Type | String |
| Default | `"repman"` |

---

## 8.2.11 Checking Alert Status via API

The following endpoints allow external monitoring systems to poll cluster health:

```
GET /api/status
# {"alive": "running"} or {"alive": "starting"}

GET /api/clusters/{clusterName}/status
# {"alive": "running"} or {"alive": "errors"}

GET /api/clusters/{clusterName}/topology/alerts
# Returns the current active alert list
```

Via CLI:

```bash
replication-manager-cli status
# running

replication-manager-cli status --cluster=my-cluster
# running  or  errors

replication-manager-cli status --cluster=my-cluster --with-errors
# JSON object listing all active errors and warnings
```
