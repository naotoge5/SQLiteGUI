<?php
require_once('autoload.php');

$_SESSION['db'] = DB::bag($_POST['name']);
header('Location: ../route=home');