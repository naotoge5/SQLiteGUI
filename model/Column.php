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
            $row = $db->querySingle("SELECT [notnull], [dflt_value], [pk] FROM pragma_table_info('" . $this->parent->getName() . "') WHERE name = '" . $this->name . "'", true);
            $flag = ($row['pk']) ? true : false;
            if ($flag) $flag = !$this->isComposite($db);
            $this->constraints['primary_key'] = $flag;
            $this->constraints['not_null'] = ($row['notnull']) ? true : false;
            $this->constraints['default'] = ($row['dflt_value'] === null) ? false : $row['dflt_value'];
            $this->constraints['unique'] = $this->hasUnique($db);
            $this->toRow();
            $row = $db->querySingle("SELECT [table], [to] FROM pragma_foreign_key_list('" . $this->parent->getName() . "') WHERE [from] = '" . $this->name . "'", true);
            $this->constraints['foreign_key'] = ($row) ? ["table" => $row['table'], "column" => $row['to']] : false;
        } catch (Exception $e) {
        } finally {
            $db->close();
        }
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
     * primaryキーが複合でないかどうか
     *
     * @param SQLite3 $db
     * @return boolean
     */
    protected function isComposite(SQLite3 $db): bool
    {
        $result = $db->query("SELECT origin FROM pragma_index_list('" . $this->parent->getName() . "')");
        $rows = [];
        while ($tmp = $result->fetchArray(SQLITE3_ASSOC)) {
            $rows[] = $tmp['origin'];
        }
        return in_array('pk', $rows);
    }

    /**
     * カラムが（複合でない）uniqueキーを持つかどうか
     *
     * @param SQLite3 $db
     * @return boolean
     */
    protected function hasUnique(SQLite3 $db): bool
    {
        $flag = false;
        $result = $db->query("SELECT name FROM pragma_index_list('" . $this->parent->getName() . "') WHERE origin = 'u'");
        while ($tmp = $result->fetchArray(SQLITE3_ASSOC)) {
            $result2 = $db->query("SELECT name FROM pragma_index_info('" . $tmp['name'] . "')");
            $rows = [];
            while ($tmp = $result2->fetchArray(SQLITE3_ASSOC)) {
                $rows[] = $tmp['name'];
            }
            if (in_array($this->name, $rows) && count($rows) == 1) {
                $flag = true;
                break;
            }
        }
        return $flag;
    }

    protected function toRow(): string
    {
        $schema = $this->parent->getSchema();
        $tmp = strstr($schema, $this->name . ' ' . $this->type);
        $end = strpos($tmp, ',');
        $row = substr($tmp, 0, $end);
        while (substr_count($row, '(') !== substr_count($row, ')')) {
            $tmp2 = strstr($tmp, ',');
            $tmp2 = substr($tmp2, 1);
            $subend = strpos($tmp2, ',');
            if (!$subend) $subend = strrpos($tmp2, ')');
            $end += $subend + 1;
            $row = substr($tmp, 0, $end);
        }
        echo $row;
        return '';
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
