<?php
namespace sf;
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
//    public static function createObject($name)
//    {
//        // 加载相应配置文件
//        $config = require(SF_PATH . "/common/config/${name}.php");
//        $class = $config['class'];
//
//        unset($config['class']);
//
//        if (class_exists($class)) {
//            $classModel = new $class();
//            foreach ($config as $key => $val) {
//                $classModel->$key = $val;
//            }
//            return $classModel;
//        } else {
//            // 类不存在
//            return null;
//        }
//    }

    public static function createObject($class)
    {
        if( is_string($class)){
            $className = $class;
        } else if ( is_array($class) && isset($class['class'])) {
            $className = $class['class'];
        }
        return self::getInstance($className);
    }

    public static function getInstance($class)
    {
        $constructorParams = [];
        // 1.反射类
        $reflection = new \ReflectionClass($class);
        // 2.获取构造函数信息
        $constructor = $reflection->getConstructor();

        // 3.获取构造函数的参数
        if($constructor){
            foreach ($constructor->getParameters() as $params) {
//            $constructorParams[] = $params;
                if ($params->isDefaultValueAvailable()) {
                    $constructorParams[] = $params->getDefaultValue();
                }
            }
        }


        // 4.判断反射类是否可实例化
        if (!$reflection->isInstantiable()) {
            // 不可实例化
            return false;
        }
        if (empty($constructorParams)){
            return $reflection->newInstanceArgs();
        }
        return $reflection->newInstanceArgs($constructorParams);
    }
}