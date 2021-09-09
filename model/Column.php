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
        $db = DB::cast(unserialize($_SESSION['db']));
        try {
            $db = $db->getDB();
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
        $DB = DB::cast(unserialize($_SESSION['db']));
        try {
            $db = $DB->getDB();
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
                $this->constraints['foreign_key'] = self::getForeignKeyConditions($this->parent->getName(), $this->name);
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
        $flag = false;
        $start_end = startToEnd($row);
        $start = $start_end["start"];
        $end = $start_end["end"];
        if (empty($start)) {
            if ((strpos($row, 'unique') !== false) || (strpos($row, 'UNIQUE') !== false)) $flag = true;
        } else {
            foreach ($start as $key => $value) {
                $tmp = substr($row, $value, $end[$key]);
                $tmp = str_replace($tmp, '', $row);
                echo '|' . $tmp . '|';
                if ((strpos($tmp, 'unique') !== false) || (strpos($tmp, 'UNIQUE') !== false)) $flag = true;
            }
        }
        return $flag;
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
        echo $row;
        $row = strstr($row, '(');
        $end = mb_strlen($row);
        $array = str_split($row);
        $tmp = [];
        $start = 0;
        $flag = false;
        foreach ($array as $key => $val) {
            if (empty($tmp)) {
                switch ($val) {
                    case "(":
                    case "'":
                    case '"':
                        $start = $key;
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
                if (empty($tmp)) {
                    $end = $key;
                    $flag = true;
                }
            }
            if ($flag) break;
        }
        $row = substr($row, 1, $end - 1);
        return $row;
    }

    /**
     * foreignkeyの取得
     *
     * @return boolean
     */
    static function getForeignKeyConditions($table, $column = null)
    {
        $DB = DB::cast(unserialize($_SESSION['db']));
        try {
            $db = $DB->getDB();
            if (is_null($column)) {
                $rows = [];
                $result = $db->query("SELECT [table], [to] FROM pragma_foreign_key_list('" . $table . "')");
                while ($tmp = $result->fetchArray(SQLITE3_ASSOC)) {
                    $rows[] = ["table" => $tmp['table'], "column" => $tmp['to']];
                }
            } else {
                $row = $db->querySingle("SELECT [table], [to] FROM pragma_foreign_key_list('" . $table . "') WHERE [from] = '" . $column . "'", true);
                return ["table" => $row['table'], "column" => $row['to']];
            }
        } catch (Exception $e) {
            echo $e;
        } finally {
            $db->close();
        }
    }

    /**
     * Undocumented function
     *
     * @return string
     */
    protected function toRow(): string
    {
        $schema = $this->parent->getSchema();
        $tmp = strstr($schema, '('); //CREATE TABLE <テーブル名>までを除去
        $schema = substr($tmp, 1, -1); //先頭の"(", 最後尾の")"を除去
        $tmp = strstr($schema, $this->name . ' ' . $this->type); //対象の先頭行までを除去
        $end = self::rowEnd($tmp); //対象の最後尾を検索
        $row = substr($tmp, 0, $end); //最後尾以降を除去
        return $row;
    }

    static function rowEnd($value)
    {
        $end = mb_strlen($value);
        if ($end === 1) return false; //文字列長が1であれば調べる価値なし
        $array = str_split($value); //文字列を配列に変換
        $tmp = [];
        foreach ($array as $key => $val) {
            if (empty($tmp)) { //配列が空の時特殊文字待機中でない...","が現れた場所が最後尾
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
        $DB = DB::cast(unserialize($_SESSION['db']));
        $columns = [];
        try {
            $db = $DB->getDB();
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
