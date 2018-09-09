#!/usr/bin/env bash

sqlite3 sqlite_db/url_shortener < scripts/sqlite.sql

mysql -u root -proot < scripts/mysql.sql