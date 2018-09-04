# 1.0.4
Removed RMT from the project

# 1.0.3
The main change in this release is the improvement of the query that is executed by the DoctrineConnectionHealthCheck. It now uses the SchemaManager to grab a table from the available schemas and performs a SELECT * FROM [table] LIMIT 1 on that table.

VERSION 1  RELEASE OF A NEW VERSION
===================================

   Version 1.0.0 - Release of a new version
      18/12/2017 16:17  1.0.2  Updated README.md
      11/12/2017 11:16  1.0.1  Symfony 3 support
      07/12/2017 14:41  1.0.0  initial release
