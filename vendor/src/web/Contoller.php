<?php

namespace sf\web;

class Contoller extends t
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