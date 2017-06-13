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
$ab= 1;
$app->get('/ab',function ()use($ab){
    echo $ab;
});
$app->get('/t/a/1','HomeController@home');
$app->run();
