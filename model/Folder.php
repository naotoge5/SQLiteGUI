<?php

use function PHPSTORM_META\type;

class Folder
{
    static function data($path = null)
    {
        if (is_null($path) || !is_dir($path)) $path = self::path();
        $dir = scandir($path);
        $list_h = preg_grep('/^([^.])/', $dir);
        $list = [];
        foreach ($list_h as $emp) {
            $list[] = $emp;
        }
        $array = explode("/", $path);
        $paths = [];
        foreach ($array as $emp) {
            if ($emp != '') $paths[] = $emp;
        }
        return ["list" => $list, "paths" => $paths];
    }

    private static function path()
    {
        $paths = [];
        $array = explode("/", __DIR__);
        for ($i = 0; $i < count($array) - 2; $i++) {
            if ($array[$i] != '') $paths[] = $array[$i];
        }
        $path = '';
        foreach ($paths as $emp) {
            $path .= '/' . $emp;
        }
        return $path;
    }
}
/* .= '/<u id="folder' . $i . '"data-count="' . $length - $i . '">' . $folders[$i] . '</u>'; */