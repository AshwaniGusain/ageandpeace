<?php

namespace Snap\Decorator\Contracts;

interface DecoratorInterface
{   
    public function __construct($value);

    public static function detect($value, $name = null);

    public function wrap();

    public function reset();

    public function __get($name);

    public function __toString();
}
