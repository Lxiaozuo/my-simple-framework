<?php
/**
 * Created by PhpStorm.
 * User: summer.zuo
 * Date: 2018/7/6
 * Time: 17:16
 */
class test
{
    public function __construct( $a,$b)
    {

    }
}
class A
{

}
class Instance
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public static function of($id)
    {
//        $t = new static($id);

    }

    public function __get($name)
    {
        $getter = 'get' . $name;
        var_dump($getter);die;
        $this->$getter();

    }

    public function getTest()
    {
        var_dump('tgsss');die;
    }

    public function __set($name, $value)
    {
        var_dump($name,1114444);die;
        // TODO: Implement __set() method.
    }
}
$a['test'][] = '123';

var_dump($a['test']);
//var_dump($instance->ida = 232);
// 方法名不区分大小写