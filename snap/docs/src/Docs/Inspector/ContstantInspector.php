<?php

namespace Snap\Docs\Inspector;

use Snap\Support\Contracts\ToString;

class ConstantInspector implements ToString
{
    public $key;
    public $value;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function asString()
    {
        $str = $this->key.': ';
        $str .=  (is_string($this->value)) ? '"'.$this->value.'"' : $this->value;

        return $str;
    }

    public function __toString()
    {
        return $this->asString();
    }
}