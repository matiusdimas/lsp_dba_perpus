<?php

class App {
    protected $controller = 'HomeController';
    protected $method     = 'index';
    protected $params     = [];

    private const CONTROLLERS_PATH = __DIR__ . '/../Controllers/';

    public function __construct() {
        $url = $this->parseUrl();

        if (isset($url[0])) {
            // Support camelCase controller names like hakAkses -> HakAksesController
            $controllerName = ucfirst($url[0]) . 'Controller';
            $file = self::CONTROLLERS_PATH . $controllerName . '.php';
            if (file_exists($file)) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        require_once self::CONTROLLERS_PATH . $this->controller . '.php';
        $this->controller = new $this->controller;

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    public function parseUrl(): array {
        if (isset($_GET['url'])) {
            return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
        return [];
    }
}
