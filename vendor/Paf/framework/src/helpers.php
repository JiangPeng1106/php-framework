<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/2
 * Time: 16:54
 */

if(! function_exists('value')){
    /**
     * Return the default value of the given value.
     *
     * @param mixed $value
     * @return mixed
     */
    function value($value){
       return $value instanceof Closure ? $value() : $value;
    }
}