<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Traits\Filters\FilterManager;

/*
$map = MapService::make();
$map
    ->startingCoords(45.5122, 122.6587)
    ->zoom(10)
    ->geoPointColumn('points')
    ->options([])
;
*/
class MapService
{
    public $latitude = 0;
    public $longitude = 0;
    public $zoom = 13;
    public $limit;
    public $geoPointColumn = 'coordinates';
    //public $url = 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
    public $url = 'http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png';
    public $options = [
        'attribution' => '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
    ];
    public $pagination;

    protected $module;
    protected $request;

    public function __construct($module, Request $request, PaginationService $pagination)
    {
        $this->module = $module;
        $this->request = $request;
        $this->pagination = $pagination;
    }

    public static function make($module)
    {
        $service = new static($module, request(), PaginationService::make($module));

        return $service;
    }

    public function startingCoords($latitude, $longitude)
    {
        $this->latitude = (float) $latitude;
        $this->longitude = (float) $longitude;

        return $this;
    }

    public function zoom($zoom)
    {
        $this->zoom = (int) $zoom;

        return $this;
    }

    public function limit($limit)
    {
        $this->limit = (int) $limit;

        return $this;
    }

    public function url($url)
    {
        $this->url = $url;

        return $this;
    }

    public function options(array $options)
    {
        $this->options = $options;

        return $this;
    }

    public function getPointColumn($column)
    {
        $this->geoPointColumn = $column;

        return $this;
    }
}