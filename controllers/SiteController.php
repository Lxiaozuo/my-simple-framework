<?php
/**
 * Created by PhpStorm.
 * User: summer.zuo
 * Date: 2018/6/5
 * Time: 14:09
 */

namespace app\controllers;

use app\models\User;
use sf\web\Contoller;

class SiteController extends Contoller
{
    public function actionIndex()
    {
        $model = new User();
//        $res = $model->findAll(['id'=>2]);
        $res = $model->findOne();
        var_dump($res);
//        // 插入数据
//        $model->user_name = 'haha2';
//        $model->passwd = md5('test');
//        $model->email = '3335@qq.com';
//        $model->insert();
//        var_dump($model);
//        $ret = $model->updateAll(['id' => 2], ['user_name' => 'tototo', 'email'=>'cici@mail.com']);
//        $ret = $model->deleteAll(['user_name'=>'haha']);
//        $model = User::findOne(['id'=>1]);
//        $model->user_name = 'udpateName';
//        $model->email = 'aa@qqcc.com';
//        $model->delete();
//        var_dump($model);die;
    }

}