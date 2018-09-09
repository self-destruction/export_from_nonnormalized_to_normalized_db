<?php

$db=new \SQLite3("sqlite_db/url_shortener", SQLITE3_OPEN_READWRITE);

$sql = 'SELECT * FROM main_table';

$results = $db->query($sql);

$rows = [];

while ($res = $results->fetchArray()) {
    $row = [];
    foreach ($res as $key => $value) {
        if (!is_int($key)) {
            $row[$key] = $value;
        }
    }
    $rows[] = $row;
}

var_dump($rows);