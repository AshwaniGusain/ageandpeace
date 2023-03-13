<?php

if (!function_exists('setting')) {

    /**
     * Returns a setting by first looking in the database and then in a corresponding config.
     *
     * @param $key
     * @param $default
     * @return
     */
    function setting($key = null, $default = null)
    {
        return \Setting::get($key, $default);
    }
}