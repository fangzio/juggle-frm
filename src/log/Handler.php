<?php

namespace juggle\frm\log;

abstract class Handler
{

    protected $logs = [];

    protected $levels = [];

    public function __construct($config)
    {
        $this->levels = $config['levels'];
    }

    public function collect($logs)
    {
        // 日志level过滤
        $logs = array_filter($logs, [$this, 'filterByLevel']);
        $this->logs = array_merge($this->logs, $logs);
        // 日志format
        if (!empty($logs)) {
            $this->process();
            $this->logs = [];
        }
    }

    protected function filterByLevel($log)
    {
        return in_array($log['level'], $this->levels);
    }

    // 具体handler执行日志持久化的方法
    abstract protected function process();

    protected function format($log)
    {
        return sprintf("[app-%s] [%s]:%s\n", $log['level'], $log['time'], $log['message']);
    }
}