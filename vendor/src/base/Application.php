<?php

namespace sf\base;

use Exception;
use sf\di\ServiceLocator;

/**
 * Application is the base class for all application classes.
 * @author Harry Sun <sunguangjun@126.com>
 */
class Application extends ServiceLocator
{
    /**
     * @var string the namespace that controller classes are located in.
     * This namespace will be used to load controller classes by prepending it to the controller class name.
     * The default namespace is `app\controllers`.
     */
    public $controllerNamespace = 'app\\controllers';

    /**
     * Runs the application.
     * This is the main entrance of an application.
     */
    public function run()
    {
        try {
            return $this->handleRequest();
        } catch (Exception $e) {
            return $e;
        }
    }

    public function getCoreComponents()
    {
        return [
            'request' => [
                'class' => 'sf\web\Request'
            ]
        ];
    }
}