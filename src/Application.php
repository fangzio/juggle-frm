<?php

namespace juggle\frm;

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

    public function __construct()
    {
        self::setApp($this);
        $this->router = new Router();
    }

    public static function setApp(Application $app)
    {
        self::$app = $app;
    }

    public function run()
    {
        $ac = $this->router::parseUrl();
        list($controller, $action) = $ac;
        $this->controller = $controller;
        $this->controller->init();
        $this->controller->run($action);
    }
}