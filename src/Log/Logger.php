<?php

namespace Juggle\Frm\Log;

use Juggle\Frm\Exception\RuntimeException;
use Juggle\Frm\Config\Config;
use Juggle\Frm\Log\Handler\File;

class Logger
{
    const LEVEL_TRACE = 'trace';
    const LEVEL_DEBUG = 'debug';
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

    /**
     * @var Handler[]
     */
    protected $handlers = [];

    public function __construct()
    {
        // 暂时只支持file类型
        // 根据配置文件，加载handlers
        $hConf = Config::get('log.handlers');
        if (empty($hConf) || !is_array($hConf)) {
            throw new RuntimeException('log config is invalid!');
        }
        foreach ($hConf as $h) {
            $config = [
                'file_name' => $h['file_name'] ?? '',
                'levels' => $h['levels'],
            ];
            $this->handlers[] = new File($config);
        }
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

    public function debug($msg)
    {
        $this->log($msg, self::LEVEL_DEBUG);
    }

    public function trace($msg)
    {
        $this->log($msg, self::LEVEL_TRACE);
    }

    public function warning($msg)
    {
        $this->log($msg, self::LEVEL_WARNING);
    }

    public function error($msg)
    {
        $this->log($msg, self::LEVEL_ERROR);
    }

    public function fatal($msg)
    {
        $this->log($msg, self::LEVEL_FATAL);
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