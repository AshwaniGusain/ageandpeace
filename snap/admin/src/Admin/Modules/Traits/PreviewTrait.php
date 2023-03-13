<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Snap\Admin\Modules\Services\PreviewService;
use Snap\Admin\Ui\Components\PreviewButton;
use Snap\Admin\Ui\Module\Resource\CreatePage;
use Snap\Admin\Ui\Module\Resource\DuplicatePage;
use Snap\Admin\Ui\Module\Resource\EditPage;
use Snap\Ui\UiComponent;

trait PreviewTrait
{
    public function registerPreviewTrait()
    {
        $this->aliasTrait('preview', 'Snap\Admin\Modules\Traits\PreviewTrait');
        $this->addRoute(['get'], 'loading', '@loading', ['as' => 'loading']);

        // Causes errors on pages like "/admin/docs/admin/modules" because "pages" module exists
        //\Route::bind($this->handle(), function ($value, $route) {
        //    $model = $this->getModel()->firstOrNew([$this->getModel()->getSlugOptions()->slugField => $value]);
        //    $model->fill($this->cleanedRequestVars(request()));
        //    return $model;
        //});
    }

    public function bootPreviewTrait()
    {
        $this->bindService('preview', function(){
            $service = PreviewService::make($this);
            if (property_exists($this, 'publicBaseUri')) {
                $service->prefix($this->publicBaseUri);
            }
            return $service;
        });

        $this->addUiCallback(['create', 'edit'], function ($ui) {
            $service = $this->service('preview');
            $ui->preview
                ->setSlugInput($service->slugInput)
                ->setPrefix($service->prefix)
                ->setLoadingUrl($this->url('loading'))
                ->visible(true)
            ;
            $ui->heading->preview_button->visible(true);
            //$ui->form->get($service->slugInput)->setPrefix($service->prefix);
        }, UiComponent::EVENT_INITIALIZED);
    }

    protected function preview(PreviewService $preview, Request $request)
    {

    }

    //protected function cleanedRequestVars($request)
    //{
    //    $vars = [];
    //    $ignore = ['_method', '_token'];
    //    foreach($request->all() as $key => $val) {
    //        if ( ! in_array($key, $ignore)) {
    //            $vars[$key] = $val;
    //        }
    //    }
    //
    //    return $vars;
    //}
}