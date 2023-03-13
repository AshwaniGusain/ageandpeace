<?php

if (!function_exists('decorate')) {
    
    function decorate($value, $type = null, $name = null, $props = null) 
    {
        return \Decorator::cast($value, $type, $name, $props);
    }
}