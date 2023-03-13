<?php

use Snap\Support\Helpers\SanitizeHelper;
use Illuminate\Contracts\Support\Htmlable;

if ( ! function_exists('sanitize_trim'))
{
    function sanitize_trim($str)
    {
        return SanitizeHelper::trim($str);
    }
}

if ( ! function_exists('clean_html'))
{
    function clean_html($str, $options = [], $replace = false)
    {
        return SanitizeHelper::clean($str, $options, $replace);
    }
}

if ( ! function_exists('strip_javascript'))
{
    function strip_javascript($str)
    {
        return SanitizeHelper::stripJavascript($str);
    }
}

if ( ! function_exists('strip_images'))
{
    function strip_images($str)
    {
        return SanitizeHelper::stripImages($str);
    }
}

if ( ! function_exists('clean_numbers'))
{
    function clean_numbers($str)
    {
        return SanitizeHelper::cleanNumbers($str);
    }
}

if ( ! function_exists('safe_htmlentities'))
{
    function safe_htmlentities($str, $protect_amp = true)
    {

        return SanitizeHelper::safeHtmlEntities($str, $protect_amp);
    }
}

if ( ! function_exists('clean_filename'))
{
    function clean_filename($str, $relativePath = false)
    {
        return SanitizeHelper::cleanFilename($str, $relativePath);
    }
}

if ( ! function_exists('remove_invisible_characters'))
{
    function remove_invisible_characters($str, $urlEncoded = true)
    {
        return SanitizeHelper::removeInvisibleCharacters($str, $urlEncoded);
    }
}

if ( ! function_exists('is_ascii'))
{
    function is_ascii($str)
    {
        return SanitizeHelper::isAscii($str);
    }
}

if ( ! function_exists('convert_utf8'))
{
    function convert_utf8($str)
    {
        return SanitizeHelper::utf8($str);
    }
}

if ( ! function_exists('escape_html'))
{
    function escape_html($str)
    {
        return SanitizeHelper::escapeHtml($str);
    }
}

if ( ! function_exists('escape_js'))
{
    function escape_js($str)
    {
        return SanitizeHelper::escapeJs($str);
    }
}

if ( ! function_exists('escape_css'))
{
    function escape_css($str)
    {
        return SanitizeHelper::escapeCss($str);
    }
}

if ( ! function_exists('escape_url'))
{
    function escape_url($str)
    {
        return SanitizeHelper::escapeUrl($str);
    }
}

if ( ! function_exists('escape_attr'))
{
    function escape_attr($str)
    {
        return SanitizeHelper::escapeAttr($str);
    }
}

if ( ! function_exists('escaper'))
{
    function escaper()
    {
        return SanitizeHelper::escaper();
    }
}

if ( ! function_exists('xss_clean'))
{
    function xss_clean($str)
    {
        return SanitizeHelper::xssClean($str);
    }
}