<?php

namespace juggle\frm\log\Handler;

use juggle\frm\exception\RuntimeException;
use juggle\frm\log\Handler;

class File extends Handler
{
    protected $path = APP_DIR . DS . 'runtime' . DS . 'log';

    protected $fileName = '';

    public function __construct($config)
    {
        parent::__construct($config);
        $this->fileName = $config['file_name'] ?? 'app.log';
        $this->init();
    }

    protected function process()
    {
        $fp = fopen($this->path . DS . $this->fileName, 'a+');
        flock($fp, LOCK_EX);
        foreach ($this->logs as $log) {
            fwrite($fp, $this->format($log));
        }
        flock($fp, LOCK_UN);
        fclose($fp);
    }

    protected function init()
    {
        // 暂时支持目录 app/runtime/log
        if (!file_exists($this->path)) {
            @umask(0);
            mkdir($this->path, 0755, true);
            chmod($this->path, 0755);
        }
        if (!is_dir($this->path)) {
            throw new RuntimeException('log path is not directory');
        }
    }
}