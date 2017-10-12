---
title: Docker
taxonomy:
    category: docs
---

## Setup Docker

Usage of Ubuntu Server is preferred because of better support for ZFS and docker in that distribution. This is not a requirement if you feel more comfortable with other distributions.

> It's a loss of time to try some Docker deployments on OSX and maybe Windows (not tested) for deployments, docker is not mature enough on those distributions. It looks like it can work but you will quickly hit some network and performance degradations.   

About using docker CE 17.06 & front bridge issue
docker CE 17.06 now loads the br_netfilter kmod blocks the port-to-port bridge transit if netfilter is not configured to allow it.

Disable filtering:

`/etc/udev/rules.d/99-bridge.rules`

```
ACTION=="add", SUBSYSTEM=="module", KERNEL=="br_netfilter", \
      RUN+="/lib/systemd/systemd-sysctl --prefix=/net/bridge"
```

`/etc/sysctl.d/bridge.conf`

```
net.bridge.bridge-nf-call-ip6tables = 0
net.bridge.bridge-nf-call-iptables = 0
net.bridge.bridge-nf-call-arptables = 0
```
