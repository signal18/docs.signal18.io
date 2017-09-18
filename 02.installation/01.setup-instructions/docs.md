---
title: Setup Instructions
taxonomy:
    category: docs
---

**replication-manager** is a self-contained binary without dependencies that should work on most platforms. We only test builds for Linux, FreeBSD and Mac OS X at this time.

For convenience, we provide packages for Debian/Ubuntu and Centos/RHEL and derivatives.

As of today we build portable binary tarballs, Debian Jessie, Ubuntu, CentOS 6 & 7 packages.

Check https://github.com/signal18/replication-manager/releases for official releases.

Continuous integration builds are available on http://ci.signal18.io/mrm/builds/

### Installation from our repository

#### CentOS/RHEL

Configure the repository as such:

```
# /etc/yum.repos.d/signal18.repo
[signal18]
name=Signal18 repositories
baseurl=http://repo.signal18.io/centos/$releasever/$basearch/
gpgcheck=0
enabled=1
```
then

`yum install replication-manager`

You can install a specific version if it's present in the repo, e.g.

`yum install replication-manager-2.0.0-dev`

#### Debian/Ubuntu

Create the apt source file as such:

`echo "deb [arch=amd64] http://repo.signal18.io/deb $(lsb_release -sc) main" > /etc/apt/sources.list.d/signal18.list`

Update the sources:

`apt-get update`

You can now install the package:

`apt-get install replication-manager`

### Installation from tarball

Download the archive from [GitHub Releases](https://github.com/signal18/replication-manager/releases).

You can now unpack the tarball in your local directory:

`sudo tar zxvf replication-manager-1.1.2.tar gz -C /usr/local/`

Create a symlink

`sudo ln -s /usr/local/replication-manager-1.1.2 /usr/local/replication-manager`

Copy some systemd or init files in your system:

`sudo cp /usr/local/replication-manager/share/replication-manager.init /etc/init.d/replication-manager`
