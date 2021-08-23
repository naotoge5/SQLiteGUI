<?php
require_once('autoload.php');

unset($_SESSION['db']);
header('Location: ../index.php?route=home');