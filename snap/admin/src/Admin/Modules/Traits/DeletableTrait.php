<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Services\DeletableService;
use Snap\Ui\UiComponent;

trait DeletableTrait
{
    /**
     * @param \Illuminate\Http\Request $request
     */
    public function registerDeletableTrait(Request $request)
    {
        $this->aliasTrait('delete', 'Snap\Admin\Modules\Traits\DeletableTrait');

        $this->addRoute(['get'], '{id}/delete', '@delete', ['permission' => 'delete', 'as' => 'delete']);
        $this->addRoute(['get'], '{id}/delete_inline', '@deleteInline', ['permission' => 'delete', 'as' => 'delete_inline']);
        $this->addRoute(['patch'], '{resource}/untrash', '@unTrash', ['permission' => 'delete', 'as' => 'untrash']);
        $this->addRoute(['delete'], 'delete', '@doDelete', ['permission' => 'delete', 'as' => 'doDelete']);

        $this->addPermissions(
            ['delete']
        );
    }

    public function bootDeletableTrait()
    {
        $this->bindService('deletable', function(){
            $service = DeletableService::make($this);
            if (!empty($this->allowForceDelete)) {
                $service->allowForceDelete(true);
            }
            return $service;
        }, false);

        $this->addUiCallback(\Snap\Admin\Ui\Components\Form::class, function ($ui, $request, $module) {
            $resource = $ui->resource;
            $service = $this->service('deletable')->resource($resource);
            $this->deletable($service, $request, $resource);

        }, UiComponent::EVENT_INITIALIZED);

        $this->addUiCallback(['table', 'listing', 'grid', 'map'], function ($ui, $request, $module) {
            $service = $this->service('deletable');
            $this->deletable($service, $request);
            if ($module->hasTrait('delete') && $module->hasPermission('delete')) {
                $ui->dropdown->add($module->url(), trans('admin::resources.delete_selected'));
            }
        }, UiComponent::EVENT_INITIALIZED);
    }

    protected function deletable(DeletableService $deletable, Request $request, $resource = null)
    {

    }

}