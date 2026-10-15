---
title: "Events"
taxonomy:
    category: docs
---

Scheduled events (`CREATE EVENT`) run SQL on a timer. In a replicated cluster they are usually meant to run on the primary only. **replication-manager** shows, for every server of a cluster:

- whether its event scheduler is ON or OFF,
- the status of every event it holds,
- when an event runs and its definition, when you ask for it.

This view is read-only: it reports what the servers hold and changes nothing.

## 6.2.5.1 Configuration

##### `monitoring-event-status` (3.1.43)

| Item | Value |
| ---- | ----- |
| Description | Expose the read-only Events section, definitions API and CLI getter |
| Type | boolean |
| Default Value | true |

When disabled, the Events section is not shown and the event definitions API and CLI call are refused (HTTP 403). This gates only those user/API surfaces: event-status monitoring continues with the same monitoring work.

The setting can also be switched from **Settings > Monitoring > Monitoring Event Status**.

Reading the Events section requires the `db-show-status` grant.

## 6.2.5.2 The Events section

Open a cluster, go to the **Schema** tab and expand the **Events** section (below the tables, above Cluster Logs).

### Event scheduler

The badges next to **Event scheduler** show each server's `event_scheduler`, with its role (master or replica). The same information is under each server's column heading.

### Event status per server

One row per event, one column per server (the master first). Each cell shows the event's status on that server, in the server's own words:

| Shown | Meaning |
| ----- | ------- |
| `ENABLED` | the event runs when the server's scheduler is ON |
| `DISABLED` | the event never runs |
| `SLAVESIDE_DISABLED` (MariaDB) / `REPLICA_SIDE_DISABLED` (MySQL, Percona) | the event came from replication and does not run on this replica; the normal state on a replica |
| Missing | the server does not have this event |

`SLAVESIDE_DISABLED` and `REPLICA_SIDE_DISABLED` are the same state: MariaDB and MySQL name it differently.

### Finding events in a long list

The table shows 20 events per page (the page size can be changed below it). To narrow it:

- **Schema**: only the events of one schema;
- **Status**: events that are ENABLED, DISABLED or replica-side disabled on at least one server, or missing on at least one server;
- **Search**: part of a schema or event name;
- **Show observations only**: only the events with an observation.

The line above the table tells how many events match.

### Observations

The **Observations** column and the banner above the table point out differences between servers:

- **missing**: another server has the event, this one does not. This can be intended (an event created on one server only), so it is reported, not raised as an alert.
- **ENABLED on a replica (scheduler OFF)**: the event does not run now, but would as soon as the replica's scheduler is turned on.
- **ENABLED on a replica (scheduler ON)**: the event runs on the replica. It is shown in red and turns the banner into a warning, because it usually means the event writes on a replica.

A replica with its scheduler ON is not a problem by itself: events that are `SLAVESIDE_DISABLED` / `REPLICA_SIDE_DISABLED` do not run there.

### When an event runs, and its definition

Click an event's status in a server's column to read it on that server. Only that event is read, from the server at that moment (it is not part of the monitoring). The window shows:

- **Status** on that server;
- **Schedule**: when it runs, e.g. `Once, at 2030-06-01 00:00:00` or `Every 1 DAY, starting 2030-01-01 00:00:00, until 2031-01-01 00:00:00`;
- **Last executed** (`never` if it has not run on that server);
- **On completion**: whether the event is kept or dropped after its last run;
- **Time zone**, **Definer** and **Comment**;
- the event body (SQL), with a **Copy to clipboard** button.

Status and scheduler state come from the monitoring and refresh on every monitoring tick.

## 6.2.5.3 API and command line

```
GET /api/clusters/{clusterName}/servers/{serverName}/events
GET /api/clusters/{clusterName}/servers/{serverName}/events?schema=<schema>
GET /api/clusters/{clusterName}/servers/{serverName}/events?schema=<schema>&name=<event>
```

Without `name`, the API returns a bounded page of event metadata: schema, name, definer, schedule and `definitionBytes`. The SQL `definition` body is not included in a list response. `X-Total-Count` reports the number of events matching the request. Use `limit=<n>&offset=<n>` to request a page; an omitted limit, or `limit=0`, uses the configured maximum.

With both `schema` and `name`, the API returns that one event with its SQL definition and schedule (`eventType`, `executeAt`, `intervalValue`, `intervalField`, `starts`, `ends`, `onCompletion`, `lastExecuted`, `timeZone`, `comment`). `name` without `schema` is refused (HTTP 400).

The list limit is controlled by `monitoring-event-status-max-definitions` (default `100`). A named definition body is controlled by `monitoring-event-status-max-definition-bytes` (default `1048576`, or 1 MiB). A larger definition is refused with HTTP 413; it is never silently truncated.

```
replication-manager-cli server --cluster=<cluster> --id=<server id> --get=events
```

follows the bounded API pages and prints all event metadata for that server as one JSON array. It does not keep the complete list in memory while it writes.

Pages are a live view, not a transaction snapshot. If an event is created, dropped or renamed while a client follows offsets, a later page can change, skip or repeat an event.

Both need the `db-show-status` grant and are refused when `monitoring-event-status` is off.

## 6.2.5.4 Read-only

The Events section, the API endpoint and the CLI getter only read. They do not turn the event scheduler on or off and do not change an event's status.
