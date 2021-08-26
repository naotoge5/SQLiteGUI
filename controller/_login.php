<?php
require_once('autoload.php');

$db = new DB($_GET['path']);
$_SESSION['db'] = serialize($db);
header('Location: ../index.php?route=home');