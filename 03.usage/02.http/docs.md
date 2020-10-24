---
title: HTTP Client Usage
taxonomy:
    category: docs
---

## HTTP Client Usage

Once **replication-manager** is started in monitor mode, it provides an internal HTTP server to oversee your clusters.

Access it via pointing a web browser to  http://localhost:10001.

By default it is bound to `localhost`. This can be changed via **http-bind-address** variable or command line flag to get remote access on the network.

Using **replication-manager 2.1**,  secured connection is open via pointing a web browser to  https://replication-manager-host:10005/.


It looks like this:

![mrmdash](/images/http.png)


> Starting with **replication-manager 2.1** the http client can be secured via navigation to the https api port, this offer encryption on the wire and JWT tokens for identity tracking. The default credential is the apiUser credential so admin:repman but can be change per cluster.    



> Previously to  **replication-manager 2.1** The http dashboard  has no protected access, use creativity to restrict access to it.
Some login protection using http-auth = true is deprecate. We advice not to used it but to protect via a web proxying authentication instead.   



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
