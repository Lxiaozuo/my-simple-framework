<?php
/**
 * Created by PhpStorm.
 * User: summer.zuo
 * Date: 2018/7/6
 * Time: 17:16
 */
class test
{
    public function __construct( $a,$b =10)
    {

    }
}
class A
{

}
class Instance
{
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public static function of($id)
    {
//        $t = new static($id);

    }
}

$reflection = new ReflectionClass('test');
$constructor = $reflection->getConstructor();

foreach ($constructor->getParameters() as $param){
    $t = Instance::of('abc');
    var_dump($param->getClass(),$t->id);die;
}
