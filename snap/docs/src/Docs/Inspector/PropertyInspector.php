<?php

namespace Snap\Docs\Inspector;

use Snap\Support\Contracts\ToString;

class PropertyInspector implements ToString
{
    use BaseReflectionTrait;

    public $name;
    protected $reflection;

    public function __construct(\ReflectionProperty $prop)
    {
        $this->reflection = $prop;
        $this->name = $this->reflection->getName();
    }

    public function asString()
    {
        $str = '$'.$this->name;
        if ($comment = $this->comment()) {

            if ($var = $comment->tag('var')) {
                $str .= ' ('.$var.')';
            }
        }

        return $str;
    }

    public function __toString()
    {
        return $this->asString();
    }

}