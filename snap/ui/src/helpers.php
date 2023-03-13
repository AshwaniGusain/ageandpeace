<?php

if (!function_exists('ui')) {
    
    function ui($name, $data = [], $parent = null)
    {
        return \UI::make($name, $data, $parent);
    }
}