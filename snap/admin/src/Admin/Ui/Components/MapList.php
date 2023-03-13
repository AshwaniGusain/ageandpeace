<?php

namespace Snap\Admin\Ui\Components;

use Illuminate\Http\Request;
use Snap\Ui\Traits\CssTrait;
use Snap\Ui\Traits\JsTrait;
use Snap\Ui\UiComponent;

class MapList extends UiComponent
{
    use JsTrait;
    use CssTrait;

    protected $view = 'admin::components.map-list';
    protected $scripts = [
        'assets/snap/js/components/resource/MapList.js',
        'assets/snap/vendor/leaflet/leaflet.js',
    ];
    protected $styles = [
        'assets/snap/vendor/leaflet/leaflet.css',
    ];
    protected $data = [
        'module'         => null,
        'items'          => [],
        //'column' => 'name',
        //'order' => 'asc',
        'geo_point_name' => 'coordinates',
        'latitude'       => 0,
        'longitude'      => 0,
        'zoom'           => 13,
        // http://leaflet-extras.github.io/leaflet-providers/preview/index.html
        //'map_url' => 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        'map_url'        => 'http://{s}.tiles.wmflabs.org/bw-mapnik/{z}/{x}/{y}.png',
        'map_options'    => [
            'attribution' => '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
        ],

        'pagination' => null,
        'limit'      => null,
        //'limit_options' => []
    ];

    public function initialize(Request $request)
    {
        $this->request = $request;
        $this->setLimit($request->input('limit'));
    }

    public function getItems()
    {
        $this->pagination = $this->module->getQuery()->paginate($this->limit);
        return $this->pagination->getCollection()->keyBy('id');
    }

    public function getPagination()
    {
        if (empty($this->data['pagination'])) {
            $data = $this->getItemData();
            if (!empty($data)) {
                $this->data['pagination'] = $data->paginate($this->limit);
            }
        }

        return $this->data['pagination'];
    }

    public function getItemData()
    {
        if (isset($this->data['query'])) {
            $query = $this->query
                // ->orderBy($this->column, $this->order)
            ;

            return $query;
        }

        return [];
    }

    protected function initializeMap()
    {
        $items = $this->getItems();

        if ($this->pagination) {
            $queryString = array_except($this->request->query(), $this->pagination->getPageName());

            if (! $this->request->ajax()) {
                $this->pagination->appends($queryString);
            } else {
                //$this->pagination = '';
            }
        }

        $first = $items->first();
        if ($first && $first->{$this->geo_point_name}) {
            $this->latitude = $first->{$this->geo_point_name}->getLat();
            $this->longitude = $first->{$this->geo_point_name}->getLng();
        }

        $this->data['items'] = $items;

        $this->data['has_data'] = (!empty($data) && $data->count() > 0) ? true : false;

        return $this;
    }

    protected function _render()
    {
        $this->initializeMap();

        return parent::_render();
    }

}