<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1
 * Time: 14:07
 */
require_once __DIR__.'/../vendor/autoload.php';

$app = new Paf\Application(
    realpath(__DIR__.'/../')
);
$app->get('/ab',function (){
    echo 11;
});
$app->run();
