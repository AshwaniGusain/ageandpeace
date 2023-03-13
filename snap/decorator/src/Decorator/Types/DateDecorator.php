<?php

namespace Snap\Decorator\Types;

use Carbon\Carbon;
use DateTimeInterface;
use Snap\Support\Helpers\DateHelper;
use Snap\Decorator\AbstractDecorator;

class DateDecorator extends AbstractDecorator {

    protected $value = null;
    protected $name = null;
    public static $defaultFormat = 'm/d/Y';

    public function __construct($value, $name = null, $props = [])
    {
        $this->value = $this->parseDate($value);
    }

    protected function parseDate($value)
    {
        return DateHelper::carbonize($value);
        
        // // If this value is already a Carbon instance, we shall just return it as is.
        // // This prevents us having to re-instantiate a Carbon instance when we know
        // // it already is one, which wouldn't be fulfilled by the DateTime check.
        // if ($value instanceof Carbon) {
        //  return $value;
        // }

        //  // If the value is already a DateTime instance, we will just skip the rest of
        //  // these checks since they will be a waste of time, and hinder performance
        //  // when checking the field. We will just return the DateTime right away.
        // if ($value instanceof DateTimeInterface) {
        //  return new Carbon(
        //      $value->format($this->getDateFormat()), $value->getTimeZone()
        //  );
        // }

        // // If this value is an integer, we will assume it is a UNIX timestamp's value
        // // and format a Carbon object from this timestamp. This allows flexibility
        // // when defining your date fields as they might be UNIX timestamps here.
        // if (is_numeric($value)) {
        //  return Carbon::createFromTimestamp($value);
        // }

        // // If the value is in simply year, month, day format, we will instantiate the
        // // Carbon instances from that format. Again, this provides for simple date
        // // fields on the database, while still supporting Carbonized conversion.
        // if (preg_match('/^(\d{4})-(\d{1,2})-(\d{1,2})$/', $value)) {
        //  return Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        // }

        // // Finally, we will just assume this date is in the format used by default on
        // // the database connection and use that format to create the Carbon object
        // // that is returned back out to the developers after we convert it here.
        // return Carbon::createFromFormat($this->getDateFormat(), $value);
    }

    public function format($format = null)
    {
        if (empty($format)) {
            if (!empty($this->props['format'])) {
                $format = $this->props['format'];   
            } elseif ($f = config('date_format')) {
                $format = $f;
            } else {
                $format = static::$defaultFormat;
            }
        }

        if (!empty($format)) {
            $this->value->setToStringFormat($format);
        }

        return $this;
    }

    public function timestamp()
    {
        return $this->value->format('U');
    }

    public function __get($name)
    {
        return $this->value->$name;
    }

    public function __toString()
    {
        return $this->value->__toString();
    }

    public static function detect($value, $name = null)
    {
        return $value instanceof Carbon || $value instanceof DateTimeInterface || DateHelper::isDateFormat($value);
    }

}
