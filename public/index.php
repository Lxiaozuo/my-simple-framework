<?php
define('SF_PATH', dirname(__DIR__));

// composer autoload
require_once(SF_PATH . '/vendor/autoload.php');

//require_once(SF_PATH . '/vendor/src/Sf.php');

$application = new sf\web\Application();
$application->run();
