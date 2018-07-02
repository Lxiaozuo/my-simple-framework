<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2018/6/28
 * Time: 22:17
 */

namespace sf\web;


use sf\Sf;

class Request extends \sf\base\Request
{

    public function resolve()
    {
        list($router, $params) = $this->getRequestParams();
        list(,$controllerName, $actionName) = explode('/', $router);
        $controller = $this->createController($controllerName);
        return $controller->runAction($actionName, $params);
    }

    /**
     * 获取请求参数
     */
    public function getRequestParams()
    {
        $router = $this->getPathInfo();
        $params = $this->getRequestString();

        return [$router, $params];
    }

    /**
     * 创建控制器
     */
    public function createController($controllerName)
    {
        $ucController = ucfirst($controllerName);
        $controllerNameAll = $this->controllerNamespace . '\\' . $ucController . 'Controller';
        $controller = Sf::createObject($controllerNameAll);
        return $controller;
    }

}