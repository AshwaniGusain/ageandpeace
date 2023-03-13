<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Snap\Admin\Models\Search;
use Snap\Admin\Modules\Services\IndexableService;
use Snap\Ui\UiComponent;

trait IndexableTrait {

    public function registerIndexableTrait()
    {
        $this->aliasTrait('indexable', 'Snap\Admin\Modules\Traits\IndexableTrait');

        /* Added binding here so that it can be called outside the context of the admin (e.g. a seeder) */
        $this->bindService('indexable', function(){
            return IndexableService::make($this);
        }, true);
    }

    public function bootIndexableTrait()
    {
        $this->addUiCallback(['create', 'edit', 'create_inline', 'edit_inline'], function ($ui, $request, $module) {
            $resource = $ui->resource;
            $service = $this->service('indexable')->resource($resource);
            $this->indexable($service, $request, $resource);

        }, UiComponent::EVENT_INITIALIZED);

        Event::listen($this->eventName('afterSave'), function($resource, $request){
            if (! $this->service('indexable')->indexResource($resource)) {
                //@TODO Throw error?
            }
        });

        Event::listen($this->eventName('afterDelete'), function($resource, $request){
            if (! $this->service('indexable')->unIndexResource($resource)) {
                //@TODO Throw error?
            }
        });
    }

    protected function indexable(IndexableService $indexable, Request $request)
    {

    }
}