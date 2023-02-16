<?php

namespace app\models;

use R;
use DateTime;
use vendor\core\base\Model;

class Settings extends Model
{

    private static string $url = 'http://www.cbr.ru/scripts/XML_daily.asp';

    public array $vars = [
        'refresh_interval',
        'currencies_to_widgets',
        'currencies_to_imports',
    ];

    /**
     * Получение всех настроек
     */
    public static function getAll(): array
    {
        $result = R::findAll('m_settings');

        $data = [];
        foreach ($result as $item) {
            if ($item['name'] == 'refresh_interval' ||
                $item['name'] == 'parsing_date' ||
                $item['name'] == 'xml_date') {
                $data[$item['name']] = $item['value'];
            } else {
                // Преобразование строки в массив
                $data[$item['name']] = explode(";", $item['value']);
            }
        }

        return $data;
    }

    /**
     * Сохранение настроек
     */
    public function save()
    {
        $this->saveByFieldName('currencies_to_widgets');
        $this->saveByFieldName('currencies_to_imports');
        $this->saveRefreshInterval();
    }

    /**
     * Сохранение данных по имени поля
     */
    private function saveByFieldName(string $fieldName)
    {
        $array = [];
        foreach ($this->vars[$fieldName] as $item) {
            if ($item['value'] == 1) {
                // Фильтрация от спецсимволов
                $item['currency'] = htmlspecialchars(trim($item['currency']), ENT_QUOTES);

                $array[] = $item['currency'];
            }
        }

        // Получение из БД записи с нужным названием поля
        $obj = R::findOne('m_settings', 'name = ?', [$fieldName]);

        // Преобразование массива в строку
        $obj['value'] = implode(";", $array);

        // Обновляем значение записи
        R::store($obj);
    }

    /**
     * Сохранение интервала для обновления парсинга
     */
    private function saveRefreshInterval()
    {
        // Получение из БД записи с интервалом обновления
        $obj = R::findOne('m_settings', 'name = "refresh_interval"');

        // Приводим полученный интервал к типу integer
        $obj['value'] = (int)$this->vars['refresh_interval'];

        if ($obj['value'] <= 0) {
            // Если полученный интервал менее нуля,
            // то устанавливаем минимальный интервал в одну единицу
            $obj['value'] = 1;
        }

        // Обновляем значение записи
        R::store($obj);
    }

    /**
     * Парсинг данных по ссылке из ЦБ
     */
    public static function parseXml()
    {
        // Получение всех настроек
        $settings = self::getAll();

        $currentDate = date('Y-m-d H:i:s');

        // Получение даты для следующего обновления данных согласно установленному интервалу (в минутах)
        $tDate = new DateTime($settings['parsing_date']);
        $tDate->modify('+' . $settings['refresh_interval'] . ' minute');
        $nextUpdateDate = $tDate->format('Y-m-d H:i:s');

        // Если текущее время меньше следующего временя для обновления фида
        // Необходимо для предотвращения постоянных запросов
        if ($currentDate < $nextUpdateDate) {
            exit;
        }

        // Получение фида
        $xml = simplexml_load_file(self::$url);

        // Если фид не пустой
        if (!empty($xml)) {
            // Получение даты из фида
            $xmlDate = date_create($xml->attributes()->Date);
            $xmlDate = date_format($xmlDate, 'Y-m-d');

            // Проходимся по всех валютам в фиде
            foreach ($xml->Valute as $item) {
                // Текущая валюта входит в список валют для загрузки с ЦБ
                if (in_array($item->CharCode, $settings['currencies_to_imports'])) {
                    // Записываем в БД
                    $params['value'] = $item->Value;
                    $params['currency'] = $item->CharCode;
                    $params['xmlDate'] = $xmlDate;

                    self::setDataByCurrency($params);
                }
            }

            // Установка даты последнего парсинга (текущее время)
            $obj = R::findOne('m_settings', 'name = "parsing_date"');
            $obj['value'] = date('Y-m-d H:i:s');
            R::store($obj);

            // Обновление в настройках даты из фида
            $obj = R::findOne('m_settings', 'name = "xml_date"');
            $obj['value'] = $xmlDate;
            R::store($obj);
        }
    }

    /**
     * Установка/обновление данных для валюты из парсинга
     */
    private static function setDataByCurrency(array $params)
    {
        // Поиск существующей записи
        $query = "currency = ? AND date = ?";
        $obj = R::findOne('m_data', $query, [
            $params['currency'],
            $params['xmlDate'],
        ]);

        if (!$obj) {
            // Новая запись
            $obj = R::dispense('m_data');
            $obj['date'] = $params['xmlDate'];
            $obj['currency'] = $params['currency'];
        }

        $value = str_replace(',', '.', $params['value']);
        $obj['value'] = $value;

        // Сохраняем/обновляем запись в бд
        R::store($obj);
    }

    /**
     * Обнуление базы данных и установка дефолтных значений
     */
    public static function reset()
    {
        R::wipe('m_data');
        R::wipe('m_settings');

        $fields = ['currencies', 'currencies_to_widgets', 'currencies_to_imports',
            'refresh_interval', 'parsing_date', 'xml_date'];

        foreach ($fields as $item) {
            // Новая запись
            $obj = R::dispense('m_settings');

            $obj['name'] = $item;

            if ($item == 'refresh_interval') {
                $obj['value'] = 1;
            } else if ($item == 'parsing_date') {
                $obj['value'] = date('Y-m-d 00:00:00');
            } else if ($item == 'xml_date') {
                $obj['value'] = date('Y-m-d');
            } else {
                $obj['value'] = 'AUD;AZN;GBP;AMD;BYN;BGN;BRL;HUF;HKD;DKK;USD;EUR;INR;KZT;CAD;KGS;CNY;MDL;NOK;PLN;RON;XDR;SGD;TJS;TRY;TMT;UZS;UAH;CZK;SEK;CHF;ZAR;KRW;JPY';
            }

            R::store($obj);
        }
    }

}
