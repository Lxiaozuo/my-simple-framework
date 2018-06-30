<?php

namespace sf\web;
use sf\base\BaseObject;
use sf\Sf;

//use sf\sf;

/**
 * Application is the base class for all application classes.
 */
class Application extends \sf\base\Application
{
    /**
     * Handles the specified request.
     * @return Response the resulting response
     */
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
    private $_components = [];

    public function handleRequest()
    {
        $request = $this->getRequest();
        // 实例化类
        $requestClass = Sf::createObject($request);
        // 处理请求
        $requestClass->resolve();
    }

    /**
     * 获取request对象
     * @return array
     */
    public function getRequest()
    {
        return $this->get('request');
    }

    // 拉取配置信息
    public function get($id)
    {
        if(isset($this->_components[$id])){
            return $this->_components[$id];
        }
        $components = [];
        foreach ($this->coreCopment() as $module=>$item) {
            if ($id == $module) {
                $components[$id] = $item;
                $this->_components[$id] = $item;
            }
        }

        return $this->_components[$id];
    }

    public function coreCopment()
    {
        return array_merge(parent::coreCopment(),[
           'request'    => ['class' => 'sf\web\Request']
        ]);
    }
}