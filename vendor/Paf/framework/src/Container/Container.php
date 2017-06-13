<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/2
 * Time: 17:57
 */
namespace Paf\Container;

use Paf\Container\BoundMethod;

class Container
{
    protected $alias = [];

    /**
     * The container's shared instances.
     *
     * @var array
     */
    protected $instance = [];

    protected $buildStack = [];
    public function bind($abstract){
        if($abstract instanceof \Closure){
            return $abstract;
        }
        $reflector =  new \ReflectionClass($abstract);
        if(! $reflector->isInstantiable()){
            return ;
        }
        $this->buildStack[] = $abstract;
        $constructor = $reflector->getConstructor();
        if(is_null($constructor)){
            array_pop($this->buildStack);
            return new $abstract;
        }
        return $reflector->newInstanceArgs();
    }

    public function make($abstract){
        return $this->resolve($abstract);
    }

    public function resolve($abstract){

        return $this->bind($abstract);
    }

    public function call($callback, array $parameters = [], $defaultMethod = null){
            BoundMethod::call($this, $callback, $parameters, $defaultMethod);
    }

//    final public function __clone()
//    {
//        // TODO: Implement __clone() method.
//    }
}