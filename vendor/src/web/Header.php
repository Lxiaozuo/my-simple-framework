<?php
/**
 * Created by PhpStorm.
 * User: summer.zuo
 * Date: 2018/7/20
 * Time: 14:15
 */

namespace sf\web;


use sf\base\BaseObject;

class Header extends BaseObject
{

    private $_headers = [];

    public function add($name, $value)
    {
        $name = strtolower($name);
        $this->_headers[$name][] = $value;
        return $this;
    }

    public function get($name ,$defaultValue = null, $first)
    {
        $name = strtolower($name);
        if (isset($this->_headers[$name])) {
            return $first ? reset($this->_headers[$name]) : $this->_headers[$name];
        }

        return $defaultValue;
    }

    public function has($name)
    {
        $name = strtolower($name);
        if (isset($this->_headers[$name])) {
            return true;
        } else {
            return false;
        }
    }
}