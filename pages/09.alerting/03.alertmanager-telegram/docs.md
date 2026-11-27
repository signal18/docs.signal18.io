---
title: Alertmanager, Telegram and the Alert Queues
taxonomy:
    category: docs
---

## 9.4.1 Alertmanager, Telegram and the Alert Queues

Available from replication-manager 4.0.1.

replication-manager can send its alerts to Prometheus Alertmanager and to Telegram, next to email, Slack, Pushover, Teams and the alert script. Each channel has its own send queue: a channel that is down or slow never delays the monitoring, it only drops its oldest waiting alerts, and a state tells you so.

All the settings below are per cluster, in the GUI under **Settings > Alerts** (sub-tabs **Alertmanager**, **Telegram**, **Triggers**, **Script**), through the cluster settings API, or in the cluster's configuration file. Passwords and tokens are encrypted in the saved configuration and never written to a log.

---

## 9.4.2 Prometheus Alertmanager

The states listed in `monitoring-alert-trigger` are sent to Alertmanager (v2 API, `POST /api/v2/alerts`):

- when a state opens, it fires;
- while it stays open, it is posted again every `alert-alertmanager-resend-interval`, as Alertmanager expects (it resolves by itself an alert it has not heard of within its `resolve_timeout`, 5 minutes by default);
- when it clears, it is posted with its end time, and Alertmanager resolves it.

At each resend replication-manager compares what it has sent with the states that are really open: a state open since before a restart is sent, and an alert whose state has gone is resolved.

Labels of each alert:

| Label | Value |
|---|---|
| `alertname`, `code` | the state code, for example `ERR00042` |
| `cluster` | the cluster name |
| `server` | the database server, for a server state |
| `severity` | `critical` for an error, `warning` for a warning |
| `domain` | `ha` |
| `source` | `replication-manager` |

Annotations: `summary` (the state's first line), `description`, `monitor`. Both monitors of an active/standby pair send the same labels, so Alertmanager keeps one alert.

Example Alertmanager route:

```yaml
route:
  routes:
    - matchers: [ 'source="replication-manager"', 'severity="critical"' ]
      receiver: dba-oncall
```

| Setting (since) | Description | Default |
|---|---|---|
| `alert-alertmanager` (4.0.1) | Turns the channel on | `false` |
| `alert-alertmanager-url` (4.0.1) | Alertmanager URLs, comma separated: every peer of an Alertmanager cluster gets the alerts. Example `http://am1:9093,http://am2:9093` | `""` |
| `alert-alertmanager-user` (4.0.1) | Basic auth user | `""` |
| `alert-alertmanager-password` (4.0.1) | Basic auth password (secret) | `""` |
| `alert-alertmanager-bearer-token` (4.0.1) | Bearer token, used instead of basic auth when set (secret) | `""` |
| `alert-alertmanager-tls-skip-verify` (4.0.1) | Do not verify the TLS certificate | `false` |
| `alert-alertmanager-timeout` (4.0.1) | Timeout of one post, in seconds | `10` |
| `alert-alertmanager-resend-interval` (4.0.1) | Interval of the resend, in seconds; keep it below Alertmanager's `resolve_timeout` | `60` |

---

## 9.4.3 Telegram

The same alerts as the email (the states of `monitoring-alert-trigger` and the server state changes), with the same text, sent by a Telegram bot to one or more chats.

1. Create a bot with BotFather and copy its token.
2. Add the bot to the group or channel that should receive the alerts.
3. Find the chat id (a group id is negative, a channel's starts with `-100`, or use `@channelname`).
4. Set the three settings below and turn `alert-telegram` on.

| Setting (since) | Description | Default |
|---|---|---|
| `alert-telegram` (4.0.1) | Turns the channel on | `false` |
| `alert-telegram-bot-token` (4.0.1) | The bot token (secret) | `""` |
| `alert-telegram-chat-id` (4.0.1) | Chat ids, comma separated | `""` |
| `alert-telegram-parse-mode` (4.0.1) | Empty for plain text, or `MarkdownV2` (title in bold) | `""` |
| `alert-telegram-api-url` (4.0.1) | Bot API base URL | `https://api.telegram.org` |
| `alert-telegram-timeout` (4.0.1) | Timeout of one send, in seconds | `10` |

---

## 9.4.4 Alert Queues and Timeouts

Every channel sends from its own queue, never from the monitoring loop.

| Setting (since) | Description | Default |
|---|---|---|
| `alert-queue-limit` (4.0.1) | Alerts waiting to be sent, per channel. Above it the oldest waiting alert is dropped | `1000` |
| `alert-send-timeout` (4.0.1) | Timeout of one send to Teams or Pushover, in seconds | `10` |
| `alert-script-timeout` (4.0.1) | The alert script is stopped after this many seconds | `300` |

Two states tell you a channel is in trouble:

| State | Meaning |
|---|---|
| `WARN0360` | A channel dropped alerts in the last 5 minutes, its queue full: it is down or too slow. The state names the channel and how many alerts it dropped |
| `WARN0361` | A channel's last sends failed. The state names the channel and its last error (without its password or token) |

From 4.0.1 the alert script runs outside the monitoring loop and is stopped after `alert-script-timeout`. Its arguments are unchanged: the cluster, the host, the previous state and the new state.
