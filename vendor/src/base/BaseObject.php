<?php
namespace sf\base;

class BaseObject
{
    public function __get($name)
    {
        $getter = "get" . $name;
        if (method_exists($this,$getter)) {
            $this->$getter();
            return ;
        }
        throw new \Exception('can\'t find this' . $getter);
    }

    public function __set($name, $value)
    {
        $setter = "set" . ucwords($name);
        if (method_exists($this,$setter)) {
            $this->$setter($value);
            return ;
        }

        throw new \Exception('can\'t find this' . $setter);
    }
}