<?php

namespace sf\di;
/**
 * 通过构造器注入的方法,所以要从构造器入手
 * Class Container
 * @package sf\di
 */
class Container
{
    private $_sigletons;

    private $_reflections;

    private $_dependencies;

    /**
     * 获取类
     * $params array 构造函数参数的值
     * $config array 扩展参数
     */
    public function get($class, $params = [], $config = [])
    {
        if (isset($this->_sigletons[$class])) {
            return $this->_sigletons[$class];
        }

        $this->build($class, $params, $config);

    }

    public function build($class, $params = [], $config = [])
    {
        list($reflection, $dependencies) = $this->getDependencies($class);

        foreach ($params as $key => $param) {
            $dependencies[$key] = $param;
        }
        $dependencies = $this->resolveDependencies($dependencies);

        $this->_dependencies[$class] = $dependencies;

        // 实例化
        $object = $reflection->newInstanceArgs($dependencies);

        foreach ($config as $name=>$item) {
            $object->$name = $item;
        }

        return $object;
    }

    public function getDependencies($class)
    {
        if (isset($this->_reflections[$class]) && isset($this->_dependencies[$class])) {
            return [$this->_reflections[$class], $this->_dependencies[$class]];
        }

        $dependencies = [];
        $reflection = new \ReflectionClass($class);
        $constructor = $reflection->getConstructor();
        // 如果存在构造函数,则循环构造函数的参数
        if ($constructor !== null) {
            foreach ($constructor->getParameters() as $parameter) {
                if (version_compare(PHP_VERSION, '5.6.0', '>=') && $parameter->isVariadic()) {
                    break;
                } elseif ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    // 剩下的类型都为object,包括object以及变量类型
                    $c = $parameter->getClass();
                    $dependencies[] = Instance::of($c === null ? null : $c->getName());
                }
            }
        }
        $this->_reflections[$class] = $reflection;

        return [$reflection, $dependencies];
    }

    /**
     * 当构造函数依赖其他类的时候,需要执行此函数,递归调用
     * @param $dependencies
     */
    public function resolveDependencies($dependencies)
    {
        foreach ($dependencies as $key => $dependency) {
            // 如果为类,则递归调用get()
            if ($dependency instanceof Instance && $dependency !== null) {
                $dependencies[$key] = $this->get($dependency->getName());
            }
        }
        return $dependencies;
    }
}