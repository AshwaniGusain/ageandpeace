<?php

namespace Snap\Support\Contracts;

interface Observable
{
    public static function observe($classes);
    public static function eventName($name);
    public static function getObservableEvents();
}
