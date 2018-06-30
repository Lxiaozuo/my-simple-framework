<?php

namespace sf\web;

class Contoller extends \sf\base\Contoller
{
    /**
     * render the views
     */
    public function render($view, $params = [])
    {
        extract($params);
        $renderView = '../views/' . $view . '.php';
        return require $renderView;
    }
}