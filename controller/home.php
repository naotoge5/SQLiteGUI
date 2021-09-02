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
    foreach ($columns as $column) {
        echo $column->getName() . ":" . $column->getType() . "<br>----------------<br>";
        foreach ($column->getConstraints() as $key => $value) {
            switch ($key) {
                case 'foreign_key':
                    if ($value) echo $value["table"] . "(" . $value["column"] . ")<br>";
                    break;
                case 'default':
                case 'check':
                    if ($value) echo $value . "<br>";
                    break;

                default:
                    if ($value) echo $key . "<br>";
                    break;
            }
        }
        echo "****************<br>";
    }
}
