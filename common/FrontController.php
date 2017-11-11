<?php

namespace common;

define('ERROR_CONTROLLER_PATH', CONTROLLER_NAMESPACE . ucfirst(strtolower(ERROR_CONTROLLER)) . 'Controller');

class FrontController
{

    protected $basePath = ROOT;
    protected $controller = CONTROLLER_NAMESPACE . DEFAULT_CONTROLLER . 'Controller';
    protected $action = DEFAULT_ACTION;
    protected $params = array();

    /**
     * FrontController constructor.
     */
    public function __construct()
    {
        $this->parseUri();
    }

    private function parseUri()
    {
        $path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), '/');
        $path = preg_replace('/[^a-zA-Z0-9]\//', "", $path);
        if (strpos($path, $this->basePath) === 0) {
            $path = substr($path, strlen($this->basePath));
        }

        @list($controller, $action, $params) = explode("/", $path, 3);

        if (!empty($controller)) {
            $this->setController($controller);
        }
        if (!empty($action) && $this->controller != ERROR_CONTROLLER_PATH) {
            $this->setAction($action);
        }
        if (!empty($params) && $this->controller != ERROR_CONTROLLER_PATH) {
            $this->setParams(explode("/", $params));
        }
    }

    private function setController($controller)
    {
        $controller = CONTROLLER_NAMESPACE . ucfirst($controller) . 'Controller';
        if (!class_exists($controller)) {
            $controller = CONTROLLER_NAMESPACE . ucfirst(ERROR_CONTROLLER) . 'Controller';
        }

        $this->controller = $controller;
    }

    private function setAction($action)
    {
        if(!method_exists(new $this->controller, $action . 'Action')) {
            $this->controller = ERROR_CONTROLLER_PATH;
        } else {
            $this->action = $action;
        }
    }

    private function setParams(array $params)
    {
        $this->params = $params;
    }

    public function run()
    {
        call_user_func_array(array(new $this->controller, $this->action . 'Action'), $this->params);
    }
}