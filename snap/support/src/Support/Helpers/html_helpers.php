<?php 

use Snap\Support\Helpers\HtmlHelper;

if ( ! function_exists('html_attrs'))
{
    function html_attrs($attrs)
    {
        return HtmlHelper::attrs($attrs);
    }
}

if ( ! function_exists('safe_email'))
{
    function safe_email($email)
    {
        return HtmlHelper::email($email);
    }
}

if ( ! function_exists('mailto'))
{
    function mailto($email)
    {
        return HtmlHelper::mailto($email);
    }
}