<?php

namespace app\controllers;

use app\models\Index;
use vendor\core\base\Controller;

class AppController extends Controller
{

    public function __construct($route)
    {
        parent::__construct($route);

        new Index();
    }

}
