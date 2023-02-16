<h1 class="mb-1">
    Курсы валют
</h1>

<div class="mt-4 mb-4">
    <?php if ($data['settings']) : ?>
        <a class="btn__parse me-2" href="#">Обновить курсы</a>
        <a class="me-2" href="/settings">Настройки</a>
    <?php endif; ?>

    <a class="btn__reset" href="#">Инициализация (сброс)</a>
</div>

<?php if ($data['settings']) : ?>
    <div class="row">

        <div class="col-xxl-3 col-xl-4 col-md-6 col-sm-8">

            <div>Данные на <?=dateModify($data['settings']['xml_date'])?></div>
            <div>Обновление: <?=$data['settings']['refresh_interval']?> мин.</div>

            <table class="table table-bordered table-striped table-sm mt-2">

                <tr>
                    <th>Тикет</th>
                    <th>Котировка</th>
                    <th>Изменение</th>
                </tr>

                <?php foreach ($data['widgets'] as $name => $value) : ?>

                    <tr>
                        <td><?=$name?></td>
                        <td class="<?=$value['class']?>">
                            <strong>
                                <?=$value['current_value']?>
                            </strong>
                        </td>
                        <td class="text-secondary">
                            <?php if ($value['value_diff']) : ?>
                                <?=$value['value_diff']?>
                            <?php endif; ?>
                        </td>
                    </tr>

                <?php endforeach; ?>

            </table>
        </div>


    </div>

    <script>
        interval = <?=$data['settings']['refresh_interval']?> * 60000;
    </script>

<?php endif; ?>

