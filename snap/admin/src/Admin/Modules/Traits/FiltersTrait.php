<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Snap\Admin\Modules\Services\FiltersService;
use Snap\Admin\Modules\Traits\Filters\FilterManager;
use Snap\Admin\Ui\Components\Filters as UiFilters;
use Snap\Admin\Ui\Components\Search;
use Snap\Ui\UiComponent;

trait FiltersTrait
{
    /**
     * @param \Illuminate\Http\Request $request
     */
    public function registerFiltersTrait()
    {
        $this->aliasTrait('filters', 'Snap\Admin\Modules\Traits\FiltersTrait');
        //$this->filters = new FilterManager($this, $request);
    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function bootFiltersTrait(Request $request)
    {
        $service = FiltersService::make($this);
        $this->bindService('filters', $service, true);
        // $this->filters($service, $request);

        //$this->bindService('filters', function(){
        //    return FiltersService::make($this);
        //});
        //
        $this->addUiCallback(UiFilters::class, function ($ui, $request, $module) {
            $service = $this->service('filters');
            $ui->filters = $service->filters;
        }, UiComponent::EVENT_INITIALIZED);
    }

    protected function filters($filters, $request)
    {

    }
}