$(function () {
    token2 = $('#token2').val();

    $('.btn__parse').click(function () {
        parseXml();
    });

    $('.btn__reset').click(function () {
        resetSettings();
    });

    $('.btn__save').click(function () {
        saveSettings();
    });

    // Если находимся на главной странице,
    // запускаем парсинг xml по установленному интервалу
    if (window.location.pathname == '/') {
        setInterval(parseXml, interval);
    }

});

function parseXml() {
    $.ajax({
        type: "POST",
        url: "/settings/parse-xml"
    }).done(function () {

        location.reload();

    });
}

function saveSettings() {
    let vars = new Object();
    vars['token2'] = token2;
    vars['refresh_interval'] = $('#refresh_interval').val();
    vars['currencies_to_widgets'] = [];
    vars['currencies_to_imports'] = [];

    // Проходимся по всем чекбоксам
    $('input:checkbox').each(function () {
        let currency = $(this).attr('name');
        let field = $(this).data('field');

        ($(this).prop('checked')) ? value = 1 : value = 0;
        vars[field].push({'currency': currency, 'value': value});
    });

    $.ajax({
        type: "POST",
        url: "/settings/save",
        data: vars
    }).done(function () {

        location.href = '/';

    });
}

function resetSettings() {
    let vars = new Object();
    vars['token2'] = token2;

    $.ajax({
        type: "POST",
        url: "/settings/reset",
        data: vars
    }).done(function () {

        location.reload();

    });
}
