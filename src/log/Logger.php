<?php

namespace juggle\frm\log;

use juggle\frm\log\Handler\File;

class Logger
{
    const LEVEL_TRACE = 'trace';
    const LEVEL_INFO = 'info';
    const LEVEL_WARNING = 'warning';
    const LEVEL_ERROR = 'error';
    const LEVEL_FATAL = 'fatal';

    // 日志条数达到条数限制，自动冲洗
    protected $autoFlushCnt = 10;

    protected $logs = [];

    protected $logsCount = 0;

    // 正在执行写日志操作
    protected $processing = false;

    protected $levels = [self::LEVEL_TRACE, self::LEVEL_INFO, self::LEVEL_WARNING, self::LEVEL_ERROR];

    /**
     * @var Handler[]
     */
    protected $handlers = [];

    public function __construct()
    {
        // 暂时只支持file类型
        $config = [
            'levels' => $this->levels,
        ];
        $this->handlers[] = new File($config);
        register_shutdown_function([$this, 'flush']);
    }

    public function log($msg, $level = self::LEVEL_INFO)
    {
        $this->logs[] = [
            'time' => date('Y/m/d H:i:s'),
            'message' => strval($msg),
            'level' => $level,
        ];
        $this->logsCount++;
        if ($this->autoFlushCnt > 0 && $this->logsCount >= $this->autoFlushCnt && !$this->processing) {
            $this->processing = true;
            $this->flush();
            $this->processing = false;
        }
    }

    public function info($msg)
    {
        $this->log($msg, self::LEVEL_INFO);
    }

    protected function flush()
    {
        // handler收集日志
        foreach ($this->handlers as $h) {
            $h->collect($this->logs);
        }
        $this->logs = [];
        $this->logsCount = 0;
    }
}