<?php

namespace Juggle\Frm\Orm\Handler;

interface IHandler
{
    /**
     * @param $sql
     * @param array $bind
     * @return \PDOStatement
     */
    public function query($sql, $bind = []);
}