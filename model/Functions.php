<?php

namespace row;

/**
 * スキーマを行に変換
 *
 * @param string $schema スキーマ
 * @param int $i 何行目か
 * @return string 行
 */
function get($schema, $count)
{
    $row = $schema;
    $end = 0;
    for ($i = 0; $i <= $count; $i++) {
        $row = substr($row, $end);
        $end = end($row) + 1;
    }
    $row = substr($row, 0, $end);
    return $row;
}

/**
 * 
 *
 * @param string $row
 * @return int
 */
function end($row)
{
    $array = str_split($row);
    $tmp = [];
    foreach ($array as $key => $val) {
        if (empty($tmp) and $val === ',') return $key;
        $end = \end($tmp);
        if ($end === $val) {
            array_pop($tmp);
        } else {
            if ($end === "'" or $end === '"') continue;
            switch ($val) {
                case "(":
                case "'":
                case '"':
                    $tmp[] = parenConvert($val);
                    break;
            }
        }
    }
    return strlen($row);
}

/**
 * 行中の丸括弧, クォーテーションの範囲（開始位置, 終了位置）を返す
 *
 * @param string $row
 * @return false|int[]
 */
function parenRange($row)
{
    $array = str_split($row);
    $res = false;
    $tmp = [];
    foreach ($array as $key => $val) {
        $end = \end($tmp);
        if ($end === $val) {
            array_pop($tmp);
            $res["end"] = $key;
            if (\end($tmp) === false) break;
        } else {
            if ($end === "'" or $end === '"') continue;
            switch ($val) {
                case "(":
                case "'":
                case '"':
                    if (empty($tmp)) $res["start"] = $key;
                    $tmp[] = parenConvert($val);
                    break;
            }
        }
    }
    return $res;
}

/**
 * 開始丸括弧を終了丸括弧に変換する
 *
 * @param string $s
 * @return string
 */
function parenConvert($s)
{
    if ($s === "(") $s = ")";
    return $s;
}

/**
 * 行中の丸括弧, クォーテーションを取り除く
 *
 * @param string $row
 * @return string
 */
function removeParenthesesAndQuotation($row): string
{
    $se = parenRange($row);
    while ($se) {
        $end = $se["end"] - $se["start"] + 1;
        $row = substr_replace($row, '', $se["start"], $end);
        $se = parenRange($row);
    }
    return $row;
}

function h($value)
{
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}
