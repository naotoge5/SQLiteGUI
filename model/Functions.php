<?php
function toRow($constraints): string
{
    $row = '';
    foreach ($constraints as $key => $value) {
        if ($value) {
            if ($row !== '') $row .= ', ';
            switch ($key) {
                case 'foreign_key':
                    $row .= $key . ":" . $value["table"] . "-" . $value["column"];
                    break;
                case 'check':
                case 'default':
                    $row .= $key . ":" . $value;
                    break;
                default:
                    $row .= $key;
                    break;
            }
        }
    }
    return htmlspecialchars($row);
}

function startToEnd($row)
{
    $start = [];
    $end = [];
    $tmp = [];
    $array = str_split($row);
    foreach ($array as $key => $val) {
        if (count($tmp) > 0) {
            $last = array_key_last($tmp);
            switch ($val) {
                case "(":
                    $tmp[] = $val;
                    break;
                case ")":
                    if ($tmp[$last] === "(") {
                        unset($tmp[$last]);
                        $end[] = $key;
                    };
                    break;
                case $tmp[$last]:
                    unset($tmp[$last]);
                    $end[] = $key;
                    break;
                case "'":
                case '"':
                    $tmp[] = $val;
                    break;
            }
        } else {
            switch ($val) {
                case "(":
                case "'":
                case '"':
                    $tmp[] = $val;
                    $start[] = $key;
                    break;
            }
        }
    }
    return ["start" => $start, "end" => $end];
}

function h($value)
{
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}
