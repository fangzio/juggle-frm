<?php

use juggle\frm\app\Application;

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