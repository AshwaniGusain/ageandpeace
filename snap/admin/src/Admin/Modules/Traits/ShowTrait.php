<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Services\ShowService;
use Snap\Ui\UiComponent;

trait ShowTrait
{
    public function registerShowTrait()
    {
        $this->aliasTrait('show', 'Snap\Admin\Modules\Traits\ShowTrait');
        $this->addRoute(['get'], '{resource}', '@show', ['as' => 'show', 'where' => ['resource' => '[0-9]+']]);
        $this->addRoute(['get'], '{resource}/inline', '@showInline', ['as' => 'showInline', 'where' => ['resource' => '[0-9]+']]);
    }

    public function bootShowTrait()
    {
        $this->bindService('show', function(){
            return ShowService::make($this);
        }, false);


        if ( ! $this->hasService('form')) {
            $this->addUiCallback(\Snap\Admin\Ui\Components\Form::class, function ($ui, $request) {
                    $resource = $ui->resource;
                    $service = $this->service('show')->resource($resource);

                        if (!empty($this->scaffoldForm)) {
                            $service->scaffold();
                        }

                        if (method_exists($this, 'inputs')) {
                            $service->inputs($this->inputs());
                        }
                    $this->show($service, $request);

                    $ui->form = $service->form->form;

            }, UiComponent::EVENT_INITIALIZED);
        }
    }

    protected function show(ShowService $show, Request $request)
    {

    }

}