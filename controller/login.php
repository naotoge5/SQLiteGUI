<?php
require_once('autoload.php');

$_SESSION['db'] = DB::bag($_GET['name']);
header('Location: ../index.php?route=home');