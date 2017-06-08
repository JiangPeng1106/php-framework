<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/2
 * Time: 17:57
 */
namespace Paf\Container;
class Container
{
    protected $alias = [];

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

//    final public function __clone()
//    {
//        // TODO: Implement __clone() method.
//    }
}