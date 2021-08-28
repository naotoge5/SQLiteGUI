<?php

class Column
{
    private string $name;
    private string $type;
    private $constraints;

    protected const CONSTRAINT = ['check', 'foreign key', 'unique'];

    function __construct($table_schema, $name, $type, $constraints)
    {
        $this->name = $name;
        $this->type = $type;
        $this->constraints = $constraints;
        $rows = explode("\n", $table_schema);
        foreach ($rows as $row) {
            foreach (self::CONSTRAINT as $tmp) {
                $this->constraints[strtoupper($tmp)] = (strpos($row, 's')) ? 1 : 0;
            }
        }
    }

    static function list($name, $key = false)
    {
        $db = unserialize($_SESSION['db']);
        $path = $db->getPath();
        $columns = [];
        try {
            $db = new SQLite3($path);
            $db->enableExceptions(true);
            $result = $db->query("PRAGMA table_info(" . $name . ")");
            while ($tmp = $result->fetchArray(SQLITE3_ASSOC)) {
                if ($key) {
                    $columns[] = $tmp[$key];
                } else {
                    $columns[] = $tmp;
                }
            }
        } catch (Exception $e) {
            //$flag = Config::errorType($e);
        } finally {
            $db->close();
        }
        return $columns;
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
}
