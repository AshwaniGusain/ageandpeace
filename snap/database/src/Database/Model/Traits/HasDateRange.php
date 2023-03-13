<?php

namespace Snap\Database\Model\Traits;

use Carbon\Carbon;
use Snap\Support\Helpers\DateHelper;

trait HasDateRange {

    /**
     * Boot the scope.
     *
     * @return void
     */
    public static function bootHasDateRange()
    {
        \Event::listen('eloquent.retrieved: '.static::class, function($model) {
            $model->addDates([$model->getStartDateColumn(), $model->getEndDateColumn()]);
        });

    }

    /**
     * Get the name of the start date column.
     *
     * @return string
     */
    public function getStartDateColumn()
    {
        return defined('static::START_DATE_COLUMN') ? static::START_DATE_COLUMN : 'start_date';
    }

    /**
     * Get the name of the end date column.
     *
     * @return string
     */
    public function getEndDateColumn()
    {
        return defined('static::END_DATE_COLUMN') ? static::END_DATE_COLUMN : 'end_date';
    }

    /**
     * Get the range between the dates as a string.
     *
     * @return string
     */
    public function getDateRangeAttribute()
    {
        $startDateColumn = $this->getStartDateColumn();
        $endDateColumn = $this->getEndDateColumn();
        return DateHelper::rangeStr($this->$startDateColumn, $endDateColumn);
    }

    /**
     * Sets the start date attribute to a Carbon object if it isn't already.
     *
     * @param $date
     * @return $this
     */
    public function setStartDateAttribute($date)
    {
        if (! $date instanceof Carbon) {
            $date = new Carbon($date);
        }

        $this->attributes[$this->getStartDateColumn()] = $date;

        return $this;
    }

    /**
     * Sets the end date attribute to a Carbon object if it isn't already.
     *
     * @param $date
     * @return $this
     */
    public function setEndDateAttribute($date)
    {
        if (! $date instanceof Carbon) {
            $date = new Carbon($date);
        }

        $this->attributes[$this->getEndDateColumn()] = $date;

        return $this;
    }
}
