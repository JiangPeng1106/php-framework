<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/12
 * Time: 12:59
 */
require '../vendor/nikic/fast-route/src/bootstrap.php';
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r){
    $r->addRoute('GET','/','');
    $r->addRoute('GET','/user','HomeController@home');
    $r->addGroup('/home',function (FastRoute\RouteCollector $r){
        $r->addRoute('GET','/test','HomeController@home');

    });
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri,'?')){
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);
$routeInfo = $dispatcher->dispatch($httpMethod,$uri);
switch ($routeInfo[0]){
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        //...404 not found
        break;
    case FastRoute\Dispatcher::NOT_FOUND:
        $allowedMethods = $routeInfo[1];
        //...405 method not allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        //... call $handler with $vars
        list($class, $method) = explode("@", $handler, 2);
        $namespace = "App\\Http\\Controllers\\";
        call_user_func_array(array( $namespace.$class, $method), $vars);
        break;
}