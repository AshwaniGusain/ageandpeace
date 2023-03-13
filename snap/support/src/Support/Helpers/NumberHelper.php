<?php

namespace Snap\Support\Helpers;

use Illuminate\Support\Traits\Macroable;
use NumberFormatter;

class NumberHelper
{
    use Macroable;

    /**
     * Converts a number into a byte format
     *
     * @param $num
     * @param int $precision
     * @return string
     */
    public static function byte($num, $precision = 1)
    {
        if ($num >= 1000000000000) {
            $num = round($num / 1099511627776, $precision);
            $unit = trans('support::number.terabyte');
        } elseif ($num >= 1000000000) {
            $num = round($num / 1073741824, $precision);
            $unit = trans('support::number.gigabyte');
        } elseif ($num >= 1000000) {
            $num = round($num / 1048576, $precision);
            $unit = trans('support::number.megabyte');
        } elseif ($num >= 1000) {
            $num = round($num / 1024, $precision);
            $unit = trans('support::number.kilobyte');
        } else {
            $unit = trans('support::number.bytes');

            return number_format($num).' '.$unit;
        }

        return number_format($num, $precision).' '.$unit;
    }

    /**
     * For a complete list of styles:
     * http://php.net/manual/en/class.numberformatter.php#intl.numberformatter-constants.unumberformatstyle
     *
     * @param $style
     * @param string $locale
     * @return \NumberFormatter
     */
    public static function formatter($style, $locale = 'en_US')
    {
        $formatter = new NumberFormatter($locale, $style);

        return $formatter;
    }

    /**
     * Creates ordinal part of number string when passed a number (e.g. 1st, 2nd, 3rd)
     *
     * @param $number
     * @param string $locale
     * @return string
     */
    public static function ordinal($number, $locale = 'en_US')
    {
        return static::formatter(NumberFormatter::ORDINAL)->format($number);
    }

    /**
     * Creates a percentage based on the number value passed.
     *
     * @param $number
     * @param string $locale
     * @return string
     */
    public static function percent($number, $locale = 'en_US')
    {
        return static::formatter(NumberFormatter::PERCENT)->format($number);
    }

    /**
     * Converts a number into a currency value based on the $locale.
     *
     * @param $number
     * @param string $locale
     * @return string
     */
    public static function currency($number, $locale = 'en_US')
    {
        return static::formatter(NumberFormatter::CURRENCY)->format($number);
    }

    /*
     * SEE MORE ACCURATE WAY TO DO THIS ABOVE
     * */
    //public static function currency($value, $symbol = '$',  $include_cents = TRUE, $decimal_sep = '.', $thousands_sep = ',')
    //{
    //	if (!isset($value) OR $value === "") {
    //		return;
    //	}
    //	$value = (float) $value;
    //	$dec_num = ( ! $include_cents) ? 0 : 2;
    //	return $symbol.number_format($value, $dec_num, $decimal_sep, $thousands_sep);
    //}

}
