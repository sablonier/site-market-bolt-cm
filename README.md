Bolt Extensions Market Place Repository
=======================================

Extensions & Theme
------------------

Core functionality is provided by the [`bolt/marketplace`](https://github.com/bolt/marketplace-core)
extension.

Theme is provided by the [`bolt/marketplace-theme`](https://github.com/bolt/marketplace-theme).


Running Scheduled Jobs
----------------------

Dump package repository JSON from database

```
./app/nut package:dump
```


Rebuild JSON data for all packages:

```
./app/nut package:build 
```

Rebuild a single package's JSON data

```
./app/nut package:build author/pachage

```

Flushing the hook generated update queue:

```
./app/nut package:queue
```

Running extension tests

```
./app/nut package:extension-tester [--wait=n] [--protocol=http] [--protocol=https] [--private-key=~/.ssh/id_rsa]
```
