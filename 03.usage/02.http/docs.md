---
title: HTTP Client Usage
---

## HTTP Client Usage

Once **replication-manager** is started in monitor mode, it provides an internal HTTP interface to oversee your clusters.

Access it via pointing a web browser to  http://localhost:10001.

By default it is bound to `localhost`. This can be changed via **http-bind-address** variable or command line flag to get remote access on the network.

It looks like this:

![mrmdash](/images/http.png)

> The http dashboard is an angularjs application, it has no protected access for now use creativity to restrict access to it.
Some login protection using http-auth = true can be enable and use the database password giving in the replication-manager config file but it is reported to leak memory when a browser is still connected and constantly refresh the display. We advice not to used it but to protect via a web proxying authentication instead.   

### Sample nginx configuration providing secure access to the dashboard

```
server {
  server_name repman.dashboard;
  location / {
    auth_basic           "Login required";
    auth_basic_user_file conf/htpasswd;
    proxy_pass http://localhost:10001;
  }
}
```
