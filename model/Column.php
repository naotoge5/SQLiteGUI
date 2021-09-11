<?php

class Column
{
    private Table $parent;
    private string $name;
    private string $type;
    private string $row;
    private $constraints;

    function __construct(Table $parent, $name, $row)
    {
        $this->parent = $parent;
        $this->name = $name;
        $this->row = $row;
        $this->setType();
        $this->setConstraints();
    }

    protected function setType()
    {
        $db = DB::cast(unserialize($_SESSION['db']));
        try {
            $db = $db->getDB();
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
            $row = $db->querySingle("SELECT [notnull], [dflt_value] FROM pragma_table_info('" . $this->parent->getName() . "') WHERE name = '" . $this->name . "'", true);
            $this->constraints['primary_key'] = self::hasPrimaryKey($this->row);
            $this->constraints['autoincrement'] = $this->hasAutoIncrement($this->row);
            $this->constraints['not_null'] = ($row['notnull']) ? true : false;
            $this->constraints['unique'] = self::hasUnique($this->row);
            $this->constraints['check'] = self::hasCheck($this->row);
            if ($this->constraints['check']) {
                $this->constraints['check'] = self::getCheckConditions($this->row);
            }
            $this->constraints['foreign_key'] = self::hasForeignKey($this->row);
            if ($this->constraints['foreign_key']) {
                $this->constraints['foreign_key'] = self::getForeignKeyConditions($this->parent->getName(), $this->name);
            }

            $this->constraints['default'] = ($row['dflt_value'] === null) ? false : $row['dflt_value'];
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
    static function hasPrimaryKey(string $row): bool
    {
        $row = row\removeParenthesesAndQuotation($row);
        return (stripos($row, 'primary key')) ? true : false;
    }

    /**
     * （複合でない）uniqueキーを持つかどうか
     *
     * @param string $row
     * @return boolean
     */
    static function hasUnique(string $row): bool
    {
        $row = row\removeParenthesesAndQuotation($row);
        return (stripos($row, 'unique')) ? true : false;
    }

    /**
     * check制約を持つかどうか
     *
     * @param string $row
     * @return boolean
     */
    static function hasCheck(string $row): bool
    {
        $row = row\removeParenthesesAndQuotation($row);
        return (stripos($row, 'check')) ? true : false;
    }

    /**
     * （複合でない）foreignキーを持つかどうか
     *
     * @param string $row
     * @return boolean
     */
    static function hasForeignKey(string $row): bool
    {
        $row = row\removeParenthesesAndQuotation($row);
        return (stripos($row, 'references')) ? true : false;
    }

    /**
     * autoincrementかどうか
     *
     * @param string $row
     * @return boolean
     */
    protected function hasAutoIncrement(string $row): bool
    {
        $row = row\removeParenthesesAndQuotation($row);
        return (stripos($row, 'autoincrement')) ? true : false;
    }

    /**
     * check制約の条件を取得
     *
     * @param string $row
     * @return boolean
     */
    static function getCheckConditions(string $row): string
    {
        $res = strstr($row, 'check');
        if ($res === false) $res = strstr($row, 'CHECK');
        $row = $res;
        $row = strstr($row, '(');
        $se = row\parenRange($row);
        $row = substr($row, 1, $se["end"] - 1);
        return $row;
    }

    /**
     * foreignkeyの取得
     *
     * @param [type] $table
     * @param [type] $column
     * @return false|array
     */
    static function getForeignKeyConditions($table, $column)
    {
        $DB = DB::cast(unserialize($_SESSION['db']));
        try {
            $db = $DB->getDB();
            $row = $db->querySingle("SELECT [table], [to], [on_update], [on_delete], [match] FROM pragma_foreign_key_list('" . $table . "') WHERE [from] = '" . $column . "'", true);
            return ["table" => $row['table'], "column" => $row['to']];
        } catch (Exception $e) {
            echo $e;
        } finally {
            $db->close();
        }
        return false;
    }

    function getName()
    {
        return $this->name;
    }

    function getType()
    {
        return $this->type;
    }

    function getRow()
    {
        return $this->row;
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
