<?php

namespace sf\web;
use sf\base\BaseObject;

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

    public function handleRequest()
    {
        // 1.加载配置类文件
        $request = [
            'class' => 'sf\web\Request'
        ];
        // 2.根据类生成对应的对象(反射）

        // 3.对象的方式调用
    }

    public function getRequest()
    {
        return $this->get('request');
    }

    // 拉取配置信息
    public function get()
    {

    }

    public function coreCopment()
    {
        return array_merge(parent::coreCopment(),[
           'request'    => ['class' => 'sf\web\Request']
        ]);
    }
}