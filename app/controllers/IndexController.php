<?php

namespace app\controllers;

use app\models\Index;
use app\models\Settings;

class IndexController extends AppController
{

    public function indexAction()
    {
        // Получаем все настройки из БД
        $data['settings'] = Settings::getAll();

        // Получаем список виджетов, если прошла первичная инициализация
        if ($data['settings']) {
            $currencies = $data['settings']['currencies_to_widgets'];
            $data['widgets'] = Index::getWidgets($currencies);
        }

        // Передаем в html переменную $data
        $this->set(compact(['data']));
    }

}


