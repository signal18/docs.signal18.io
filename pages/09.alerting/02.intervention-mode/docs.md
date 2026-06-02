---
title: Intervention Mode (Mute)
taxonomy:
    category: docs
---

## 9.3.1 Intervention Mode

> **Available since:** replication-manager **v3.1.26**

Intervention mode — also called **Mute** — silences all alert notifications for a cluster or globally across all clusters during planned maintenance. Unlike the scheduled [alert blackout window](/alerting/configuration-guide#9-2-3-alert-blackout-window) which uses a fixed cron expression, intervention mode is started and stopped on demand from the GUI or API, with optional scheduling and auto-unmute.

When intervention mode is active:
- **Email** alerts are suppressed
- **Slack** notifications are suppressed (including state RESOLV/OPENED diffs)
- **Microsoft Teams** notifications are suppressed (including state diffs)
- **Pushover** push notifications are suppressed
- **Cloud18 / Mattermost** alert forwarding is suppressed (ALERT, START, ALERTOK channels)
- **ALERT**, **START**, and **ALERTOK** log events skip all external channels

All suppressed notifications are counted and shown as "blocked notifications" in the GUI badge and intervention history.

Monitoring, state detection, failover, and switchover continue to operate normally — only the notification delivery is muted.

**Exception — intervention start/stop notifications are always delivered:**

| Event | Level | When sent | Message |
|---|---|---|---|
| Scheduled | ALERT | Immediately when scheduled | "Intervention scheduled by {user} at {time}: {reason} — notifications will be muted" |
| Started | ALERT | Before muting activates | "Intervention started by {user}: {reason} (scope: {scope}) — notifications are now muted" |
| Ended | ALERTOK | After unmuting | "Intervention ended by {user}: {reason}. Duration: {duration}. Suppressed alerts: {count} — notifications resumed" |

These three notifications bypass the mute so that all team members are informed when maintenance begins and ends, and how many alerts were silenced.

---

## 9.3.2 Warning States

Two warning codes track intervention status in the cluster state machine:

| Code | Meaning |
|---|---|
| **WARN0172** | Intervention scheduled at a future time — not yet active |
| **WARN0173** | Intervention active — notifications muted |

These states appear in the cluster's warning badge and are visible in the GUI alongside other warnings. They are set on every monitoring tick while the condition holds and cleared automatically when the intervention starts (WARN0172) or ends (WARN0173).

---

## 9.3.3 Scope

| Scope | Effect | Started from |
|---|---|---|
| **Cluster** | Mutes a single cluster | Cluster view → Mute badge |
| **Global** | Mutes all clusters simultaneously | Home view (no cluster selected) → Mute badge |

A global intervention starts a separate intervention on every cluster with `scope: "global"`. Ending a global intervention ends all of them at once via the "Close All" button.

---

## 9.3.4 GUI

The **Mute** badge appears in the navbar next to Blockers and Warnings:

- **Inactive**: teal badge with a crossed-bell icon — click to open the intervention panel
- **Active**: red blinking badge showing the count of blocked notifications

Clicking the badge opens the **Intervention Panel** which shows:

- **Active intervention**: reason, user, start time, duration, blocked notification count, and a Close (or Close All) button
- **Start Intervention** button (when no intervention is active) — opens the Intervention Modal
- **Last 5 interventions**: history with user, time, duration, and blocked count per entry

### 9.3.4.1 Starting an Intervention

The Intervention Modal requires:

| Field | Description | Default |
|---|---|---|
| **Description** | Free-text reason for the intervention (required) | — |
| **Start time** | When the intervention begins | Now |
| **Estimated duration** | How long the maintenance is expected to last | 30 minutes |
| **Auto-unmute** | Automatically end the intervention after the estimated duration | Checked |

- If the start time is **now** (or within 30 seconds), the intervention starts immediately
- If the start time is **in the future**, the intervention is scheduled — it will activate automatically when the time arrives (WARN0172 is set in the meantime)
- The button text changes between "Start Intervention" and "Schedule Intervention" accordingly

### 9.3.4.2 Ending an Intervention

An intervention ends when:
1. The operator clicks **Close** (cluster) or **Close All** (global) in the intervention panel
2. The **auto-unmute** timer expires (if enabled)
3. replication-manager is restarted — active interventions are restored from disk (`interventions.json`)

---

## 9.3.5 Intervention History

All interventions are persisted to `{working-dir}/interventions.json` per cluster. The file stores:

- **Current** active intervention (restored on restart)
- **Pending** scheduled intervention (restored on restart)
- **History** of all past interventions

The history records who started the intervention, why, when it started and ended, and how many notifications were blocked. This provides an audit trail for maintenance activities.

The GUI shows the last 5 interventions in the panel. The full history is available in the JSON file.

---

## 9.3.6 API

### 9.3.6.1 Cluster-Level

```
POST /api/clusters/{clusterName}/actions/intervention-start
```

Request body (JSON):
```json
{
  "reason": "Rolling restart for v3.1.26",
  "startAt": "2026-06-02T14:00:00Z",
  "endAt": "2026-06-02T15:00:00Z"
}
```

| Field | Required | Description |
|---|---|---|
| `reason` | Yes | Free-text description of the maintenance |
| `startAt` | No | ISO 8601 start time. Omit or set to now for immediate start. Future time schedules the intervention. |
| `endAt` | No | ISO 8601 auto-unmute time. Omit for manual-only unmute. |

```
POST /api/clusters/{clusterName}/actions/intervention-end
```

Ends the active intervention on the specified cluster.

### 9.3.6.2 Global

```
POST /api/actions/intervention-start
```

Same request body as cluster-level. Starts an intervention on **all clusters** with `scope: "global"`.

```
POST /api/actions/intervention-end
```

Ends the active intervention on all clusters.

---

## 9.3.7 Difference from Alert Blackout Window

| | Alert Blackout (`scheduler-alert-disable`) | Intervention Mode (Mute) |
|---|---|---|
| **Trigger** | Cron schedule | On-demand (GUI or API) |
| **Scope** | Per-cluster config | Per-cluster or global |
| **Duration** | Fixed (`scheduler-alert-disable-time`) | Operator-defined with optional auto-unmute |
| **History** | No audit trail | Full history with user, reason, blocked count |
| **Warning state** | No | WARN0172 (scheduled), WARN0173 (active) |
| **Available since** | v2.1.0 | v3.1.26 |

Both can be active simultaneously. When either is active, notifications are suppressed.
