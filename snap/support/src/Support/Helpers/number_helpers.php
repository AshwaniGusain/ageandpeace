<?php 

use Snap\Support\Helpers\NumberHelper;


if ( ! function_exists('byte'))
{
    function byte($num, $precision = 1)
    {
        return NumberHelper::byte($num, $precision);
    }
}

if ( ! function_exists('currency'))
{
	function currency($value)
	{
	    return NumberHelper::currency($value);
	}
}

if ( ! function_exists('ordinal_number'))
{
    function ordinal_number($value)
    {
        return NumberHelper::ordinal($value);
    }
}

if ( ! function_exists('percent_number'))
{
    function percent_number($value)
    {
        return NumberHelper::percent($value);
    }
}