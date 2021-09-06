<?php
require_once('autoload.php');

$hash = ["title" => 'Create Table', "name" => '', "schema" => ''];

$tables = Table::list();

$columns = [];

if (isset($_GET['table'])) {
    $table = new Table($_GET['table']);
    $hash["title"] = 'Show Table';
    $hash["name"] = $table->getName();
    $hash["schema"] = $table->getSchema();
    $columns = $table->getColumns();
}
