<?php

class Column
{
    private Table $parent;
    private string $name;
    private string $type;
    private $constraints;

    protected const COLUMN_CONSTRAINT = ['autoincrement', 'check', 'primary key', 'unique'];
    protected const TABLE_CONSTRAINT = ['primary key', 'unique', 'check', 'foreign key'];

    function __construct(Table $parent, $name)
    {
        $this->parent = $parent;
        $this->name = $name;
        $this->setType();
        $this->setConstraints();
    }

    protected function setType()
    {
        $db = unserialize($_SESSION['db']);
        $path = $db->getPath();
        try {
            $db = new SQLite3($path);
            $db->enableExceptions(true);
            $row = $db->querySingle("SELECT type FROM pragma_table_info('" . $this->parent->getName() . "') WHERE name = '" . $this->name . "'");
            $this->type = $row;
        } catch (Exception $e) {
            //$flag = Config::errorType($e);
        } finally {
            $db->close();
        }
    }

    protected function setConstraints()
    {
        $db = unserialize($_SESSION['db']);
        $path = $db->getPath();
        try {
            $db = new SQLite3($path);
            $db->enableExceptions(true);
            $row = $db->querySingle("SELECT [notnull], [dflt_value] FROM pragma_table_info('" . $this->parent->getName() . "') WHERE name = '" . $this->name . "'", true);
            $schema_row = $this->toRow();
            $this->constraints['primary_key'] = $this->hasPrimaryKey($schema_row);
            $this->constraints['autoincrement'] = $this->hasAutoIncrement($schema_row);
            $this->constraints['not_null'] = ($row['notnull']) ? true : false;
            $this->constraints['unique'] = $this->hasUnique($schema_row);
            $this->constraints['default'] = ($row['dflt_value'] === null) ? false : $row['dflt_value'];
            $this->constraints['check'] = $this->hasCheck($schema_row);
            if ($this->constraints['check']) {
                $this->constraints['check'] = $this->getCheckConditions($schema_row);
            }
            $this->constraints['foreign_key'] = $this->hasForeignKey($schema_row);
            if ($this->constraints['foreign_key']) {
                $row = $db->querySingle("SELECT [table], [to] FROM pragma_foreign_key_list('" . $this->parent->getName() . "') WHERE [from] = '" . $this->name . "'");
                $this->constraints['foreign_key'] = ["table" => $row['table'], "column" => $row['to']];
            }
        } catch (Exception $e) {
            echo $e;
        } finally {
            $db->close();
        }
    }

    /**
     * （複合でない）primaryキーを持つかどうか
     * 
     * @param string $row
     * @return boolean
     */
    protected function hasPrimaryKey(string $row): bool
    {
        return (strpos($row, 'primary key') || strpos($row, 'PRIMARY KEY')) ? true : false;
    }

    /**
     * （複合でない）uniqueキーを持つかどうか
     *
     * @param string $row
     * @return boolean
     */
    protected function hasUnique(string $row): bool
    {
        return (strpos($row, 'unique') || strpos($row, 'UNIQE')) ? true : false;
    }

    /**
     * check制約を持つかどうか
     *
     * @param string $row
     * @return boolean
     */
    protected function hasCheck(string $row): bool
    {
        return (strpos($row, 'check') || strpos($row, 'CHECK')) ? true : false;
    }

    /**
     * （複合でない）foreignキーを持つかどうか
     *
     * @param string $row
     * @return boolean
     */
    protected function hasForeignKey(string $row): bool
    {
        return (strpos($row, 'references') || strpos($row, 'REFERENCES')) ? true : false;
    }

    /**
     * autoincrementかどうか
     *
     * @param string $row
     * @return boolean
     */
    protected function hasAutoIncrement(string $row): bool
    {
        return (strpos($row, 'autoincrement') || strpos($row, 'AUTOINCREMENT')) ? true : false;
    }

    /**
     * check制約の条件を取得
     *
     * @param string $row
     * @return boolean
     */
    protected function getCheckConditions(string $row): string
    {
        $row = strstr($row, 'check');
        $row = strstr($row, '(');
        $end = mb_strlen($row);
        $array = str_split($row);
        $tmp = [];
        $flag = false;
        foreach ($array as $key => $val) {
            if (empty($tmp)) {
                switch ($val) {
                    case "(":
                    case "'":
                    case '"':
                        $tmp[] = $val;
                        break;
                }
            } else {
                $last = array_key_last($tmp);
                if ($tmp[$last] === "(") {
                    switch ($val) {
                        case ")":
                            $end = $key;
                            $flag = true;
                            break;
                        case "(":
                        case "'":
                        case '"':
                            $tmp[] = $val;
                            break;
                    }
                } elseif ($tmp[$last] === $val) {
                    unset($tmp[$last]);
                }
            }
            if ($flag) break;
        }
        $row = substr($row, 1, $end - 1);
        return $row;
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    protected function toRow(): string
    {
        $schema = $this->parent->getSchema();
        $tmp = strstr($schema, '(');
        $schema = substr($tmp, 1, -1);
        $tmp = strstr($schema, $this->name . ' ' . $this->type);
        $end = $this->rowEnd($tmp);
        $row = substr($tmp, 0, $end);
        return $row;
    }

    protected function rowEnd($value)
    {
        $end = mb_strlen($value);
        if ($end === 1) return false;
        $array = str_split($value);
        $tmp = [];
        foreach ($array as $key => $val) {
            if (empty($tmp)) {
                if ($val === ",") {
                    $end = $key;
                    break;
                }
                switch ($val) {
                    case ")":
                    case "(":
                    case "'":
                    case '"':
                        $tmp[] = $val;
                        break;
                }
            } else {
                $last = array_key_last($tmp);
                if ($tmp[$last] === "(") {
                    switch ($val) {
                        case ")":
                            unset($tmp[$last]);
                            break;
                        case "(":
                        case "'":
                        case '"':
                            $tmp[] = $val;
                            break;
                    }
                } elseif ($tmp[$last] === $val) {
                    unset($tmp[$last]);
                }
            }
        }
        return $end;
    }

    function getName()
    {
        return $this->name;
    }

    function getType()
    {
        return $this->type;
    }

    function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * テーブルが持つカラムのリスト
     *
     * @param string $name Table Name
     * @return array Column List(name)
     */
    static function list($name): array
    {
        $db = unserialize($_SESSION['db']);
        $path = $db->getPath();
        $columns = [];
        try {
            $db = new SQLite3($path);
            $db->enableExceptions(true);
            $result = $db->query("PRAGMA table_info(" . $name . ")");
            while ($tmp = $result->fetchArray(SQLITE3_ASSOC)) {
                $columns[] = $tmp['name'];
            }
        } catch (Exception $e) {
            //$flag = Config::errorType($e);
        } finally {
            $db->close();
        }
        return $columns;
    }
}
