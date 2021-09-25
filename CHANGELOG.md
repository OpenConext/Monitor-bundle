# Changelog

## 2.0.1
Add support for Symfony 5 and 6. Bump PHP to >= 7.2

## 2.00
SF4 and PHP 7.2 support

## 1.0.5
Take two of improving the DoctrineConnectionHealthCheck

## 1.0.4
Removed RMT from the project

## 1.0.3
The main change in this release is the improvement of the query that is executed by the DoctrineConnectionHealthCheck. It now uses the SchemaManager to grab a table from the available schemas and performs a SELECT * FROM [table] LIMIT 1 on that table.

### 1.0.2  
Updated README.md

### 1.0.1  
Symfony 3 support

### 1.0.0  
Initial release
