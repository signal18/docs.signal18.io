---
title: HTTP Client Usage
---

## HTTP Client Usage

*replication-manager**  when started to monitor your clusters, provide an internal HTTP server:

it is accessible on http://localhost:10001.

By default it is bind only to `localhost` and need to be changed to bind address to get access on the network.

It looks like this:

![mrmdash](/images/http.png)

> The http dashboard is an angularjs application, it has no protected access for now use creativity to restrict access to it.
Some login protection using http-auth = true can be enable and use the database password giving in the replication-manager config file but it is reported to leak memory when a browser is still connected and constantly refresh the display. We advice not to used it but to protect via a web proxying authentication instead.   
