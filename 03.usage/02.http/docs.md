---
title: HTTP Client Usage
---

## HTTP Client Usage

**replication-manager**  once started for monitoring your clusters, provide internal HTTP interface:

Access it via pointing a web browser to  http://localhost:10001.

By default it is bind  to `localhost` and this need to be changed to bind address to get remote access on the network.

It looks like this:

![mrmdash](/images/http.png)

> The http dashboard is an angularjs application, it has no protected access for now use creativity to restrict access to it.
Some login protection using http-auth = true can be enable and use the database password giving in the replication-manager config file but it is reported to leak memory when a browser is still connected and constantly refresh the display. We advice not to used it but to protect via a web proxying authentication instead.   
