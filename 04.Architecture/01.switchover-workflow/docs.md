---
title: Switchover Workflow
---
### Switchover Workflow

**replication-manager** start with some checks to cancel dangerous switchover, it looks for some excessive long WRITE queries ,and  excessive long transactions and cancel the switchover in such case. Switchover also follow the state machine blocking states so that miss working replication or wrong topology also do not enable switchover.  

During failover **replication-manager** prevents additional writes via setting READ_ONLY flag and FTWRL on the old leader, if routers are still sending WRITE transactions, they can still pile-up until timeout. To prevent this, **replication-manager**  is killing those blocked transactions.


Some additional caution to make sure that piled writes do not happen is that **replication-manager**  decrease max_connections to the server to 1 and consume last possible connection by not killing himself. This works but to avoid a scenario where a node is left in such state where the database cannot be connected anymore (killing replication-manager in this critical section), we advise using extra port provided with MariaDB or MySQL pool of threads feature:

MariaDB Thread Pool
```
thread_handling = pool-of-threads  
extra_port = 3307   
extra_max_connections = 10
```   

To better protect consistency it is strongly advised to disable *SUPER* privilege on users that perform writes, such as the The MaxScale user used with Read-Write split module is instructed to monitor the replication lag via writing in the leader, privileges should be lower as describe in Maxscale settings   

### Switchover Events Graph

![switchover](/images/switchover.png)
