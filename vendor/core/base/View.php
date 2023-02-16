<?php

namespace vendor\core\base;

class View
{

    public array $route; // текущий маршрут

    public function __construct($route)
    {
        $this->route = $route;
    }

    /**
     * Выводим html страничку
     */
    public function render($vars)
    {
        if (is_array($vars)) {
            extract($vars);
        }

        ob_start();

        require_once APP . "/views/{$this->route['controller']}/index.php";

        // Сохраняем в переменную $content все данные из вида
        $content = ob_get_contents();

        ob_clean();

        require_once APP . "/views/layouts/default.php";
    }

}
