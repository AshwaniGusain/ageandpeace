<?php

namespace Snap\Decorator;

class DecoratorFactory {

    protected $decoratorTypes;

    const DEFAULT_DATA_TYPE = 'string';

    const NAME_TYPE_DELIMITER = ':';

    public function __construct($decoratorTypes = null)
    {
        $this->decoratorTypes = new DecoratorCollection($decoratorTypes);
    }

    public function cast($value, $type = null, $name = null, $props = null)
    {
        if (is_null($type) || $type === true) {
            $class = $this->detect($value, $name);

        } elseif (is_string($type) && $this->decoratorTypes->has($type)) {
            $class = $this->decoratorTypes->get($type);

        }

        if (empty($class)) {
            if ($type == 'object' || class_exists($type)) {
                $class = $type;

            } else {
                $class = $this->decoratorTypes->get(self::DEFAULT_DATA_TYPE);
            }
        }

        if ( ! empty($class)) {
            return new $class($value, $name, $props);
        }

        return $value;
    }

    public function types($flipped = false)
    {
        if ($flipped) {
            return $this->decoratorTypes->flip();
        }

        return $this->decoratorTypes->all();
    }

    public function hasType($type)
    {
        return $this->decoratorTypes->has($type);
    }

    public function bind($type, $class = null)
    {
        if (is_array($type)) {

            foreach($type as $t => $c) {
                $params = $this->normalizeParameters($c);
                $this->decoratorTypes[$t] = $params;
            }
        } else {
            $this->decoratorTypes[$type] = $this->normalizeParameters($class);
        }

        return $this;
    }

    protected function autoPriority()
    {
        return count($this->decoratorTypes);
    }

    protected function normalizeParameters($class)
    {
        if (is_array($class)) {
            $params = $class;

            // Set the priority for detection.
            if (isset($params['priority'])){
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

        if (!isset($priority)) {
            $priority = $this->autoPriority();
        }

        $params = array('class' => $class, 'priority' => $priority);

        return $params;
    }

    public function detect($value, $name)
    {
        foreach($this->decoratorTypes->all() as $class) {
            $detect = $class.'::detect';
            if ((is_array($value) && $class::$allowArrays === true) || ! is_array($value)) {
                if (call_user_func($detect, $value, $name) === true) {
                    return $class;
                }
            }
        }

        return false;
    }
    
    public static function hasTypeInName($name)
    {
        return strpos($name, ':') !== false;
    }

    public static function parseNameType($name)
    {
        $type = null;
        if (static::hasTypeInName($name)) {
            $nameParts = explode(self::NAME_TYPE_DELIMITER, $name);

            $name = end($nameParts);
            if (count($nameParts) == 2) {
                $type = $nameParts[0];
            }
        }

        return [$name, $type];
    }
}