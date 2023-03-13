<?php

use Snap\Support\Helpers\DateHelper;

if ( ! function_exists('carbonize'))
{
    function carbonize($value)
    {
        return DateHelper::carbonize($value);
    }
}

if ( ! function_exists('datetime_now'))
{
    function datetime_now()
    {
        return DateHelper::dateTimeNow();
    }
}

if ( ! function_exists('is_valid_date'))
{
    function is_valid_date($value)
    {
        return DateHelper::isValid($value);
    }
}

if ( ! function_exists('is_date_format'))
{
    function is_date_format($value)
    {
        return DateHelper::isDateFormat($value);
    }
}

if ( ! function_exists('is_time_format'))
{
    function is_time_format($value)
    {
        return DateHelper::isTimeFormat($value);
    }
}

if ( ! function_exists('date_range_str'))
{
    function date_range_str($value)
    {
        return DateHelper::rangeStr($value);
    }
}

if ( ! function_exists('pretty_date'))
{
    function pretty_date($value)
    {
        return DateHelper::pretty($value);
    }
}

if ( ! function_exists('age'))
{
    function age($value)
    {
        return DateHelper::age($value);
    }
}