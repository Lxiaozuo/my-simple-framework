<?php
/**
 * Created by PhpStorm.
 * User: summer.zuo
 * Date: 2018/6/5
 * Time: 13:55
 */

// composer autoload
require_once(__DIR__ . '/../vendor/autoload.php');

$application = new sf\web\Application();
$application->run();
