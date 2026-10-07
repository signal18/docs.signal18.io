---
title: Application Templates
taxonomy:
    category: Provisioning
---

Available from **replication-manager 3.1.43**.

An application is deployed next to the database cluster from a **template**: a TOML file
of the template repository (`prov-app-template-repo`, the public
[cloud18-templates](https://github.com/signal18/cloud18-templates)) or of the cluster's local
templates. Add it from the GUI, from the API (`POST /api/clusters/{cluster}/actions/addserver/{name}/{port}/app/{template}`)
or from the MCP tool `app-add`, then provision it.

## 10.6.1 Substitution keys

A template is substituted with the cluster's data before it is loaded. The raw file must be
valid TOML before the substitution, so selectors take bare values.

| key | value |
|---|---|
| `{{app.name}}`, `{{app.port}}`, `{{app.host}}` | the app being added; `host` is its service name in the cluster namespace (`<app>.<cluster>.svc.<orchestrator cluster>`) |
| `{{name}}` | the cluster name |
| `{{config.<field>}}` | a cluster setting, e.g. `{{config.provAppAgents}}`, `{{config.cloud18Domain}}` |
| `{{proxies.0.name}}`, `{{proxies.0.writePort}}`, `{{proxies.#.name}}` | the proxies: the first one, or all names comma-joined |
| `{{apps.#(name==valkey1).host}}` | another app of the cluster, by name: `host`, `port`, `config.deployment.variables.#(name==X).value`, `db.*` |

The namespace glues the apps together: every app answers on its service name, so a
dependent app references another one through its environment variables, e.g.
`REDIS_CACHE = "redis://{{apps.#(name==erpnext-cache).host}}:6379"`. The referenced app must
exist in the cluster when the dependent one is added (not necessarily provisioned).

A template carries plain default values, never free placeholders: an unresolved key refuses
the add with the list of missing keys.

## 10.6.2 A database for the app (`app.db`)

The cluster is the database service, so an app asks it for its own schema:

```toml
app-db-auto-create = true

[[deployment.variables]]
  name = "DB_HOST"
  type = "env"
  value = "{{proxies.0.name}}"
[[deployment.variables]]
  name = "DB_NAME"
  type = "env"
  value = "{{app.db.schema}}"
[[deployment.variables]]
  name = "DB_USER"
  type = "env"
  value = "{{app.db.user}}"
[[deployment.variables]]
  name = "DB_PASSWORD"
  type = "secret"
  value = "{{app.db.password}}"
```

Referencing `{{app.db.*}}` is enough to request the database. At add time the app settings
`app-db-schema` and `app-db-user` default to the app name (lower-case, `a-z0-9_`), and
`app-db-pass` to a generated password stored encrypted; all three can be set by hand in the
app Overview or through the app settings route. At provision, replication-manager creates
the schema, the user for `%` (the configurator disables name resolution, so a hostname-bound
account could never log in; the database is reachable from the cluster network only) and grants
it all privileges on that schema only. Every statement is in the SQL log.

**An existing schema or user is never touched.** The app records what it created
(`app-db-owned`); a provision that finds the schema or the user already present without that
mark is refused, the app shows the state `APPERR008` and nothing is altered. Free the names
with `app-db-schema` / `app-db-user`, or set `app-db-owned` when the objects really belong to
the app. On an owned account the provision re-applies the stored password, and setting `app-db-pass` rotates it at once.
Dropping the app never drops the schema or the user.

Dependent processes share the owner's database: `{{apps.#(name==erpnext-backend).db.user}}`,
`.db.schema`, `.db.password` (as a `secret` variable). A `prov-app-docker-cmd` reads the
password from its environment variable, never from the key.

Settings: `app-db-auto-create` (bool), `app-db-schema`, `app-db-user`, `app-db-pass`,
`app-db-owned` (bool), on the route
`/api/clusters/{cluster}/apps/{app}/settings/actions/set/{setting}/{value}` (or `switch` for
the booleans). The MCP tools `app-add` and `list-cluster-apps` answer the `db` object.

## 10.6.3 Files on the S3 provider (`s3-mounts`)

An app that must share files between its processes mounts a bucket of the cluster's S3
provider app (template `rustfs`, `app-s3-provider = true`, its volume is metered as
archive, BAU) instead of a shared block volume:

```toml
[[deployment.paths]]
  dockerpath = "/home/frappe/frappe-bench/sites/erpnext/public/files"
  name = "path-s3-erp-public"
  srcname = "erp-public"
  srcpath = "mnt/erp-public/"
  srctype = "s3"
  volumename = "erp-mounts"

[deployment.storages]
  [[deployment.storages.s3-mounts]]
    name = "erp-public"
    bucket = "erp-public"
    endpoint = "{{apps.#(name==rustfs1).host}}:9000"
    accesskey = "{{apps.#(name==rustfs1).config.deployment.variables.#(name==RUSTFS_ACCESS_KEY).value}}"
    secretkey = "{{apps.#(name==rustfs1).config.deployment.variables.#(name==RUSTFS_SECRET_KEY).value}}"
    volumename = "erp-mounts"
    volumedir = "mnt/erp-public"
    uid = "1000"
    gid = "1000"
```

A mount sidecar presents the bucket inside the container, as the owner `uid`/`gid` (default
33, www-data). The provider's credentials are read from its variables, MinIO
(`MINIO_ROOT_USER`/`MINIO_ROOT_PASSWORD`), RustFS (`RUSTFS_ACCESS_KEY`/`RUSTFS_SECRET_KEY`)
or AWS names.

## 10.6.4 Start timeout

`prov-app-start-timeout` (default `2m`) is the container start and image pull timeout
written in the service definition, per app or for the cluster.

## 10.6.5 How an app is monitored

An app **with a route** is probed on each route: over TCP for a `tcp` route, with an HTTP `GET` for an `http` or `https`
route. The HTTP check reads `/` and expects `200` unless the route carries a monitor block in the template:

```toml
[[deployment.routes]]
  cname = "{{app.name}}.{{name}}.{{config.cloud18SubDomain}}-{{config.cloud18SubDomainZone}}.{{config.cloud18Domain}}.cloud18.io"
  port = "8080"
  primary = true
  protocol = "https"
  [deployment.routes.monitor]
    path = "/api/method/ping"
    expect-status = "200"
```

Use it when the root of the application is slow (ERPNext's `/` renders a full page through its backend) or answers
something else than 200 to an anonymous request (the S3 API of RustFS answers 403 at its root, its health endpoint
`/health` answers 200). The route is named by its public URL and the destination port behind the gateway
(`https://name -> :8080`): the gateway terminates TLS on 443, the destination port is reached on the cluster network only.

An app **without a route** lives on the cluster network only. By default it is up when `app-port` answers a TCP
connect. A background process that listens on nothing (ERPNext's worker and scheduler) declares
`app-monitor-mode = "ping"`: the monitor sends one ICMP echo to the app host instead, and `app-port` only identifies the
app. The setting is on the app page (Overview, "Monitor mode") and on the API
(`POST /api/clusters/{cluster}/apps/{id}/settings/actions/set/app-monitor-mode/ping`). A failed echo opens the
`APPERR009` state; a monitor that cannot open an ICMP socket says so instead of reporting the app up.

## 10.6.6 Example: ERPNext as linked apps

The `erpnext` templates deploy ERPNext as one container per process, no shared volume:
two valkey apps (`erpnext-cache`, `erpnext-queue`), `erpnext-backend` (owns the database through
`app.db` and the site, creates it on the empty schema at first start), `erpnext-worker`,
`erpnext-scheduler`, `erpnext-websocket`, `erpnext-frontend` (the https route). The site files live on
the S3 provider (`rustfs1`), the database is the cluster's MariaDB through the proxy. Add
them in that order with those names.
