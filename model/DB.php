<?php
class DB
{
    private $path;
    private $name;
    private $tables;

    /**
     * データベース接続に必要なセット
     *
     * @param string $path
     */
    function __construct(string $path)
    {
        $this->path = $path;
        $array = explode("/", $path);
        $this->name = substr($array[count($array) - 1], 0, -3);
        $this->name = str_replace('.db', '', $array[count($array) - 1]);
    }

    function getPath()
    {
        return $this->path;
    }

    function getName()
    {
        return $this->name;
    }

    function getTables()
    {
        return $this->tables;
    }

    function version()
    {
        $row = '';
        try {
            $db = new SQLite3($this->path); //相対パスでええのか
            $db->enableExceptions(true);
            $row = $db->querySingle("SELECT sqlite_version()");
        } catch (Exception $e) {
            //$flag = Config::errorType($e);
        } finally {
            $db->close();
        }
        return $row;
    }
}
