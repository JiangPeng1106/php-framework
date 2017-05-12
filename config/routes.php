<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/5/12
 * Time: 12:59
 */

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r){
    $r->addRoute('GET','/',function (){
        echo 555;
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
        break;
}