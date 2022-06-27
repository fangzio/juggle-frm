<?php

use Juggle\Frm\App\Application;

if (!function_exists('app')) {
    function app()
    {
        return Application::getApp();
    }
}

if (!function_exists('logger')) {
    function logger()
    {
        return app()->getLogger();
    }
}

if (!function_exists('now_ms')) {
    function now_ms()
    {
        list($us, $s) = explode(' ', microtime());
        return number_format($s * 1000 + $us * 1000, 3, '.', '');
    }
}

if (!function_exists('cost')) {
    function cost($start, $end)
    {
        return number_format($end - $start, 3, '.', '');
    }
}