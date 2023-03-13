<?php

namespace Snap\Decorator;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;

class DecoratorCollection implements ArrayAccess, Countable, IteratorAggregate {

    protected $types = [];

    public function __construct($types = [])
    {
        foreach($types as $t => $c) {
            $this->types[$t] = $this->normalizeParameters($c);
        }
    }

    protected function normalizeParameters($class)
    {
        if (is_array($class)) {
            $params = $class;

            // Set the priority for detection.
            if (isset($params['priority'])) {
                $priority = $params['priority'];

            } elseif (array_key_exists(1, $params)) {
                $priority = $params[1]; 
            }

            // Set the decorator class.
            if (isset($params['class'])) {
                $class = $params['class'];

            } elseif (array_key_exists(0, $params)) {
                $class = $params[0];
            }
        }

        if (! isset($priority)) {
            $priority = count($this->types);
        }

        return ['class' => $class, 'priority' => $priority];
    }
    /**
     * Get an item from the collection by key.
     *
     * @param  mixed  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $all = false)
    {
        if ($this->offsetExists($key)) {
            $type = $this->types[$key];

            if ( ! $all) {
                return $type['class'];
            }

            return $type;
        }
    }

    /**
     * Get an iterator for the items.
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        return new ArrayIterator($this->all());
    }

    /**
     * Count the number of items in the collection.
     *
     * @return int
     */
    public function count()
    {
        return count($this->all());
    }

    public function all($all = false)
    {
        if ($all) {
            return $this->types;    
        }

        $types = [];
        foreach($this->types as $key => $type) {
            $types[$key] = $type['class'];
        }

        return $types;
    }

    /**
     * Flip the items in the collection.
     *
     * @return static
     */
    public function flip()
    {
        return array_flip($this->all());
    }

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function has($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return array_key_exists($key, $this->types);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->types[$key];
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
        if (is_null($key)) {
            $this->types[] = $value;
        } else {
            $this->types[$key] = $value;
        }
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->types[$key]);
    }
}