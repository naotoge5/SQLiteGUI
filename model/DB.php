<?php
class DB
{
    /**
     * データベース接続に必要なセット
     *
     * @param string $path
     * @return array
     */
    static function bag(string $path): array
    {
        $array = explode("/", $path);
        $name = substr($array[count($array) - 1], 0, -3);
        return ["name" => $name, "path" => $path];
    }
}
