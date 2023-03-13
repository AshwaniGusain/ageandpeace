<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Services\OthersService;
use Snap\Admin\Ui\Components\OthersNavigation;
use Snap\Ui\UiComponent;

trait OthersNavigationTrait {

    public function registerOthersNavigationTrait()
    {
        $this->aliasTrait('others', 'Snap\Admin\Modules\Traits\OthersNavigationTrait');
    }

    public function bootOthersNavigationTrait()
    {
        $this->bindService('others', function(){
            return OthersService::make($this);
        }, true);

        $this->addUiCallback('edit', function ($ui) {
            $service = $this->service('others');
            $resource = $ui->resource;
            $others = $service->query->lists($service->nameField, $service->valueField);

            if ($others->count()) {
                $othersDropdown = new OthersNavigation();
                $othersDropdown
                    ->setResource($resource)
                    ->setOthers($others)
                    ->setCurrent($resource->getKey())
                ;
                $ui->related_panel->list->add($othersDropdown);
            }
        }, UiComponent::EVENT_INITIALIZED);
    }

    protected function others(OthersService $others, Request $request)
    {

    }
}