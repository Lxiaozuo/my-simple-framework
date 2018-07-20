<?php
namespace sf\web;
use sf;

/**
 * Application is the base class for all application classes.
 */
class Application extends sf\base\Application
{
    public function __construct($config)
    {
        if (!empty($config)) {
            sf\Sf::configure($this, $config);
        }
        return $this;
    }

    public function handleRequest()
    {
        $request = $this->getRequest();
        list($route, $params) = $request->resolve();
        list($controller, $action) = explode('/',$route);
        $controller = $this->createControler($controller);
        return $controller->runAction($controller, $action, $params);
    }

    public function getRequest()
    {
        return $this->get('request');
    }

    public function createControler($controller)
    {
        $controllerName = $controller . 'Controller';
        $controllerNameSpace = 'app\controllers\\';
        $object = sf\sf::createObject($controllerNameSpace . $controllerName);
        return $object;
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