<?php
namespace sf\base;

class Contoller
{
    public $controllerId;

    public $actionId;

    public function runAction($actionName, $params)
    {
        $action = $this->createAction($actionName);
        return $this->$action($params);
    }

    public function createAction($actionName)
    {
        $action = 'action' . ucfirst($actionName);
        return $action;
    }

}
