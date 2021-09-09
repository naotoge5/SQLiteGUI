<?php

class Table
{
    private string $name;
    private string $schema;
    /**
     * @var Column[]
     */
    private $columns;
    private $constraints = [];

    function __construct($name)
    {
        $this->name = $name;
        $this->setSchema();
        $this->setColumns();
        //$this->setConstraints();
    }

    private function setSchema()
    {
        $DB = DB::cast(unserialize($_SESSION['db']));
        $schema = '';
        try {
            $db = $DB->getDB();
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

    function setConstraints()
    {
        $last = array_key_last($this->columns);
        $last_column = $this->columns[$last];
        $tmp = strstr($this->schema, '('); //CREATE TABLE <テーブル名>までを除去
        $schema = substr($tmp, 1, -1); //先頭の"(", 最後尾の")"を除去
        $tmp = strstr($schema, $last_column->getName() . ' ' . $last_column->getType()); //対象の先頭行までを除去
        $last_column_end = Column::rowEnd($tmp);
        if (strlen($tmp) === $last_column_end) { //スキーマの最後尾（文字数）と最終カラムの行の最後尾が同じであれば表制約はなし
            # code...
        } else {
            $tmp = substr($tmp, $last_column_end + 1);
            echo $tmp;
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
        $DB = DB::cast(unserialize($_SESSION['db']));
        $tables = [];
        try {
            $db = $DB->getDB();
            $result = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name != 'sqlite_sequence'");
            while ($tmp = $result->fetchArray(SQLITE3_ASSOC)) {
                $tables[] = $tmp['name'];
            }
        } catch (Exception $e) {
        } finally {
            $db->close();
        }
        return $tables;
    }

    static function find($name)
    {
        $DB = DB::cast(unserialize($_SESSION['db']));
        $flag = 0;
        try {
            $db = $DB->getDB();
            $flag = $db->querySingle("SELECT COUNT(name) FROM sqlite_master WHERE type='table' AND name = '" . $name . "'");
        } catch (Exception $e) {
            echo $e;
        } finally {
            $db->close();
        }
        return $flag;
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @return bool
     */
    static function drop($name): bool
    {
        $DB = DB::cast(unserialize($_SESSION['db']));
        $res = true;
        try {
            $db = $DB->getDB();
            $db->exec("DROP TABLE " . $name);
        } catch (Exception $e) {
            Config::setErrorMessage($e->getMessage());
            $res = false;
        } finally {
            $db->close();
        }
        return $res;
    }
}
