<?php

namespace Snap\Docs\Inspector;

class InterfaceInspector
{
    use BaseReflectionTrait;

    public $name;
    protected $reflection;

    public function __construct($interface)
    {
        $this->reflection = $interface;
        $this->name = $interface->getName();
    }

}