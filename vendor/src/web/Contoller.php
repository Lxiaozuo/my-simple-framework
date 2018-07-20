<?php

namespace sf\web;

class Contoller extends \sf\base\Contoller
{

    private $_defaultAction = 'index';
    /**
     * render the views
     */
    public function render($view, $params = [])
    {
        extract($params);
        $renderView = '../views/' . $view . '.php';
        return require $renderView;
    }

    public function runAction($controller, $action, $params)
    {
        if (empty($action)) {
            $action = $this->_defaultAction;
        }

        $action = 'action' . $action;
        // 跳转到controler中的action，执行action中的操作
        return $controller->$action($params);
    }
}