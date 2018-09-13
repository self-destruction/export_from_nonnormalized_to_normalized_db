<?php

$db_sqlite =new \SQLite3("sqlite_db/url_shortener", SQLITE3_OPEN_READWRITE);

$sql = 'SELECT * FROM main_table';

$query = $db_sqlite->query($sql);

$results = [];
$i = 0;
while ($res = $query->fetchArray(SQLITE3_ASSOC)) {
    foreach ($res as $key => $value) {
        [$table_name, $column_name] = explode('_', $key, 2);
        if (is_null($value)) {
            $value = 'null';
        } elseif (is_string($value)) {
            $value = '\'' . $value . '\'';
        }
        $results[$table_name][$i][] = $value;
    }
    $i++;
}

// сортируем, чтобы ограничния не повлияли при добавлении в бд
$results = [
    'user' => $results['user'],
    'url' => $results['url'],
    'click' => $results['click'],
    'promocode' => $results['promocode'],
    'redeem' => $results['redeem']
];

//var_dump($results);



$db_mysql = new mysqli('localhost', 'root', 'root', 'url_shortener');

if (mysqli_connect_errno()) {
    printf("Не удалось подключиться: %s\n", mysqli_connect_error());
    exit();
}

foreach ($results as $table_name => $row) {
    foreach ($row as $column_data) {
        //TODO insert ignore. ошибки могут быть не только из-за одинаковых айдишников
        // у меня одинаковые айдишники не вставятся
        $sql = "INSERT INTO {$table_name} VALUES ";
        $sql .= '(' . implode(',', $column_data) . ')';

        echo $sql . "\n";

        if ($db_mysql->query($sql) !== TRUE) {
            echo "Error: {$db_mysql->error}\n";
        }
    }
}

$db_mysql->close();
