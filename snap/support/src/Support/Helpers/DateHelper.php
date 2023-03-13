<?php

namespace Snap\Support\Helpers;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Traits\Macroable;

class DateHelper
{
    use Macroable;

    public static function carbonize($value, $format = 'Y-m-d H:i:s')
    {
        // If this value is already a Carbon instance, we shall just return it as is.
        // This prevents us having to re-instantiate a Carbon instance when we know
        // it already is one, which wouldn't be fulfilled by the DateTime check.
        if ($value instanceof Carbon) {
            return $value;
        }

        // If the value is already a DateTime instance, we will just skip the rest of
        // these checks since they will be a waste of time, and hinder performance
        // when checking the field. We will just return the DateTime right away.
        if ($value instanceof DateTimeInterface) {
            return new Carbon($value->format($format), $value->getTimeZone());
        }

        // If this value is an integer, we will assume it is a UNIX timestamp's value
        // and format a Carbon object from this timestamp. This allows flexibility
        // when defining your date fields as they might be UNIX timestamps here.
        if (is_numeric($value)) {
            return Carbon::createFromTimestamp($value);
        }

        // If the value is in simply year, month, day format, we will instantiate the
        // Carbon instances from that format. Again, this provides for simple date
        // fields on the database, while still supporting Carbonized conversion.
        if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value)) {
            return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        }

        // Finally, we will just assume this date is in the format used by default on
        // the database connection and use that format to create the Carbon object
        // that is returned back out to the developers after we convert it here.
        return Carbon::parse($value);
    }

    public static function dateTimeNow()
    {
        return date('Y-m-d H:i:s');
    }

    public static function isEmptyDate($date)
    {
        return (empty($date) || $date == '0000-00-00' || $date == '0000-00-00 00:00:00' || $date == '01/01/1970');
    }

    public static function isValid($value)
    {
        return \DateTime::createFromFormat('Y-m-d H:i:s', $value) !== FALSE;
    }

    public static function isDateFormat($value)
    {
        return (! empty($value) && (int) $value != 0) && (preg_match("#([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})#", $value) || preg_match("#([0-9]{1,2})[/\-\.]([0-9]{1,2})[/\-\.]([0-9]{4})#", $value));
    }

    public static function isTimeFormat($value)
    {
        return (! empty($value) && (int) $value != 0) && (preg_match("#([0-9]{2}):([0-9]{2})#", $value) || preg_match("#([0-9]{2}):([0-9]{2}):([0-9]{2})#", $value));
    }

    public static function rangeStr($date1, $date2, $params = [])
    {
        // set formatting defaults
        //@ TODO put in language file
        $format['same_day_and_time'] = 'F j, Y h:ia';
        $format['same_day'] = ['F j, h:ia', 'h:ia'];
        $format['same_month'] = ['F j', 'j, Y'];
        $format['same_year'] = ['F j', 'F j, Y'];
        $format['default'] = 'F j, Y';
        $format['joiner'] = ' - ';

        $format = array_merge($format, $params);

        $date1TS = (is_string($date1)) ? strtotime($date1) : $date1;
        if (is_null($date2) OR (int) $date2 == 0) {
            $date2TS = $date1TS;
        } else {
            $date2TS = (is_string($date2)) ? strtotime($date2) : $date2;
        }

        // same day
        if (date('Y-m-d', $date1TS) == date('Y-m-d', $date2TS) OR (int) $date2 == 0) {

            // same day but different time
            if (date('H:i', $date1TS) != date('H:i', $date2TS)) {
                return date($format['same_day'][0], $date1TS).$format['joiner'].date($format['same_day'][1], $date2TS);
            }

            // same day and time format
            return date($format['same_day_and_time'], $date1TS);
        } // same month
        else {
            if (date('m/Y', $date1TS) == date('m/Y', $date2TS)) {
                return date($format['same_month'][0], $date1TS).$format['joiner'].date($format['same_month'][1], $date2TS);
            } // same year
            else {
                if (date('Y', $date1TS) == date('Y', $date2TS)) {
                    return date($format['same_year'][0], $date1TS).$format['joiner'].date($format['same_year'][1], $date2TS);
                } // default
                else {
                    return date($format['default'], $date1TS).$format['joiner'].date($format['default'], $date2TS);
                }
            }
        }
    }

    public static function pretty($timestamp, $use_gmt = false)
    {
        if (is_string($timestamp)) {
            $timestamp = strtotime($timestamp);
        }

        $now = ($use_gmt) ? mktime() : time();
        $diff = $now - $timestamp;
        $day_diff = floor($diff / 86400);

        // don't go beyond
        if ($day_diff < 0) {
            return;
        }

        //@TODO... put in language file
        if ($diff < 60) {
            return 'just now';
        } else {
            if ($diff < 120) {
                return '1 minute ago';
            } else {
                if ($diff < 3600) {
                    return floor($diff / 60).' minutes ago';
                } else {
                    if ($diff < 7200) {
                        return '1 hour ago';
                    } else {
                        if ($diff < 86400) {
                            return floor($diff / 3600).' hours ago';
                        } else {
                            if ($day_diff == 1) {
                                return 'Yesterday';
                            } else {
                                if ($day_diff < 7) {
                                    return $day_diff." days ago";
                                } else {
                                    return ceil($day_diff / 7).' weeks ago';
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function age($bday_ts, $at_time_ts = null)
    {
        if (empty($at_time_ts)) {
            $at_time_ts = time();
        }
        if (is_string($bday_ts)) {
            $bday_ts = strtotime($bday_ts);
        }

        // See http://php.net/date for what the first arguments mean.
        $diff_year = date('Y', $at_time_ts) - date('Y', $bday_ts);
        $diff_month = date('n', $at_time_ts) - date('n', $bday_ts);
        $diff_day = date('j', $at_time_ts) - date('j', $bday_ts);

        // If birthday has not happened yet for this year, subtract 1.
        if ($diff_month < 0 || ($diff_month == 0 && $diff_day < 0)) {
            $diff_year--;
        }

        return $diff_year;
    }
}

