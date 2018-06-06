<?php
namespace sf\web;

/**
 * Application is the base class for all application classes.
 */
class Application extends \sf\base\Application
{
    /**
     * Handles the specified request.
     * @return Response the resulting response
     */
    public function handleRequest()
    {
        $router = $_GET['r'];
        list($controllerName, $actionName) = explode('/', $router);
        $ucController = ucfirst($controllerName);
        $controllerNameAll = $this->controllerNamespace . '\\' . $ucController . 'Controller';
        $controller = new $controllerNameAll();
        $controller->controllerId = $controllerName;
        $controller->actionId = $actionName;
        return call_user_func([$controller, 'action'. ucfirst($actionName)]);
    }
}