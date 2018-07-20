<?php
/**
 * 服务定位器
 */

namespace sf\di;

use Exception;
use sf\base\BaseObject;
use sf\Sf;

class ServiceLocator extends BaseObject
{
    // 存储的为对象
    private $_components;

    private $_definitions;

    public static $config;

    public function get($id)
    {
        if (isset($this->_components[$id])) {
            return $this->_components[$id];
        }

        if (!empty($this->_definitions[$id])) {
            $component = $this->_definitions[$id];
        } else {
            $component = $this->getComponent($id);
        }



        return $this->_components[$id] = Sf::createObject($component);
    }

    /**
     * Register a class definition with the container
     * $locator->set('db','sf\db\Connection');
     * $locator->set('db',[
     *      'class' =>  'sf\db\Connection'
     * ]);
     * $locator->set('db',function(){});
     *   // an instance
     * $locator->set('cache', new \yii\caching\FileCache);
     */
    public function set($id, $definition)
    {
        unset($this->_components[$id]);

        if ($definition === null) {
            unset($this->_definitions[$id]);
            return;
        }

        if ( is_object($definition) || is_callable($definition)) {
            $this->_definitions[$id] = $definition;
        } elseif (is_array($definition)) {
            if (isset($definition['class'])) {
                $this->_definitions[$id] = $definition['class'];
            } else {
                throw new Exception("not have the class property");
            }
        } else {
            throw new Exception('Unexpected type');
        }

    }

    public function getComponent($id)
    {

        $components = array_merge(
            $this->getCoreComponents(),
            isset(self::$config['components']) ? self::$config['components'] : []
        );

        if (!isset($components[$id])) {
            throw new Exception(" $id components doesn't exists");
        }

        return $components[$id];
    }

    public function setComponents($components)
    {
        foreach ($components as $id=>$component) {
            $this->set($id, $component);
        }
    }
}