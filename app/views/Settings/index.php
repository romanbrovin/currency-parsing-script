<h1 class="mb-4">Настройки</h1>

<div class="row">

    <div class="col-xl-4 mb-4">
        <h4 class="mb-2">
            Список для виджета
        </h4>
        <div>
            <?php foreach ($settings['currencies'] as $currency) : ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" data-field="currencies_to_widgets" id="widget_<?=$currency?>"
                           name="<?=$currency?>" type="checkbox"
                        <?=(in_array($currency, $settings['currencies_to_widgets'])) ? 'checked' : null;?>>
                    <label for="widget_<?=$currency?>">
                        <?=$currency?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="col-xl-4 mb-4">
        <h4 class="mb-2">
            Список для импорта
        </h4>
        <div>
            <?php foreach ($settings['currencies'] as $currency) : ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" data-field="currencies_to_imports" id="import_<?=$currency?>"
                           name="<?=$currency?>" type="checkbox"
                        <?=(in_array($currency, $settings['currencies_to_imports'])) ? 'checked' : null;?>>
                    <label for="import_<?=$currency?>">
                        <?=$currency?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div>
        <label for="refresh_interval">Интервал обновления (мин.)</label>
        <div class="col-sm-2 col-1 mt-2">
            <input class="form-control" id="refresh_interval" type="text" value="<?=$settings['refresh_interval']?>">
        </div>
    </div>

    <div class="mt-4">
        <a class="btn btn-secondary btn-sm me-2" href="/">Назад</a>
        <button class="btn btn-success btn-sm btn__save">Сохранить</button>
    </div>

</div>
