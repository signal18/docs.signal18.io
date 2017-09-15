---
title: False Positive Detection
---




--failover-falsepositive-external                Failover checks that http//master:80 does not reponse 200 OK header
--failover-falsepositive-external-port int       Failover checks external port (default 80)
--failover-falsepositive-heartbeat               Failover checks that slaves do not receive hearbeat (default true)
--failover-falsepositive-heartbeat-timeout int   Failover checks that slaves do not receive hearbeat detection timeout  (default 3)
--failover-falsepositive-maxscale                Failover checks that maxscale detect failed master
--failover-falsepositive-maxscale-timeout int    Failover checks that maxscale detect failed master (default 14)
--failover-falsepositive-ping-counter int        Failover after this number of ping failures (interval 1s) (default 5)
)
