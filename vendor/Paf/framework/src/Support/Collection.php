<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/6/15
 * Time: 15:14
 */

namespace Paf\Support;

use Countable;
use Exception;
use ArrayAccess;
use function foo\func;
use Traversable;
use ArrayIterator;
use CachingIterator;
use Serializable;
use IteratorAggregate;
use InvalidArgumentException;
use Paf\Support\Traits\Macroable;
use Paf\Contracts\Support\Jsonable;
use Paf\Contracts\Support\Arrayable;

class Collection implements ArrayAccess, Arrayable, Countable, IteratorAggregate, Jsonable, JsonSerializable
{
    use Macroable;

    /**
     * The items contained in the collection
     *
     * @var array
     */
    protected $items = [];

    /**
     * The methods that can be proxied
     *
     * @var array
     */
    protected static $proxies = [
        'contains', 'each', 'every', 'filter', 'first', 'flatMap', 'map',
        'partition', 'reject', 'sortBy', 'sortByDesc', 'sum',
    ];

    /**
     * Create a new collection.
     *
     * Collection constructor.
     * @param mixed $items
     */
    public function __construct($items = [])
    {
        $this->items = $this->getArrayableItems($items);
    }

    /**
     * Create a new collection instance if the value isn't one already.
     *
     * @param array $items
     * @return static
     */
    public function make($items = []){
        return new static($items);
    }

    /**
     * Create a new collection by invoking the callback a given amount of times.
     *
     * @param int $amount
     * @param callable $callback
     * @return static
     */
    public static function times($amount, callable $callback){
        if($amount < 1){
            return new static;
        }
        return (new static(range(1, $amount)))->map($callback);
    }

    /**
     * Run a map over each of items.
     *
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback){
        $keys = array_keys($this->items);
        $items = array_map($callback, $this->items, $keys);
        return new static(array_combine($keys, $items));
    }

    /**
     * Get all of the items in the collection.
     *
     * @return array
     */
    public function all(){
        return $this->items;
    }

    /**
     * Get the collection of the items as a plain array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function($value){
            return $value instanceof Arrayable ? $value->toArray(): $value;
        }, $this->items);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize(){
        return array_map(function ($value){
            if ($value instanceof JsonSerializable){
                return $value->jsonSerialize();
            }elseif ($value instanceof  Jsonable){
                return json_decode($value->toJson(), true);
            }elseif ($value instanceof Arrayable){
                return $value->toArray();
            }else{
                return $value;
            }
        }, $this->items);
    }

    /**
     * Get the collection of items as Json
     *
     * @param int $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Results array of  items from Collection or Arrayable.
     *
     * @param mixed $items
     * @return array
     */
    protected function getArrayableItems($items){
        if(is_array($items)){
            return $items;
        }elseif ($items instanceof self){
            return $items->all();
        }elseif ($items instanceof Arrayable){
            return $items->toArray();
        }elseif ($items instanceof Jsonable){
            return json_decode($items->toJson(), true);
        }elseif ($items instanceof JsonSerializable){
            return $items->jsonSerialize();
        }elseif ($items instanceof Traversable){
            return iterator_to_array($items);
        }
        return (array) $items;
    }

    /**
     * Get the average value of a given key.
     * @param callable|string|null $callback
     * @return float|int
     */
    public function avg($callback = null){
        if($count = $this->count()){
            return $this->sum($callback) / $count;
        }
    }

    /**
     * Alias fot the 'avg' methods.
     *
     * @param callable|string|null  $callback
     * @return float|int
     */
    public function average($callback){
        return $this->avg($callback);
    }

    /**
     * Count the numbers of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * Get the sum of the given values.
     *
     * @param callable|string|null $callback
     * @return float|int
     */
    public function sum($callback = null){
        if(is_null($callback)){
            return array_sum($this->items);
        }
        $callback = $this->valueRetriever($callback);
        return $this->reduce(function ($result, $item) use ($callback){
            return $result + $callback($item);
        }, 0);
    }

    public function median($key = null){
        $count = $this->count();
        if($count == 0){
            return;
        }
        $values = with(isset($key) ? $this->pluck($key) : $this)->sort()->values();
    }

    /**
     * Get a value retrieving callback.
     *
     * @param string $value
     * @return callable
     */
    protected function valueRetriever($value){
        if($this->useAsCallable($value)){
            return $value;
        }

        return function ($item) use ($value){
            return data_get($item, $value);
        };
    }

    /**
     * Determine if the given value is callback , but not a string.
     *
     * @param mixed $value
     * @return bool
     */
    protected function useAsCallable($value){
        return !is_string($value) && is_callable($value);
    }
}