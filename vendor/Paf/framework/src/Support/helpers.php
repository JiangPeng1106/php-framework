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

if(! function_exists('data_get')){
    /**
     * Get an item from array or object using 'dot' notation.
     *
     * @param $target
     * @param $key
     * @param null $default
     * @return mixed
     */
    function data_get($target, $key, $default = null){
        if(is_null($key)){
            return $target;
        }

        $key = is_array($key) ? $key : explode('.', $key);
        while (! is_null($segment = array_shift($key))){
            if($segment === '*'){
                if($target instanceof Collection){
                    $target = $target->all();
                }elseif (! is_array($target)){
                    return value($target);
                }

                $result = Arr::pluck($target, $key);

                return in_array('*', $key) ? Arr::collapse($result) : $result;
            }
            if(Arr::accesible($target) && Arr::exists($target, $segment)){
                $target = $target[$segment];
            }elseif (is_object($target) && isset($target->{$segment})){
                $target = $target->{$segment};
            }else{
                return value($target);
            }
        }
    }
}