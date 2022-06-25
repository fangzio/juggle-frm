<?php

namespace Juggle\Frm\Exception;

class RuntimeException extends \Exception
{
    /**
     * 运行时异常
     * @return string
     */
    public function getRuntimeMessage()
    {
        return sprintf('juggle frm runtime exception:[%s]', $this->getMessage());
    }
}