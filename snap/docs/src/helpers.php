<?php

if (!function_exists('docs_url')) {

    /**
     * @param string $uri
     * @return mixed
     */
    function docs_url($uri = '')
    {
        return \Docs::url();
    }
}

if (!function_exists('docs_url')) {

    /**
     * @param string $html
     * @return mixed
     */
    function docs_auto_link($html)
    {
        return \Docs::autoLink($html);
    }
}