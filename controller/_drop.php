<?php
require_once('autoload.php');

$table = $_POST['drop-table-name'];
$res = Table::drop($table);
$_SESSION["flash"] = ($res) ? 'Dropped Table [' . $table . ']' : Config::getErrorMessage();
header('Location: ../index.php?route=home');
