<?php

$db_sqlite =new \SQLite3("sqlite_db/url_shortener", SQLITE3_OPEN_READWRITE);

$sql = 'SELECT * FROM main_table';

$query = $db_sqlite->query($sql);

$results = [];

while ($res = $query->fetchArray()) {
    foreach ($res as $key => $value) {
        if (!is_int($key)) {
            [$table_name, $column_name] = explode('_', $key, 2);
            if (is_null($value)) {
                $value = 'null';
            } elseif (is_string($value)) {
                $value = '\'' . $value . '\'';
            }
            $results[$table_name][$column_name][] = $value;
        }
    }
}

//var_dump($results);


$db_mysql = \mysqli_connect('localhost', 'root', 'root', 'url_shortener');

if ( mysqli_connect_errno() ) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
}

foreach ($results as $table_name => $row) {
    $sql = "INSERT INTO {$table_name} VALUES ";
    $values = [];

    foreach ($row as $column_name => $column_data) {
        $values[] = '(' . implode(',', $column_data) . ')';
    }

    $sql .= implode(',', $values);

    echo $sql . "\n";
}
