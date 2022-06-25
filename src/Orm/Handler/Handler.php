<?php

namespace Juggle\Frm\Orm\Handler;

use Juggle\Frm\Exception\RuntimeException;

abstract class Handler implements IHandler
{
    protected $conf;
    protected $connection;

    public function __construct($conf)
    {
        $this->conf = $conf;
    }

    public static function parseConf($conf)
    {
        if (empty($conf['type'])) {
            throw new RuntimeException('db config invalid, no db type!');
        }
        $cls = 'Juggle\Frm\Orm\Handler\\' . ucfirst(strtolower($conf['type']));
        if (!class_exists($cls)) {
            throw new RuntimeException('db handler not exist!');
        }
        return $cls::parseConf($conf);
    }

    abstract protected function dsn();

    abstract protected function afterConnection();
}