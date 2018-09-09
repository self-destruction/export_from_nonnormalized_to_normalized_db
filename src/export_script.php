<?php

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

$mysqli = new mysqli('localhost', 'root', 'root', 'url_shortener');

$spreadsheet = new Spreadsheet();

$spreadsheet->getDefaultStyle()
    ->getFont()
    ->setName('Arial')
    ->setSize(10);

$stmt = $mysqli->query("SHOW tables");
$tables = $stmt->fetch_all();

for ($i = 0; $i < count($tables); ++$i) {
    saveList($spreadsheet, $tables[$i][0], $mysqli, $i);
}

$spreadsheet->removeSheetByIndex($spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet')));

IOFactory::createWriter($spreadsheet, 'Xls')->save('file.xls');

$mysqli->close();


function saveList(&$spreadsheet, string $table_name, $mysqli, $active_sheet) {
    $stmt = $mysqli->query("SELECT * FROM {$table_name}");
    $results = $stmt->fetch_all(MYSQLI_ASSOC);

    $table = [];
    $table[] = array_keys($results[0]);
    foreach ($results as $row) {
        $table[] = $row;
    }

    $spreadsheet->createSheet($active_sheet);

    $spreadsheet->setActiveSheetIndex($active_sheet);

    $spreadsheet->getActiveSheet()->setTitle($table_name);

    $spreadsheet->getActiveSheet()->fromArray($table, null, 'A1');

    foreach(range('A','Z') as $columnID) {
        $spreadsheet->getActiveSheet()->getColumnDimension($columnID)
            ->setAutoSize(true);
    }
}