<?php
/**
 * Created by PhpStorm.
 * User: summer.zuo
 * Date: 2018/7/20
 * Time: 11:11
 */

namespace sf\web;


class JsonParse
{
    public function parseRequest($value)
    {
        return json_decode($value, true);
    }
}