<?php

namespace app\models;

use R;
use vendor\core\base\Model;

class Index extends Model
{

    /**
     * Получения списка котировок (+ изменения за предыдущий день) по заданным валютам
     *
     * @param array $currencies список валют для отображения в виджете
     */
    public static function getWidgets(array $currencies): array
    {
        $data = [];

        // Сортируем валюты по алфавиту
        sort($currencies);

        foreach ($currencies as $currency) {
            // Получаем список последних двух котировок по текущей валюте
            $query = "
                SELECT value
                FROM m_data
                WHERE currency = '$currency'
                ORDER BY date DESC
                LIMIT 2";

            $dataList = R::getAll($query);

            foreach ($dataList as $key => $item) {
                // Обнуляем значение разницы котировой и стиля класса
                $data[$currency]['value_diff'] =
                $data[$currency]['class'] =
                    null;

                // Получение актуальной котировки
                if ($key == 0) {
                    $data[$currency]['current_value'] = $item['value'];
                } else {
                    // Если есть еще одна запись по этой валюте
                    if ($item['value']) {
                        // Предудыщая котировка
                        $data[$currency]['last_value'] = $item['value'];

                        // Разница котировок
                        $valueDiff = $data[$currency]['current_value'] - $data[$currency]['last_value'];
                        $valueDiff = round($valueDiff, 4);
                        $data[$currency]['value_diff'] = $valueDiff;

                        // Устанавливем класс для цветового отображение разницы котировок
                        if ($valueDiff > 0) {
                            $data[$currency]['class'] = 'text-success';
                        } else if ($valueDiff < 0) {
                            $data[$currency]['class'] = 'text-danger';
                        }
                    }
                }
            }
        }

        return $data;
    }

}
