<?php
class DB
{
    private string $name;
    private string $path;

    function __construct(string $path)
    {
        $this->path = $path;
        $array = explode("/", $path);
        $this->name = str_replace('.db', '', $array[count($array) - 1]);
    }

    function getName()
    {
        return $this->name;
    }

    function getDB()
    {
        $db = new SQLite3($this->path);
        $db->enableExceptions(true);
        return $db;
    }

    static function cast($obj): self
    {
        return $obj;
    }

    function version()
    {
        $row = '';
        try {
            $db = $this->getDB();
            $row = $db->querySingle("SELECT sqlite_version()");
        } catch (Exception $e) {
            //$flag = Config::errorType($e);
        } finally {
            $db->close();
        }
        return $row;
    }
}
