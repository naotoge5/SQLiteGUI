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

function isParenthesesSet($row)
{
    return substr_count($row, '(') === substr_count($row, ')');
}

function h($value)
{
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}
