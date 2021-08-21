<?php
require_once('autoload.php');

$route = '';
if (isset($_GET['route'])) $route = $_GET['route'];
if (!isset($_SESSION['db'])) $route = 'login';

switch ($route) {
    case 'query':
    case 'login':
    case 'home':
    case 'select':
        break;
    default:
        $route = 'home';
}