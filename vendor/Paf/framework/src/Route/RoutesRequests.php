<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/1
 * Time: 15:11
 */
namespace Paf\Route;

use FastRoute\Dispatcher;
use Symfony\Component\HttpFoundation\Request;

trait RoutesRequests
{
    protected $routes;
    protected $dispatcher;
    public function get($url, $action){
        $this->addRoute("GET", $url, $action);
    }
    public function group(){

    }

    protected function createDispatcher(){
        return $this->dispatcher?:\FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r){
            foreach ($this->routes as $route){
                $r->addRoute($route['method'], $route['uri'], $route['action']);
            }
        });
    }

    public function addRoute($method, $uri, $action){
        $action = $this->parseAction($action);

        if(is_array($method)){

        }else{
            $this->routes[$method.$uri] = ['method' => $method, 'uri' => $uri, 'action' => $action];
        }
    }

    public function parseAction($action){
        if(is_string($action)){
            return ['uses' => $action];
        }elseif (!is_array($action)){
            return [$action];
        }
    }

    public function dispatcher($request = null){
        list($method,$pathInfo) = $this->parseIncomingRequest($request);
        $this->handleDispatcherResponse(
            $this->createDispatcher()->dispatch($method, $pathInfo)
        );
    }

    protected function parseIncomingRequest($request){
        if(!$request){
            $request = Request::createFromGlobals();
        }
        return [$request->getMethod(), $request->getPathInfo()];
    }

    protected function handleDispatcherResponse($routeInfo){
        switch ($routeInfo[0]){
            case Dispatcher::METHOD_NOT_ALLOWED:
                break;
            case Dispatcher::NOT_FOUND:
                break;
            case Dispatcher::FOUND:
                $this->handleFoundRoute($routeInfo);
                break;
        }
    }
    protected function handleFoundRoute($routeInfo){
        return $this->prepareResponse();
    }

    protected function prepareResponse(){

    }
    protected function callActionBaseRoute($routeInfo){
        $action = $routeInfo[1];
        if(isset($action['uses'])){
            $this->callControllerAction($routeInfo);
        }
    }

    protected function callControllerAction($routeInfo){
        $uses = $routeInfo[1]['uses'];
        if(is_string($uses) && (mb_strpos($uses, '@') === false)){
            $uses .= '@__invoke';
        }
    }

    protected function callControllerCallable(callable $callable, array $parameters = []){
        call_user_func_array($callable, $parameters);
    }

    public function run($request = null){
        $this->dispatcher($request);
    }
}