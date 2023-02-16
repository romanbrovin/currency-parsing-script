<?php

namespace vendor\core;

class Router
{

    protected static array $route;  // текущий маршрут
    protected static array $routes; // таблица маршрутов

    /**
     * Добавляем маршрут в таблицу
     *
     * @param string $regexp регулярное выражение маршрута
     * @param array  $route маршрут [controller, action, params]
     */
    public static function add(string $regexp, array $route = [])
    {
        self::$routes[$regexp] = $route;
    }

    /**
     * Перенаправляем URL по корректному маршруру
     */
    public static function dispatch(string $url)
    {
        if (self::matchRoute($url)) {
            $controller = 'app\controllers\\' . self::$route['controller'] . 'Controller';

            if (class_exists($controller)) {
                $obj = new $controller(self::$route);
                $method = self::lowerCamelCase(self::$route['action']) . 'Action';

                if (method_exists($obj, $method)) {
                    $obj->$method();
                    $obj->getView();
                } else {
                    include WWW . '/404.php';
                }
            } else {
                include WWW . '/404.php';
            }
        } else {
            include WWW . '/404.php';
        }
    }

    /**
     * Ищем URL в таблице маршрутов
     */
    protected static function matchRoute(string $url): bool
    {
        foreach (self::$routes as $pattern => $route) {
            if (preg_match("~$pattern~i", $url, $matches)) {
                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $route[$key] = $value;
                    }
                }

                if (!isset($route['action'])) {
                    $route['action'] = 'index';
                }

                $route['controller'] = self::upperCamelCase($route['controller']);
                self::$route = $route;

                return true;
            }
        }

        return false;
    }

    /**
     * Преобразуем имена к виду CamelCase
     */
    protected static function upperCamelCase(string $name): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $name)));
    }

    /**
     * Преобразуем имена к виду camelCase
     */
    protected static function lowerCamelCase(string $name): string
    {
        return lcfirst(self::upperCamelCase($name));
    }

}
