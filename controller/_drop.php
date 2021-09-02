<?php
require_once('autoload.php');

$table = $_POST['delete-table-name'];
Table::drop($table);
$_SESSION["flash"] = 'Dropped Table [' . $table . ']';
header('Location: ../index.php?route=home');
