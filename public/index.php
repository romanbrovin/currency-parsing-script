<?php

use vendor\core\Router;

$query = trim($_SERVER['REQUEST_URI'], '/');

define('ROOT', dirname(__DIR__));
define('APP', dirname(__DIR__) . '/app');
define('WWW', dirname(__DIR__) . '/public');
define('LIBS', dirname(__DIR__) . '/vendor/libs');

require LIBS . '/functions.php';

session_start();

// Подключаем классы
spl_autoload_register(function ($class) {
    $file = ROOT . '/' . str_replace('\\', '/', $class) . '.php';
    if (is_file($file)) {
        require_once $file;
    }
});

// Правила по умолчанию
Router::add('^(?P<controller>[-a-z\d]+)/?(?P<action>[-a-z\d]+)?$');
Router::add('^$', ['controller' => 'Index', 'action' => 'index']);

// Запуск
Router::dispatch($query);
