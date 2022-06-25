<?php

namespace Juggle\Frm\Orm;

use Juggle\Frm\Config\Config;
use Juggle\Frm\Exception\RuntimeException;
use Juggle\Frm\Orm\Handler\Handler;

class ConnectionManager
{
    protected $connections = [];

    /**
     * @param null $name
     * @return Handler
     * @throws RuntimeException
     */
    public function connection($name = null)
    {
        if ($name === null) {
            $name = 'default';
        }
        if (isset($this->connections[$name])) {
            return $this->connections[$name];
        }
        return $this->connect($name);
    }

    public function reconnect($name)
    {
        return $this->connect($name);
    }

    public function connect($name)
    {
        $conf = Config::get('db.' . $name);
        if (empty($conf)) {
            throw new RuntimeException('db config is invalid!');
        }
        // parse dsn
        $conf = Handler::parseConf($conf);
        $cls = 'Juggle\Frm\Orm\Handler\\' . ucfirst(strtolower($conf['type']));
        $handler = new $cls($conf);
        $this->connections[$name] = $handler;
        return $handler;
    }
}