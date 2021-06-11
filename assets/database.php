<?php

switch ($_GET['type']) {
    case 0:
        unlink('../db/' . $_GET['database']);
        break;
    case 1:
        touch('../db/' . $_GET['database']);
        break;
}

$files = scandir('../db/');
$array = array();
foreach ($files as $file) {
    if (substr($file, -2) == "db") {
        array_push($array, $file);
    }
}
echo json_encode($array);
