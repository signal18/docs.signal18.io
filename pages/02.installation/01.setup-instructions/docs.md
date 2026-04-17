---
title: 'Installation'
taxonomy:
    category:
        - docs
---

**replication-manager** ships as a self-contained binary with no external library dependencies. Choose the method that fits your environment — all paths lead to the same monitoring daemon.

The current stable version is **3.1**. Check [GitHub Releases](https://github.com/signal18/replication-manager/releases) for the latest release or review the [changelog](/change-logs/features31).

| Section | Description |
|---|---|
| [Overview](setup-instructions/overview) | Binary flavors (osc, pro, arb) and distribution types |
| [Embedded Binary](setup-instructions/embedded-binary) | One-command install, no package manager required |
| [Repository](setup-instructions/repository) | RPM and DEB packages via Signal18 apt/yum repo |
| [Docker](setup-instructions/docker) | Official container images with all dependencies bundled |
| [Source](setup-instructions/source) | Build from Go source for custom flavors |
| [Orchestrators](setup-instructions/orchestrators) | Install OpenSVC, Kubernetes, and SlapOS for Pro provisioning |
| [What Was Installed](setup-instructions/what-was-installed) | Files and directories created by each install method |
| [Install Dependencies](setup-instructions/dependencies) | External tools — auto-detected, with optional path overrides |
