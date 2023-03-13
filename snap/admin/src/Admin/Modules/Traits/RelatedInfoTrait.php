<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Services\RelatedInfoService;
use Snap\Ui\UiComponent;

trait RelatedInfoTrait
{
    protected $searched = false;

    public function registerRelatedInfoTrait()
    {
        $this->aliasTrait('relatedInfo', 'Snap\Admin\Modules\Traits\RelatedInfoTrait');
    }

    public function bootRelatedInfoTrait()
    {
        $this->bindService('relatedInfo', function () {
            return RelatedInfoService::make($this);
        }, false);

        $this->addUiCallback(['edit', 'create', 'show'], function ($ui, $request, $module) {
            $resource = $ui->resource;
            $service = $this->service('relatedInfo')->resource($resource)->panel($ui->related_panel);
            $this->buildRelatedInfo($service, $request, $resource);
        }, UiComponent::EVENT_INITIALIZED);

        // Run post processors for any inputs.
        $eventTypes = ['beforeValidation', 'beforeSave', 'afterSave'];
        foreach ($eventTypes as $type) {
            \Event::listen(static::eventName($type), function ($resource, $request) use ($type) {
                $service = $this->service('relatedInfo')->resource($resource);

                // We don't do buildRelatedInfo here because RelatedInfo needs the
                // $ui->related_panel object and we don't have it at this point.
                $inputs = $this->relatedInfoInputs($request, $resource);
                $service->inputs($inputs);
                if ($inputs) {
                    foreach ($inputs as $input) {
                        $input->runPostProcessor($type, $request, $resource);
                    }
                }
            });
        }
    }

    public function buildRelatedInfo($service, $request, $resource = null)
    {
        $service->inputs($this->relatedInfoInputs($request, $resource));
        $this->relatedInfo($service, $request, $resource);

        return $service->panel;
    }

    protected function relatedInfo(RelatedInfoService $relatedInfo, Request $request, $resource = null)
    {

    }

    public function relatedInfoInputs(Request $request = null, $resource = null)
    {
        return [];
    }
}