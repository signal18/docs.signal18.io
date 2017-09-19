---
title: Usage
---
### Chapter 3

# Usage

## Quick Start

Prior to  **replication-manager (2.0)** all features are available inside a single binary command.

Since 2.0 the architecture have been split in multiple flavors of the monitor server daemon: min, osc, tst, pro, arb, that package different features.   

| Item | Flavor       | Description |
| ---- | ------       | ----------- |
| min  | MINIMAL      | Offer limited features but much simple for most basic usage. |
| osc  | OPEN SOURCE  | Offer most features. |
| tst  | TEST         | Offer OPEN SOURCE features and extra features for testing like local service bootstrap, benchmarking... |
| pro  | PROVISIONING | Offer commercial ready to go cluster provisioning solution. |   
| arb  | ARBITRATOR   | Offer clustering solution arbitration. |

![clientserver](/images/clientserver.png)

Start the server via:
```
systemctl start replication-manager   
```
or via init.d
```
/etc/init.d/replication-manager start
```

You can permanently start the monitor via
```
systemctl enable replication-manager   
```

**replication-manager-cli (2.0)** allow command line interaction with the monitor daemon

Command line help can be display via
```
replication-manager-cli --help
replication-manager-cli switchover --help
```

In the next section we will explore how to quicky sitchover using the interactive command line console.
