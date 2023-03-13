<?php

namespace Snap\Docs\Inspector;

use Snap\Support\Contracts\ToString;

class ParameterInspector implements ToString
{
    use BaseReflectionTrait;

    public $name;
    protected $reflection;

    public function __construct(\ReflectionParameter $param)
    {
        $this->reflection = $param;
        $this->name = $this->reflection->getName();
    }

    public function type()
    {
        return $this->reflection->getType();
    }

    public function asString()
    {
        $str = '';
        if ($this->hasType()) {
            $str .= (string) $this->type().' ';
        }
        $str .= '$'.$this->name;
        if ($this->hasDefault()) {
            $str .= ' = '.$this->defaultAsString();
        }

        return $str;
    }

    public function default()
    {
        return $this->reflection->getDefaultValue();
    }

    public function hasDefault()
    {
        return $this->reflection->isDefaultValueAvailable();
    }

    public function defaultAsString()
    {
        if ($this->hasDefault()) {
            $val = $this->default();

            if (is_null($val)) {
                return 'NULL';
            } else if (is_bool($val)) {
                return ($val === TRUE) ? 'TRUE' : 'FALSE';
            } else if (is_string($val)) {
                return '\''.$val.'\'';
            } else {
                // remove extra spaces and lowercase
                return preg_replace('#\s*#', '', strtolower(print_r($val, TRUE)));
            }
        }

        return '';
    }

    public function __toString()
    {
        return $this->asString();
    }

}