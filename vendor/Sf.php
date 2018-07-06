<?php
/**
 * 用于链接serviceLocator 与 container
 * User: summer.zuo
 * Date: 2018/7/6
 * Time: 15:48
 */
namespace sf;
use sf\di\Container;

class Sf
{
    public static $container;

    public static function createObject($type ,$params = [])
    {
        if (self::$container === null) {
            self::$container = new Container;
        }

        if (is_string($type)) {
            return self::$container->get($type);
        } elseif (is_array($type) && isset($type['class'])) {
            $class = $type['class'];
            unset($type['class']);
            return self::$container->get($class ,$params ,$type);
        } elseif (is_callable($type)) {
            return self::$container->invoke($type);
        } elseif (is_object($type)) {
            return $type;
        } else {
            throw new \Exception("format error");
        }


    }

}