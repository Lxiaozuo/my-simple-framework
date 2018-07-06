<?php
namespace sf\web;
use \sf;
/**
 * Application is the base class for all application classes.
 */
class Application extends sf\base\Application
{
    public function __construct($config)
    {
        $this->loadConfig($config);
    }

    public function handleRequest()
    {
        $request = $this->getRequest();
        return $request->resolve();
    }

    public function getRequest()
    {
        return $this->get('request');
    }


    public function loadConfig($config)
    {
        parent::$config = $config;
    }

//    /**
//     * Handles the specified request.
//     * @return Response the resulting response
//     */
//    public function handleRequest()
//    {
//        $router = $_GET['r'];
//        list($controllerName, $actionName) = explode('/', $router);
//        $ucController = ucfirst($controllerName);
//        $controllerNameAll = $this->controllerNamespace . '\\' . $ucController . 'Controller';
//        $controller = new $controllerNameAll();
//        $controller->controllerId = $controllerName;
//        $controller->actionId = $actionName;
//        return call_user_func([$controller, 'action'. ucfirst($actionName)]);
//    }
}