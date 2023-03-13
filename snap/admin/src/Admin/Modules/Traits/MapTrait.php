<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Services\MapService;
use Snap\Ui\UiComponent;

trait MapTrait {

    public function registerMapTrait()
    {
        $this->aliasTrait('map', 'Snap\Admin\Modules\Traits\MapTrait');

        $this->addRoute(['get'], 'map', '@map', []);
    }

    public function bootMapTrait()
    {
        $this->bindService('map', function(){
            return MapService::make($this);
        });

        $this->addUiCallback(['map'], function ($ui, $request, $module) {
            $service = $this->service('map');
            //$this->map($service, $request);
            $ui->map
                ->setLatitude($service->latitude)
                ->setLongitude($service->longitude)
                ->setZoom($service->zoom)
                ->setLimit($service->limit)
                ->setMapUrl($service->url)
                ->setMapOptions($service->options)
                ->setLimit($service->limit)
                ->setGeoPointName($service->geoPointColumn)
                ->setLimitOptions($service->pagination->limits)
            ;
        }, UiComponent::EVENT_INITIALIZED);
    }

    protected function map(MapService $ajax, Request $request)
    {

    }

}