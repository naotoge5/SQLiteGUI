<?php

class Table
{
    private string $name;
    private string $schema;
    private $columns;
    private $constraints;

    function __construct($name)
    {
        $this->name = $name;
        $this->setSchema();
        $this->setColumns();
    }

    private function setSchema()
    {
        $db = unserialize($_SESSION['db']);
        $path = $db->getPath();
        $schema = '';
        try {
            $db = new SQLite3($path);
            $db->enableExceptions(true);
            $row = $db->querySingle("SELECT sql FROM sqlite_master WHERE type='table' AND name = '" . $this->name . "'");
            $this->schema = $row;
        } catch (Exception $e) {
            //$flag = Config::errorType($e);
            echo $e;
        } finally {
            $db->close();
        }
    }

    function setColumns()
    {
        $columns = Column::list($this->name);
        foreach ($columns as $tmp) {
            $this->columns[] = new Column($this, $tmp);
        }
    }

    function getName()
    {
        return $this->name;
    }

    function getSchema()
    {
        return $this->schema;
    }

    /**
     * Undocumented function
     *
     * @return Column[]
     */
    function getColumns()
    {
        return $this->columns;
    }

    static function list()
    {
        $db = unserialize($_SESSION['db']);
        $path = $db->getPath();
        $tables = [];
        try {
            $db = new SQLite3($path);
            $db->enableExceptions(true);
            $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name != 'sqlite_sequence'");
            while ($tmp = $result->fetchArray(SQLITE3_ASSOC)) {
                $tables[] = $tmp['name'];
            }
        } catch (Exception $e) {
            //$flag = Config::errorType($e);
        } finally {
            $db->close();
        }
        return $tables;
    }

    static function drop($name)
    {
        $db = unserialize($_SESSION['db']);
        $path = $db->getPath();
        try {
            $db = new SQLite3($path);
            $db->enableExceptions(true);
            $db->exec("DROP TABLE " . $name);
        } catch (Exception $e) {
            //$flag = Config::errorType($e);
        } finally {
            $db->close();
        }
    }
}
