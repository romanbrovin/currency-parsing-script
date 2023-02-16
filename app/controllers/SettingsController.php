<?php

namespace app\controllers;

use app\models\Settings;

class SettingsController extends AppController
{

    public function indexAction()
    {
        // Получаем все настройки из БД
        $settings = Settings::getAll();

        // Передаем в html переменную $settings
        $this->set(compact(['settings']));
    }

    public function saveAction()
    {
        // Если запрос пришел аяксом и совпадают токены
        if (isAjax() && isToken()) {
            $settings = new Settings();

            // Принимаем переменные
            $settings->loadVars($_POST);

            $settings->save();
        }

        exit;
    }

    public function parseXmlAction()
    {
        Settings::parseXml();

        exit;
    }

    // Обнуление БД и установка дефолтный значений
    public function resetAction()
    {
        // Если запрос пришел аяксом и совпадают токены
        if (isAjax() && isToken()) {
            Settings::reset();
        }

        exit;
    }

}


