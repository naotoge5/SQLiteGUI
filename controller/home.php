<?php
require_once('autoload.php');

$hash = ["title" => 'Create Table', "name" => '', "schema" => ''];

$tables = Table::list();

if (isset($_GET['table'])) {
    $table = new Table($_GET['table']);
    $hash["title"] = 'Show Table';
    $hash["name"] = $table->getName();
    $hash["schema"] = $table->getSchema();
    $columns = $table->getColumns();
    foreach ($columns as $tmp) {
        $constraints = $tmp->getConstraints();
        echo $tmp->getName() . ':';
        foreach ($constraints as $key => $value) {
            if ($value) {
                echo ($key == 'DEFAULT') ? '&nbsp;' . $key . ' = ' . $value : '&nbsp;' . $key;
            }
        }
        echo "<br>";
    }
}
