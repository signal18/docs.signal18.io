---
title: OpenSVC Advanced Configuration
taxonomy:
    category: docs
---


## Disks

##### `prov-db-disk-fs` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database type of FS deployment|
| Type | Enum |
| Values | xfs,ext4,zfs,ufs |
| Example | "zfs" |

File system many drivers are available we do test xfs ext4 zfs . Many other drivers like ceph or drbd would need additional testing to be used as extra options may need to be added.

OpenSVC Agent drivers can provision disk resources on many SAN arrays and Cloud API, contact support if you need custom type of disk provisioning for your architecture.

##### `prov-db-disk-pool` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database disk pool type for Micro Services deployment|
| Type | Enum |
| Values | none,zpool,lvm |
| Example | "zpool" |

##### `prov-db-disk-type` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database disk pool type for Micro Services deployment|
| Type | Enum |
| Values | loopback,physical,pool,directory|
| Example | "loopback" |

When loopback instead of a real device the FS path is needed instead of device path

##### `prov-db-disk-device` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database disk device path for Micro Services deployment|
| Type | String |
| Example | "/srv" |

Depends on  `prov-db-disk-type`

physical: define the device /dev/XXXXXXXX
pool: define the pool name
loopback: define  the path to create the loopback file
directory: define the path to create the service_name directory

## network

Network please check availability of the ip before using them , also some opensvc deployemetn can manage range of dhcp ip and DNS entries   

##### `prov-db-net-iface` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database ethernet device. |
| Type | String |
| Example | "br0" |

##### `prov0-db-net-gateway` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database network gateway. |
| Type | String |
| Example | "192.168.1.254" |

##### `prov-db-net-mask` (1.1)

| Item | Value |
| ---- | ----- |
| Description | Database eth device |
| Type | String |
| Example | "255.255.255.0" |
