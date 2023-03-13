<?php

if (!function_exists('admin_path')) {

    /**
     * Get admin path.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_path($path = '')
    {
        return ucfirst(config('snap.admin.path')).($path ? '/'.$path : $path);
    }
}

if (!function_exists('admin_resource_path')) {

    /**
     * Get admin resource path.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_resource_path($path = '')
    {
        return admin_path($path);
    }
}

if (!function_exists('admin_url')) {
    /**
     * Get admin url.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_url($path = '')
    {
        return url(admin_uri($path));
    }
}

if (!function_exists('admin_uri')) {
    /**
     * Get admin url.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_uri($path = '')
    {
        $prefix = '/'.trim(config('snap.admin.route.prefix'), '/');

        $prefix = ($prefix == '/') ? '' : $prefix;

        return $prefix.'/'.trim($path, '/');
    }
}