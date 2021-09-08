<?php
require_once('autoload.php');

$hash = ["title" => 'Create Table', "name" => '', "schema" => '', "flag" => false];

$tables = Table::list();

$columns = [];

if (isset($_GET['table'])) {
    $table = new Table($_GET['table']);
    $hash["title"] = 'Show Table';
    $hash["name"] = $table->getName();
    $hash["schema"] = $table->getSchema();
    $hash["flag"] = true;
    $columns = $table->getColumns();
}
