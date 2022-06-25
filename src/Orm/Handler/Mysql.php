<?php

namespace Juggle\Frm\Orm\Handler;

use Juggle\Frm\Exception\RuntimeException;

class Mysql extends PdoBase
{
    protected function dsn()
    {
        return sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s',
            $this->conf['host'],
            $this->conf['port'],
            $this->conf['dbname'],
            $this->conf['charset']);
    }

    public static function parseConf($conf)
    {
        $default = [
            'host' => '',
            'port' => '3306',
            'dbname' => '',
            'user' => '',
            'passwd' => '',
            'charset' => '',
            'option' => [],
        ];
        if (empty($conf['type'])) {
            throw new RuntimeException('db config invalid: no db type!');
        }
        return array_merge($default, $conf);
    }
}