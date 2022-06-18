<?php

namespace juggle\frm\router;

class Router
{
    const DEFAULT_CONTROLLER = 'index';
    const DEFAULT_ACTION = 'index';

    /**
     * @return string[]
     */
    public static function parseUrl()
    {
        if (empty($_SERVER['PATH_INFO'])) {
            return [self::DEFAULT_CONTROLLER, self::DEFAULT_ACTION];
        }
        $ac = explode('/', trim($_SERVER['PATH_INFO'], '/'));
        if (count($ac) != 2) {
            return [self::DEFAULT_CONTROLLER, self::DEFAULT_ACTION];
        }
        return $ac;
    }
}
