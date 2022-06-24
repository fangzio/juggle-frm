<?php

namespace juggle\frm\config;

class Config
{
    protected static $configArr = [];

    public static function get(string $path = null, $default = null)
    {
        if ($path === null) {
            return self::$configArr;
        }
        $path = explode('.', $path);
        if (empty(self::$configArr) || !is_array(self::$configArr)) {
            return $default;
        }
        $conf = &self::$configArr;
        foreach ($path as $p) {
            if (isset($conf[$p])) {
                $conf = &$conf[$p];
            } else {
                return $default;
            }
        }
        return $conf;
    }

    public static function set(string $path, $val)
    {
        if (empty($path)) {
            return false;
        }
        if (strpos($path, '.') === false) {
            self::$configArr[$path] = $val;
            return true;
        }
        $path = explode('.', $path);
        $last = array_pop($path);
        $conf = &self::$configArr;
        foreach ($path as $p) {
            if (!isset($conf[$p])) {
                $conf[$p] = [];
            } else {
                $conf = &$conf[$p];
            }
        }
        $conf[$last] = $val;
        return true;
    }

    public static function import(array $config)
    {
        self::$configArr = array_merge_recursive(self::$configArr, $config);
    }
}