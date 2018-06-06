<?php

/**
 * Sf is a helper class serving common framework functionalities.
 * Class Sf
 */
class Sf
{
    /**
     * Creates a new object using the given configuration.
     * You may view this method as an enhanced version of the `new` operator.
     * @param string $name the object name
     */
    public static function createObject($name)
    {
        // 加载相应配置文件
        $config = require(SF_PATH . "/common/config/${name}.php");
        $class = $config['class'];

        unset($config['class']);

        if (class_exists($class)) {
            $classModel = new $class();
            foreach ($config as $key => $val) {
                $classModel->$key = $val;
            }
            return $classModel;
        } else {
            // 类不存在
            return null;
        }
    }
}