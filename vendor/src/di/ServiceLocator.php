<?php
/**
 * 服务定位器
 */
namespace sf\di;

use sf\Sf;

class ServiceLocator 
{
    // 存储的为对象
    private $_components;

    public static $config;

    public function get($id)
    {
        if (isset($this->_components[$id])) {
            return $this->_components[$id];
        }
        $component = $this->getComponent($id);
        
        return $this->_components[$id] = Sf::createObject($component);
    }

    public function getComponents($id)
    {

        $components = array_merge(
            $this->getCoreComponents(),
            isset(self::$config['components']) ? self::$config['components'] : []
        );

        if (!isset($components[$id])) {
            throw new \Exception(" $id components doesn't exists");
        }

        return $components[$id];
    }
}