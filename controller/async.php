<?php
require_once('autoload.php');

$function = $_POST['function'];
$value = $_POST['value'];
echo $function($value);

function Folder_data($path)
{
    $path = empty($path) ? null : $path;
    return json_encode(Folder::data($path));
}
