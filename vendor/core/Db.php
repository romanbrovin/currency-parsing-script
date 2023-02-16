<?php

namespace vendor\core;

use R;

class Db
{

    protected static $instance;

    protected function __construct()
    {
        // Настройки подключения к БД
        $db = require ROOT . '/config/db.php';

        // Подключаем ORM RedBeanPHP https://redbeanphp.com/
        require LIBS . '/rb.php';

        R::setup($db['dsn'], $db['user'], $db['pass']);
        R::freeze(true);
    }

    // Создаем один экземпляр класса БД
    public static function instance(): Db
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

}
