<?php
use Snap\Support\Helpers\UrlHelper;

if ( ! function_exists('is_http_path'))
{
    function is_http_path($path)
    {
        return UrlHelper::isHttpPath($path);
    }
}

if ( ! function_exists('is_absolute_url'))
{
    function is_absolute_url($link)
    {
        return UrlHelper::isAbsoluteUrl($link);
    }
}

if ( ! function_exists('is_external_url'))
{
    function is_external_url($link, $exts = array())
    {
        return UrlHelper::isExternalUrl($link, $exts);
    }
}

if ( ! function_exists('uri'))
{
    function uri($from = 0, $to = null)
    {
        return UrlHelper::uri($from, $to);
    }
}

if ( ! function_exists('current_url'))
{
    function current_url($query = false, $to = null)
    {
        return UrlHelper::currentUrl($query, $to);
    }
}

if ( ! function_exists('uri_segment'))
{
    function uri_segment($n, $default = false)
    {
        return UrlHelper::segment($n, $default);
    }
}

if ( ! function_exists('prep_url'))
{
    function prep_url($str = '')
    {
        return UrlHelper::prep($str);
    }
}

if ( ! function_exists('unprep_url'))
{
    function unprep_url($str = '')
    {
        return UrlHelper::unprep($str);
    }
}

if ( ! function_exists('anchor'))
{
    function anchor($link, $title = null, $attrs = array(), $target = null)
    {
        return UrlHelper::anchor($link, $title, $attrs, $target);
    }
}

if ( ! function_exists('auto_link'))
{
    function auto_link($str, $type = 'both', $popup = FALSE)
    {
        return UrlHelper::autoLink($str, $type, $popup);
    }
}


/**
 * Will return a target="_blank" if the link is not from the same domain.
 *
 * @access	public
 * @param	string	URL
 * @param	array	An array of extensions to check to force it to target="_blank"
 * @return	boolean
 */
if (!function_exists('link_target'))
{
    function link_target($link, $exts = array())
    {
        return UrlHelper::target($uri);
    }
}

if (!function_exists('query_str'))
{
    function query_str($query = true, $q = true)
    {
        return UrlHelper::query($query, $q);
    }
}

if (!function_exists('route_parameters'))
{
    function route_parameters($uri)
    {
        return UrlHelper::routeParameters($uri);
    }
}