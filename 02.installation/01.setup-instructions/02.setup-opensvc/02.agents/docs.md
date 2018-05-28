---
title: Cluster Agents
taxonomy:
    category: docs
---
## Setup OpenSVC Cluster Agents

Install the agents on the nodes of your provisioning cluster.

A minimal set of tools are advices for better file system support, virtualization of micro services and network debugging purpose.

[Download](https://repo.opensvc.com/)

Supposing the package is installed :

Since Agent version 1.9 the cluster need to be joined

Setup Heartbeat on the donor:

```
# nodemgr set --param hb#1.type --value unicast
# nodemgr get --kw cluster.secret
3665ab8630e011e8ab20525400e412aa
```

On the joiner:

```
# nodemgr daemon join --node=node-1-1.vdc.opensvc.com --secret 3665ab8630e011e8ab20525400e412aa
```

###Debian, Ubuntu:
```  
apt-get install -y python net-tools docker-io psmisc zfsutils-linux system-config-lvm xfsprogs wget
```

###Centos, RHEL:

```  
sudo yum install -y net-tools docker bridge-utils git runc wget bind-utils

# bugfixes
cd /usr/libexec/docker/
sudo ln -s docker-runc-current docker-runc
echo "PATH=\$PATH:/usr/libexec/docker" >>/etc/sysconfig/opensvc
sed -i -e "s/: false/: true/" /etc/oci-register-machine.conf
```

[Installing ZFS](http://lampros.chaidas.com/index.php?controller=post&action=view&id_post=101)

Agent may require to communicate via root together setup a no password ssh access  
```
sudo sed -i -e "s/^PasswordAuthentication no/PasswordAuthentication yes/" /etc/ssh/sshd_config                    
sudo systemctl restart sshd
```


#### Instruct cluster agents where to find the collector  

|SAS Collector | ON-Site Collector |
| ------------ | --------------- |
| nodemgr set --param node.dbopensvc --value https://ci.signal18.io:9443 | nodemgr set --param node.dbopensvc --value https://collector-host:443 |
| ** nodemgr register --user=email --password=hashed_password | nodemgr register --user=replication-manager@localhost.localdomain --password=mariadb |

>** Field user and hash_password found in the account.yaml file send by signal18.io  

#### Instruct cluster agents where to find replication-manager

On the **replication-manager** node start the server with http.  
```
replication-manager-pro --config=/etc/replication-manager/config.toml.sample.opensvc.master-slave-maxscale-docker-ext4-lvm  monitor --http-bind-address=0.0.0.0
```

On each cluster agent:

```
nodemgr set --param node.repocomp --value http://<replication-manager>:10001/repocomp
nodemgr updatecomp
```

You can verify that the agent is discovered by going to the web interface of replication-manager and check the agents tab.


### Using Overlay Networking

Centos & Rehdat requires EPEL

```
yum install epel-release
yum install containernetworking-cni
```

Binary Install
```
cd /tmp
wget https://github.com/containernetworking/cni/releases/download/v0.6.0/cni-amd64-v0.6.0.tgz
wget https://github.com/containernetworking/plugins/releases/download/v0.6.0/cni-plugins-amd64-v0.6.0.tgz
sudo mkdir -p /opt/cni/bin
cd  /opt/cni/bin
tar xvf /tmp/cni-amd64-v0.6.0.tgz
tar xvf /tmp/cni-plugins-amd64-v0.6.0.tgz

mkdir -p /opt/cni/net.d
```

Install one overlay CNI plugin like weave network

```
curl -L git.io/weave -o /usr/local/bin/weave
chmod a+x /usr/local/bin/weave
```

Make sure Docker daemon is started at boot and disable MountFlags:
```
sed -i s/^MountFlags=slave/#MountFlags=slave/ /lib/systemd/system/docker.service
systemctl enable docker
systemctl start docker
```

For each node

```
weave setup
weave launch $(nodemgr get --kw cluster.nodes)

mkdir -p /var/lib/opensvc/cni/net.d/
cat > /var/lib/opensvc/cni/net.d/repman.conf <<EOF
{
    "cniVersion": "0.2.0",
    "name": "repman",
    "type": "weave-net"
}
EOF
```
Make sure the OpenSVC Cluster is defined and joined before the next step
Instruct the OpenSVC agent about cni path:

```
nodemgr set --kw cni.plugins=/usr/libexec/cni  
nodemgr set --kw cni.config=/var/lib/opensvc/cni/net.d
```

Check cni from agent
```
$ nodemgr network ls
repman
$ nodemgr network show --id  repman
node-1-2.vdc.opensvc.com        
`- repman                       
   |- cniVersion                0.2.0     
   |- type                      weavenet  
   `- name                      repman    
```

Check from weave
```
$ weave report
```

Setup the DNS as a docker service on each agent
```
svcmgr create -s odns  --config http://www.opensvc.com/init/static/templates/odns.conf --provision
```

### Upgrade agent version

```
nodemgr set --kw node.repopkg=https://repo.opensvc.com
nodemgr updatepkg
```

### Additional Setup for SAS Collector

```
sudo nodemgr set --param dequeue_actions.schedule --value @1
```

### Additional Setup for ON-Site Collector

Depending on the agent version and the type of security you would like to implement for provisioning.

You should login to your collector and instruct the type of required deployment pull or push for each agent, and also instruct the IP address of the agent for the collector to send information to agent.


Prior to agent 1.9 The pull mode needs some extra settings on the agent node explained here:
[link](https://docs.opensvc.com/agent.architecture.html#the-inetd-entry-point)

The push mode needs some valid ssh configuration for trusted collector key on each agent node:

On Unix systems, if the root account has no RSA key, a 2048 bits RSA key is generated by the package post-install. A production node key must be trusted on all nodes of its cluster (PRD and DRP), whereas the keys of disaster recovery servers must not be trusted by any production nodes. This setup is used for rsync file transfers and remote command execution.
