<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2018/6/28
 * Time: 22:18
 */

namespace sf\base;


class Request extends BaseObject
{
    public $controllerNamespace = 'app\\controllers';

    public $defaultRouter = 'site/index';

    public function __construct()
    {

    }

    public function getPathInfo()
    {
        return $_SERVER['PATH_INFO'];
    }

    /**
     * 获取请求参数
     * @return mixed
     */
    public function getRequestString()
    {
        return $_SERVER['QUERY_STRING'];
    }
}