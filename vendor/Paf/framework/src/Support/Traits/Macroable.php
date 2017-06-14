<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/14
 * Time: 15:55
 */
namespace  Paf\Support\Traits;

use Closure;
use BadMethodCallException;
trait Macroable
{
    /**
     * The registered string macros
     *
     * @var array
     */
    protected  static $macros = [];

    /**
     * Register a custom macro
     *
     * @param string $name
     * @param callable $macro
     */
    public static function macros($name, callable $macro){
        static::$macros[$name] = $macro;
    }

    /**
     * Checks if macro is registered
     *
     * @param $name
     * @return bool
     */
    public static function hasMacro($name){
        return isset(static::$macros[$name]);
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public static function __callStatic($method, $parameters)
    {
        if(!static::hasMacro($method)){
            throw new BadMethodCallException("Method {$method} does not exist.");
        }
        if(static::$macros[$method] instanceof Closure){
            call_user_func_array(Closure::bind(static::$macros[$method], null, static::class), $parameters);
        }

        return call_user_func_array(static::$macros[$method], $parameters);
    }

    public  function __call($method, $parameters){
        if (! static::hasMacro($method)) {
            throw new BadMethodCallException("Method {$method} does not exist.");
        }
        if(static::$macros[$method] instanceof Closure){
            return call_user_func_array(static::$macros[$method]->bindTo($this, static::class), $parameters);
        }
        return call_user_func_array(static::$macros[$method], $parameters);
    }
}