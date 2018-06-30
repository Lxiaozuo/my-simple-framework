<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2018/6/28
 * Time: 22:17
 */

namespace sf\web;


class Request extends \sf\base\Request
{

    public function resolve()
    {
        $router = $_GET['r'];
        list($controllerName, $actionName) = explode('/', $router);
        $ucController = ucfirst($controllerName);
        $controllerNameAll = $this->controllerNamespace . '\\' . $ucController . 'Controller';
        $controller = new $controllerNameAll();
        $controller->controllerId = $controllerName;
        $controller->actionId = $actionName;
        return call_user_func([$controller, 'action' . ucfirst($actionName)]);
    }

}