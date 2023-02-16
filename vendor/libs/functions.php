<?php

function debug($val)
{
    echo '<pre>';
    print_r($val);
    echo '</pre>';
}

function dateModify($value)
{
    return date_format(date_create($value), 'd.m.Y');
}

function isAjax(): bool
{
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
}

function isToken(): bool
{
    return isset($_POST['token2']) && $_POST['token2'] == $_SESSION['token1'];
}

