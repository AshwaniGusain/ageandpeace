<?php

namespace Snap\Decorator\Types;

use ArrayAccess;
use JsonSerializable;
use IteratorAggregate;
use Illuminate\Support\Collection;
use Snap\Support\Helpers\ArrayHelper;
use Snap\Support\Helpers\UtilHelper;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Contracts\Support\Arrayable;
use Snap\Decorator\AbstractDecorator;

class ArrayDecorator extends AbstractDecorator implements ArrayAccess, IteratorAggregate {

    public static $allowArrays = true;
    protected static $decorators = [];

    public function __construct($value, $name = null, $props = [])
    {
        $value = $this->parseArray($value);

        if (is_array($value)) {
            $this->value = new Collection($value);  
        }

        if (is_null($this->value)) {
            $this->value = new Collection();
        }
    }

    protected function parseArray($value)
    {
        /*if (is_string($value) && UtilHelper::isJson($value)) {
            $value = json_decode($value, true);

        } elseif ($value instanceof JsonSerializable) {
            $value = $value->jsonSerialize();

        } elseif ($value instanceof Jsonable) {
            $value = json_decode($value->toJson(), true);

        } elseif ($value instanceof Arrayable) {
            $value = $value->toArray();
            
        } else {
            $value = (array) $value;
        }*/

        return ArrayHelper::normalize($value);
    }

    public static function detect($value, $name = null)
    {
        return (is_array($value) || UtilHelper::isJson($value) || $value instanceof JsonSerializable || $value instanceof Jsonable || $value instanceof Arrayable);
    }

     /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return $this->value->getIterator();
    }

   /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return $this->value->count();
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->value->offsetExists($key);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->value->offsetGet($key);
    }

    /**
     * Set the item at a given offset.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        return $this->value->offsetSet($key, $value);
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        return $this->value->offsetUnset($key);
    }
}
