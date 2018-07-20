<?php
/**
 * Created by PhpStorm.
 * User: summer.zuo
 * Date: 2018/7/19
 * Time: 17:15
 */
namespace sf\base;

abstract class Request extends BaseObject
{

    abstract public function resolve();
}