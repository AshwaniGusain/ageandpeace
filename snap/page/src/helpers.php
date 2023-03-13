<?php

if (!function_exists('page_title')) {

    /**
     * Returns a page title
     *
     * @param mixed $title Can be an string or an array if multiple
     * @param null $sep
     * @param string $order
     * @return mixed
     */
    function page_title($title = '', $sep = null, $order = 'right')
    {
        return \Snap\Page\Utils::pageTitle($title, $sep, $order);
    }
}