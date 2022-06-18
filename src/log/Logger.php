<?php

namespace juggle\frm\log;

class Logger
{
    const LEVEL_TRACE = 1;
    const LEVEL_INFO = 2;
    const LEVEL_WARNING = 4;
    const LEVEL_ERROR = 8;

    const LEVEL_TRACE_STR = 'trace';
    const LEVEL_INFO_STR = 'info';
    const LEVEL_WARNING_STR = 'warning';
    const LEVEL_ERROR_STR = 'error';

    protected $type = 'file';
    protected $level = self::LEVEL_INFO;

    protected $path = APP_DIR . DS . 'runtime' . DS . 'log';

    public function info($msg)
    {
        $fileName = $this->path . DS . 'app-info.log';
        $msg = sprintf("[app-info] [%s]:%s\n", date('Y-m-d H:i:s'), $msg);
        $this->appendMsg($fileName, $msg);
    }

    protected function appendMsg($fileName, $msg)
    {
        // 暂时支持目录 app/runtime/log
        $path = APP_DIR . DS . 'runtime';
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }

        $path = $path . DS . 'log';
        if (!file_exists($path)) {
            mkdir($path, 0777);
        }
        file_put_contents($fileName, $msg, FILE_APPEND);
    }
}