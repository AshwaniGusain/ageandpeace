<?php
if (!function_exists('snap')) {

    function snap()
    {
        return app('snap');
    }
}

if (!function_exists('snap_path')) {

    function snap_path($path = null)
    {
        $path = ltrim($path, '/');
        return realpath(__DIR__ .'/../../').'/'.$path;
    }
}