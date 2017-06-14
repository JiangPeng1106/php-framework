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
    /**
     * The registered type aliases.
     *
     * @var array
     */
    protected $aliases = [];

    /**
     * The current globally available container (if any).
     *
     * @var static
     */
    protected static $instance;

    /**
     * The container's shared instances.
     *
     * @var array
     */
    protected $instances = [];

    protected $buildStack = [];


    public function bind($abstract, $concrete, $shared = false){
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
       if(isset($this->instances[$abstract])) {
           return $this->instances[$abstract];
       }
        return $this->bind($abstract,$parameters = []);
    }

    public function call($callback, array $parameters = [], $defaultMethod = null){
            BoundMethod::call($this, $callback, $parameters, $defaultMethod);
    }

    /**
     * Set the shared instance of the container.
     *
     * @param $container
     * @return mixed
     *
     */
    public static function setInstance($container){
        return static::$instance = $container;
    }

    public function instance($abstract, $instance){

        $this->instances[$abstract] = $instance;

    }
//    final public function __clone()
//    {
//        // TODO: Implement __clone() method.
//    }
}