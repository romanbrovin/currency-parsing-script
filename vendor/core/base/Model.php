<?php

namespace vendor\core\base;

use vendor\core\Db;

abstract class Model
{

    protected Db $pdo;
    public array $vars = [];

    public function __construct()
    {
        $this->pdo = Db::instance();
    }

    /**
     * Помещение всех входных переменных в переменную $vars
     */
    public function loadVars($data)
    {
        foreach ($data as $name => $value) {
            if (isset($value)) {
                $this->vars[$name] = $value;
            }
        }
    }

}

