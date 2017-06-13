<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/12
 * Time: 17:04
 */

namespace Paf\Container;


class BoundMethod
{

    /**
     * Call the given Closure / class@method and inject its dependencies.
     *
     * @param $container
     * @param $callback
     * @param array $parameters
     * @param null $defaultMethod
     * @return mixed
     */
    public static function call($container, $callback, array $parameters = [], $defaultMethod = null){
        return call_user_func_array($callback, static::getMethodDependencies($container, $callback, $parameters));
    }


    /**
     * Get all dependencies for a given method.
     *
     * @param $container
     * @param $callback
     * @param array $parameters
     * @return  array
     */
    public static function getMethodDependencies($container, $callback, array $parameters = []){
        $dependencies = [];
        foreach (static::getCallReflector($callback)->getParameters() as $parameter){
            static::addDependencyForCallParameter($container, $parameter, $parameters, $dependencies);
        };
        return array_merge($parameters, $dependencies);
    }

    /**
     * Get the proper reflection instance for the given callback.
     *
     * @param $callback
     * @return ReflectionMethod|\ReflectionFunction
     */
    public static function getCallReflector($callback){
        if(is_string($callback) && strpos($callback, '::') !== false ){
            $callback = explode('::'. $callback);
        }
        return is_array($callback)
                    ? new \ReflectionMethod($callback[0], $callback[1])
                    : new \ReflectionFunction($callback);
    }

    /**
     * Get the dependency for the given call parameter.
     *
     * @param $container
     * @param $parameter
     * @param $parameters
     * @param $dependencies
     */
    public static function addDependencyForCallParameter($container, $parameter, &$parameters, &$dependencies){
        if(array_key_exists($parameter->name, $parameters)){
            $dependencies[] = $parameters[$parameter->name];
            unset($parameters[$parameter->name]);
        }elseif ( $parameter->getClass() ){
            $dependencies[] = $container->make($parameter->getClass()->name);
        }elseif ($parameter->isDefaultValueAvialable()){
            $dependencies[] = $parameter->getDefaultValue();
        }
    }
}