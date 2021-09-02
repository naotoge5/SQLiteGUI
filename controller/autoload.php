<?php

session_start();
require_once(__DIR__ . '/../model/Functions.php');
spl_autoload_register("classLoad");
function classLoad($class)
{
    require_once __DIR__ . "/../model/" . $class . ".php";
}
