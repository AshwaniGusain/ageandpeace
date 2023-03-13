<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;


class Zip extends Model
{
    //
    use SpatialTrait;

    protected $spatialFields = [
        'geo_point',
    ];

    public function providers()
    {
        return $this->belongsToMany('\App\Models\Provider');
    }
}
