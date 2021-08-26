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
        $tables = [];
        try {
            $db = new SQLite3($path); //相対パスでええのか
            $db->enableExceptions(true);
            $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name != 'sqlite_sequence'");
            while ($table = $result->fetchArray(SQLITE3_ASSOC)) {
                $tables[] = $table['name'];
            }
        } catch (Exception $e) {
            //$flag = Config::errorType($e);
        } finally {
            $db->close();
        }
        $this->tables = $tables;
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
