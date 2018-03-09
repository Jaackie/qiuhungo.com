<?php
/**
 * Created by PhpStorm.
 * User: jaackie
 * Date: 2017/3/11
 * Time: 下午3:53
 */

define('APPLICATION_PATH', dirname(__DIR__));

function __d($args)
{
    echo "<pre>";
    var_dump($args);
    echo "</pre>";
}

$application = new Yaf_Application(APPLICATION_PATH . "/conf/application.ini");
$application->bootstrap()->run();
