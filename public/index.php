<?php
/**
 * Created by PhpStorm.
 * User: summer.zuo
 * Date: 2018/6/5
 * Time: 13:55
 */

// composer autoload
require_once(__DIR__ . '/../vendor/autoload.php');

$config = require_once(__DIR__ . '/../config/main.php');

$application = new sf\web\Application($config);

$application->run();
