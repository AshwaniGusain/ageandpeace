<?php

namespace Snap\Docs\Inspector;

class TraitInspector
{
    public $name;
    protected $trait;

    public function __construct(\ReflectionClass $trait)
    {
        $this->trait = $trait;
        $this->name = $trait->getName();
    }

}