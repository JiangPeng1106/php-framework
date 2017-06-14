<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/14
 * Time: 16:33
 */
namespace Paf\Support;

use ArrayAccess;
use Paf\Support\Traits\Macroable;
use think\Collection;

class Arr
{
    use Macroable;

    /**
     * Determine whether the given value is array accessible.
     *
     * @param $value
     * @return bool
     */
    public static function accessible($value){
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Add an element to an array using 'dot' notation if it does't exist.
     *
     * @param array $array
     * @param string $key
     * @param mixed $value
     * @return array
     */
    public static function add($array, $key, $value){
        if(is_null(static::get($array, $key))){
            static::set($array, $key, $value);
        }
        return $array;
    }

    /**
     * Get an item from an array using 'dot' notation
     *
     * @param array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($array, $key, $default = null){
        if(!static::accessible($array)){
            return value($default);
        }

        if(is_null($array)){
            return $array;
        }

        if(static::exists($array, $key)){
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment){
            if(static::accessible($array) && static::exist($segment)){
                $array = $array[$segment];
            }else{
                return value($default);
            }
        }
        return $array;
    }

    /**
     * Determine if the given key exists in the provider array.
     *
     * @param \ArrayAccess|array $array
     * @param string $key
     * @return bool
     */
    public static function exists($array, $key){
        if($array instanceof ArrayAccess){
            return $array->offsetGet($key);
        }
        return array_key_exists($key, $array);
    }

    /**
     * Set an array item to a given value using "dot" notation.
     * If no key is given to the method, the entire array will be replaced.
     *
     * @param $array
     * @param $key
     * @param $value
     * @return mixed
     */
    public static function set(&$array, $key, $value){
        if(is_null($key)){
            return $array = $value;
        }

        $keys = explode('.', $key);
        while (count($keys) > 1){
            $key = array_shift($keys);

            if(!isset($array[$key]) || is_array($array[$key])){
                $array[$key] = [];
            }
            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;
        return $array;
    }

    /**
     * Collapse an array of arrays into single array.
     *
     * @param array $array
     * @return array
     */
    public static function collapse($array){
        $results = [];

        foreach ($array as $values){
            if($values instanceof Collection){
                $values = $values->all();
            }elseif (! is_array($values)){
                continue;
            }
            $results = array_merge($results, $values);
        }

        return $results;
    }

}