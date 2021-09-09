<?php

class Route
{

    private string $path;
    private string $relative = '';
    private $table;

    private string $redirect = '/';

    final private function __construct()
    {
    }

    static function on($uri)
    {

        $instance = new self();
        if ($instance->init($uri)) {
            return $instance;
        } else {
            return $instance->getRedirect();
        }
    }

    function init($uri)
    {
        $flag = false;
        $params = explode("/", $uri);
        if (isset($_SESSION['db'])) {
            $this->path = 'new';
            $this->table = null;
            if (count($params) === 2 && $params[1] === '') $flag =  true;
            if (Table::find($params[1])) {
                $this->redirect .= $params[1] . '/';
                $this->table = $params[1];
                if (count($params) > 2) {
                    switch ($params[2]) {
                        case '':
                            $this->path = 'structure';
                            $flag =  true;
                            break;
                        case 'content':
                            $this->redirect .= 'content/';
                            if (count($params) === 4 && $params[3] === '') {
                                $this->path = 'content';
                                $flag =  true;
                            }
                            break;
                    }
                }
            }
        } else {
            $this->path = 'login';
            $this->table = null;
            if ($params[1] === '' && count($params) === 2) $flag =  true;
        }
        if ($flag) $this->setRelative(count($params) - 2);
        return $flag;
    }

    /**
     * インスタンスの生成
     * @param int $id
     * @return static|null
     */
    final static function setAll($id): static | null
    {
        if (!is_numeric($id)) return null;
        // 自分自身のインスタンス生成
        $instance = new static();

        // インスタンス初期化メソッド呼び出し、失敗時はnullを返す
        return $instance->init($id) ? $instance : null;
    }
    function setPath($path)
    {
        $this->path = $path;
    }

    function setTable($table)
    {
        $this->table = $table;
    }

    function setRelative($count)
    {
        for ($i = 0; $i < $count; $i++) {
            $this->relative .= '../';
        }
    }

    function getPath()
    {
        return $this->path;
    }

    function getTable()
    {
        return $this->table;
    }

    function getRedirect(): string
    {
        return $this->redirect;
    }

    function getRelative()
    {
        return $this->relative;
    }
}
