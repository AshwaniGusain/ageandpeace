<?php

if (!function_exists('nav')) {

    /**
     * Returns a menu object.
     *
     * @param $group
     * @param $options
     * @return \Snap\Menu\MenuBuilder
     */
    function nav($group = null, $options = [])
    {
        if (is_array($group)) {
            \Nav::load($group);
        } else {
            \Nav::group($group);
        }

        return \Nav::setOptions($options);
    }
}