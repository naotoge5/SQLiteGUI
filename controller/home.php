<?php
require_once('autoload.php');

$db = unserialize($_SESSION['db']);
$tables = $db->getTables();
$version = $db->version();
$path = $db->getPath();