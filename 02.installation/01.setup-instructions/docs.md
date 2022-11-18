---
title: 'Setup Instructions'
taxonomy:
    category:
        - docs
---

**replication-manager** is a self-contained binary without dependencies that should work on most platforms. We only test builds for Linux, FreeBSD and Mac OS X at this time.

For convenience, we provide packages for Debian/Ubuntu and Centos/RHEL and derivatives.

As of today we build portable binary tarballs, Debian Jessie, Ubuntu, CentOS 6 & 7 packages.

Check out [GitHub Releases](https://github.com/signal18/replication-manager/releases) for official releases.

Development builds are also available on our [Continuous Integration Server](http://ci.signal18.io/mrm/builds/)

### Packages, binary naming convention

Prior to  **replication-manager (2.0)**, a unique binary **replication-manager** was used for monitoring and command line actions. As the result of this design monitoring or command line failover could not be used concurrently.   

Since 2.0 the architecture was split in a more traditional client-server providing multiple binaries:

A server monitoring daemon that comes with different flavors based on included features : **replication-manager-( osc, tst, pro,arm, arb)**.

And a command line client binary **replication-manager-cli** that is requesting actions to the monitoring daemon via a secured protocol.    

**replication-manager-cli** is bundled within the server packages but can as well be installed on it's own for convenience.   

| Package | Flavor       | Description |
| ---- | ------       | ----------- |
| replication-manager-osc | Open Source  | Offers all features excepting provisioning (recommended version). |
| replication-manager-tst | Test         | Offers OSC features and extra features for testing like local service bootstrap, benchmarking... |
| replication-manager-pro | Provisioning | Offers commercial, ready to go cluster provisioning solution. |   
| replication-manager-arm | Provisioning | Offers pro features for on ARM V8  |
| replication-manager-arb | Arbitrator  | Offers arbitration for replication-manager clustering. |

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

`yum install replication-manager-osc`

For last release candidate

```
# /etc/yum.repos.d/signal18.repo
[signal18]
name=Signal18 repositories
baseurl=http://repo.signal18.io/centos/2.1/$releasever/$basearch/
gpgcheck=0
enabled=1
```

#### Debian/Ubuntu

Create the repo file, install the key and install the binaries as such:

```
# change next line with desired version number
version="2.1"
echo "deb [arch=amd64] http://repo.signal18.io/deb $(lsb_release -sc) $version" > /etc/apt/sources.list.d/signal18.list
apt-key adv --recv-keys --keyserver keyserver.ubuntu.com FAE20E50
apt-get update
apt-get install replication-manager-osc
```

### Installation from tarball

Download the archive from [GitHub Releases](https://github.com/signal18/replication-manager/releases).

You can now unpack the tarball in your local directory:

`sudo tar zxvf replication-manager-1.1.2.tar gz -C /usr/local/`

Create a symlink

`sudo ln -s /usr/local/replication-manager-1.1.2 /usr/local/replication-manager`

Copy the systemd or init files in your system:

`sudo cp /usr/local/replication-manager/share/replication-manager.init /etc/init.d/replication-manager`

### Building from source

To build from source, you need to install first the [Go 1.8 binary release](https://golang.org/dl/).

Clone the source on [GitHub](https://github.com/signal18/replication-manager) and follow the steps:
```
git clone https://github.com/signal18/replication-manager.git ~/go/src/github.com/replication-manager
cd ~/go/src/github.com/replication-manager
make bin
```

This will build all the binary releases.

If you want to build the packages, use the following make recipe:
```
make package
```

[For a basic usage you can proceed with the configuration step](/installation/configuration)


### Docker Images

[Docker Hub](https://hub.docker.com/r/signal18/replication-manager/)
