<?php

namespace Snap\Decorator;

use InvalidArgumentException;
use Snap\Decorator\Contracts\DecoratorInterface;

abstract class AbstractDecorator implements DecoratorInterface {

    public static $allowArrays = false;

    protected $value = null;
    protected $orig = null;
    protected $name = null;
    protected $props = [];
    protected static $decorators = [];

    public function __construct($value, $name = null, $props = [])
    {
        $this->setValue($value);
        $this->name = $name;
        $this->setProperties($props);
        $this->normalizeDecorators();
    }

    public function setProperties($props)
    {
        if (!empty($props))
        {
            $this->props = (is_string($props)) ? ['default' => $props] : $props;    
        }
        return $this;
    }

    protected function normalizeDecorators()
    {
        // normalize decorators
        foreach(static::$decorators as $key => $formatter)
        {
            if (is_int($key))
            {
                static::$decorators[$formatter] = $formatter;
                unset(static::$decorators[$key]);
            }
        }
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }
    
    public function setValue($value)
    {
        $this->orig  = $value;
        $this->value = $value;
        return $this;
    }

    // Alias to getValue()
    public function raw()
    {
        return $this->value;
    }
    
    protected function find($name, $args = [])
    {
        // check to make sure the $type exists for the decorators
        $decorators = array_keys(static::$decorators);

        // if not in either the decorators array or a method on the object, just return the value 
        if ( ! in_array($name, $decorators) && ! method_exists($this, $name)) {
            return $this->value;
        }

        if (method_exists($this, $name)) {
            $func = array($this, $name);
        } else {
            $func = static::$decorators[$name]; 
        }

        $args = array_merge(array($this->value), $args);

        if (is_callable($func)) {
            $this->value = call_user_func_array($func, $args);
        }

        return $this->value;

    }

    public function wrap($open = '', $close = '')
    {
        if (is_callable($open)) {
            $this->value = call_user_func($open, $this->value, $this->props);
        } else {
            $this->value = $open.$this->value.$close;
        }

        return $this;
    }

    public function reset()
    {
        $this->value = $this->orig;
        return $this;
    }

    public static function detect($value, $name = null)
    {
        // needs to be implemented by parent class
        return false;
    }

    public function getDecorator($name)
    {
        if (!isset(static::$decorators[$name]))
        {
            throw new InvalidArgumentException("Invalid formatter $name requested");
        }
        return static::$decorators[$name];
    }

    public function getDecorators()
    {
        return static::$decorators;
    }
    
    public function setDecorator($name, $decorator)
    {
        static::$decorators[$name] = $decorator;
        return $this;
    }

    public function __call($name, $args)
    {
        return $this->find($name, $args);
    }

    public function __get($name)
    {
        return $this->find($name);
    }

    public function __toString()
    {
        if (isset($this->props['default']) && (is_null($this->value) || $this->value === '')) {
            $this->value = $this->props['default'];
        }

        if (!empty($this->props['wrap'])) {

            if (is_array($this->props['wrap'])) {
                call_user_func_array(array($this, 'wrap'), $this->props['wrap']);
            } else {
                call_user_func(array($this, 'wrap'), $this->props['wrap']); 
            }
        }

        $value = $this->value;
        $this->reset();

        if (is_string($value)) {
            return $value;
        } else {
            return '';
        }
    }

}
