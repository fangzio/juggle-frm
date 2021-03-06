<?php

namespace Juggle\Frm\App;

use Juggle\Frm\Controller\Controller;
use Juggle\Frm\Exception\RuntimeException;
use Juggle\Frm\Log\Logger;
use Juggle\Frm\Router\Router;

class Application extends Module
{
    /**
     * @var Application
     */
    protected static $app;
    /**
     * @var Controller
     */
    protected $controller;
    /**
     * @var Module
     */
    protected $currentModule;
    /**
     * @var Router
     */
    protected $router;

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct()
    {
        self::setApp($this);
        // app init
        $this->init();
    }

    public static function setApp(Application $app)
    {
        self::$app = $app;
    }

    public static function getApp()
    {
        return self::$app;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function run()
    {
        $ac = $this->router::parseUrl();
        list($controller, $action) = $ac;
        $controller = $this->parseController($controller);
        $this->controller = new $controller;
        $this->controller->init();
        $this->controller->run($action);
    }

    private function parseController($controller)
    {
        $cls = ucfirst($controller) . 'Controller';
        $ctrlPath = APP_DIR . DIRECTORY_SEPARATOR . 'controller' . DIRECTORY_SEPARATOR . $cls . '.php';
        if (!file_exists($ctrlPath)) {
            throw new RuntimeException(sprintf('controller [%s] not exist!', $cls));
        }
        require_once $ctrlPath;
        return $cls;
    }

    private function registerExceptionHandler()
    {
        set_exception_handler('Juggle\Frm\Exception\Handler::handleRuntimeException');
    }

    private function init()
    {
        // 设置时区
        date_default_timezone_set('Asia/Shanghai');
        // 初始化常量
        require_once __DIR__ . DIRECTORY_SEPARATOR . '../const.php';
        // 加载预定义快捷函数
        require_once __DIR__ . DS . '../fn/Fn.php';
        $this->registerExceptionHandler();

        $this->router = new Router();
        $this->logger = new Logger();
    }
}