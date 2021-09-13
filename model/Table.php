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
        $this->setConstraints();
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
        $b = strpos($this->schema, '('); //CREATE TABLE <テーブル名>までを除去
        $e = strrpos($this->schema, ')');
        $b += 1;
        $e -= $b;
        $schema = substr($this->schema, $b, $e); //先頭の"(", 最後尾の")"を除去
        $columns = Column::list($this->name);
        foreach ($columns as $i => $tmp) {
            $row = row\get($schema, $i);
            $this->columns[] = new Column($this, $tmp, $row);
        }
    }
    /**
     * Check, Primary key, Foreign key
     *
     * @return void
     */
    function setConstraints()
    {
        $b = strpos($this->schema, '('); //CREATE TABLE <テーブル名>までを除去
        $e = strrpos($this->schema, ')');
        $b += 1;
        $e -= $b;
        $schema = substr($this->schema, $b, $e); //先頭の"(", 最後尾の")"を除去
        $rows = [];
        $last_column = end($this->columns);
        $tmp = strstr($schema, $last_column->getRow()); //対象の先頭行までを除去
        $tmp = str_replace($last_column->getRow(), '', $tmp);
        do {
            $rows[] = row\get($tmp, 0);
            $tmp = str_replace(end($rows), '', $tmp);
        } while (!empty($tmp));
        foreach ($rows as $row) {
            if (Column::hasPrimaryKey($row)) $this->constraints[] = ["primary_key" => $this->getPrimaryKeyColumns()];
            if (Column::hasCheck($row)) $this->constraints[] = ["check" => Column::getCheckConditions($row)];
            if (Column::hasForeignKey($row)) {
                $tmp = strrpos($row, 'foreign');
                $row = substr($row, $tmp);
                $row = strstr($row, '(');
                $se = row\parenRange($row);
                $conditions = substr($row, 1, $se["end"] - 1);
                $conditions = str_replace(' ', '', $conditions);
                $columns = explode(',', $conditions);
                $this->constraints[] = ["foreign_key" => []];
                $last_key = array_key_last($this->constraints);
                foreach ($columns as $column) {
                    $this->constraints[$last_key]["foreign_key"][] = [$column => Column::getForeignKeyConditions($this->name, $column)];
                }
            };
        }
        var_dump($this->constraints);
    }

    /**
     * primary key
     *
     * @return string[]
     */
    protected function getPrimaryKeyColumns()
    {
        $columns = [];
        $DB = DB::cast(unserialize($_SESSION['db']));
        try {
            $db = $DB->getDB();
            $rows = $db->query("SELECT name, pk FROM pragma_table_info('" . $this->name . "')");
            while ($tmp = $rows->fetchArray(SQLITE3_ASSOC)) {
                if ($tmp['pk']) {
                    $columns[] = $tmp['name'];
                }
            }
        } catch (Exception $e) {
            echo $e;
        } finally {
            $db->close();
        }
        return $columns;
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
