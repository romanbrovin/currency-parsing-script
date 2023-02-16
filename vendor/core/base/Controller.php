<?php

namespace vendor\core\base;

abstract class Controller
{

    public array $route = []; // текущий маршрут
    public array $vars = [];  // пользовательские данные

    public function __construct($route)
    {
        $this->route = $route;
    }

    public function getView()
    {
        $obj = new View($this->route);
        $obj->render($this->vars);
    }

    public function set($vars)
    {
        $this->vars = $vars;
    }

}
