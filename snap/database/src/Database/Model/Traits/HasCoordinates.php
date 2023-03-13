<?php

namespace Snap\Database\Model\Traits;

use Event;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;

trait HasCoordinates {

    use SpatialTrait;

    /**
     * Boot the trait. Adds an observer class for validating.
     *
     * @return void
     */
    public static function bootHasCoordinates()
    {
        Event::listen('eloquent.retrieved: '.static::class, function($model) {
            $model->append('lat', 'lng');
        });
    }

    /**
     * Get the name of the coordinates column.
     *
     * @return string
     */
    public function getCoordinatesColumn()
    {
        return defined('static::COORDINATES') ? static::COORDINATES : 'coordinates';
    }

    /**
     * Returns the latitude.
     *
     * @return float
     */
    public function getLatAttribute()
    {
        $coords = $this->{$this->getCoordinatesColumn()};
        if ($coords) {
            return $coords->getLat();
        }

        return null;
    }

    /**
     * Returns the longitude.
     *
     * @return float
     */
    public function getLngAttribute()
    {
        $coords = $this->{$this->getCoordinatesColumn()};
        if ($coords) {
            return $coords->getLng();
        }

        return null;
    }

    /**
     * Returns whether there are coordinates present.
     *
     * @return boolean
     */
    public function getHasLatAndLngAttribute()
    {
        $coords = $this->{$this->getCoordinatesColumn()};
        if ($coords) {
            $lat = $coords->getLng();
            $lng = $coords->getLat();
            return ($lat && $lng) ? true : false;
        }

        return false;
    }
}
