---
title: Air-Gapped & PCI DSS — Offline License
taxonomy:
    category: docs
---

## 11.9.1 Overview

Some production environments (PCI DSS zones, air-gapped networks) allow **no
outbound network at all**: the replication-manager instance can reach neither
the Signal18 SSO nor any Signal18 service at runtime. For these environments,
plan-gated features (enterprise plugins, arbitration eligibility) are unlocked
by an **offline license**: a small signed file that replication-manager
verifies locally, with no network access, against a public key embedded in
the binary.

The offline license:

- is bound to **one instance identity** (`domain.subdomain.zone`) — copied to
  any other instance, it is rejected;
- carries the **subscription plan** of that instance — the plan loaded is
  exactly the signed one;
- **never expires** on the instance — it is refreshed only when Signal18
  rotates its signing key or the plan changes;
- never blocks monitoring: a missing or invalid license simply leaves the
  instance on the `free` plan and raises a warning.

## 11.9.2 Declaring your instances

You declare each instance from any machine **with** internet access (your
office/work network) — production is never involved in this step.

**Web console (recommended):** open
`https://api.crm.ovh-fr-2.signal18.cloud18.io/console/register-instance`.
Log in with your existing Signal18 account (or create one — a confirmation
email is sent), choose your **domain** (your company name as registered with
Signal18), a **sub-domain** and **zone** of your choice for this instance, and
your subscription plan. The whole onboarding is a single page visit.

On success, two GitLab projects are created automatically in your namespace:

- `{domain}/{subdomain}-{zone}` — your instance's configuration space
- `{domain}/{subdomain}-{zone}-pull` — the Signal18 **delivery** project

Repeat once per instance (each instance has its own sub-domain, its own
projects, and its own license).

> Your domain must match your registered client name at Signal18 — licenses
> are only issued for known clients. Contact support if the registration is
> refused.

## 11.9.3 Retrieving the delivery

Shortly after registration, Signal18 automatically delivers into the `-pull`
project of each instance:

- `plugins/` — the plugin binaries matching your replication-manager version
  and platform;
- `license.json` + `license.sig` — the offline license for this exact
  instance identity and plan.

From your work network, clone the `-pull` project with your Signal18 account
and collect these files.

## 11.9.4 Installing in the air-gapped zone

Bring the replication-manager package and the collected files into your
production zone through your standard deposit / change-management procedure
(everything is Ed25519-signed and re-verified locally by the instance).

On the instance:

1. Install the replication-manager package and place the `plugins/`
   directory in the instance plugin location.
2. Place `license.json` and `license.sig` side by side, then set in the
   configuration:

```toml
cloud18-domain          = "yourcompany"
cloud18-sub-domain      = "prod1"        # identical to the declaration
cloud18-sub-domain-zone = "fr-1"
cloud18-license-file    = "/etc/replication-manager/license.json"
```

3. Start replication-manager. Verify: the subscription plan shows the
   licensed plan, enterprise plugins load, and no license warning is raised
   (a warning means the declared identity does not match the signed one).

No outbound network is ever required in production. To refresh a license
(signing-key rotation, plan change), repeat the retrieval and deposit steps.
