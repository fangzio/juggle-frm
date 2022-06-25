<?php

namespace Juggle\Frm\Exception;

class Handler
{
    public static function handleRuntimeException(\Throwable $e)
    {
        if ($e instanceof RuntimeException) {
            self::echoMessage($e->getRuntimeMessage());
        } else {
            self::echoMessage($e->getMessage());
        }
    }

    public static function echoMessage($str)
    {
        echo $str;
    }
}