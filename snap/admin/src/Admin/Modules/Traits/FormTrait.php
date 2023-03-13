<?php

namespace Snap\Admin\Modules\Traits;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Services\FormService;
use Snap\Ui\UiComponent;

trait FormTrait {

    public function registerFormTrait()
    {
        $this->aliasTrait('form', 'Snap\Admin\Modules\Traits\FormTrait');

        // This passes the  "$resource" variable automatically based on the passed ID.
        \Route::bind('resource', function ($value, $route) {
            $module = \Admin::modules()->current();
            if ($module) {
                $model = $module->getModel();
                $resource = $model->newQueryWithoutScopes()->find($value);
                if ($resource) {
                    return $resource;
                }
            }
            return abort(404);
        });

        $this->addRoute(['get'], 'create', '@create', ['permission' => 'create']);
        $this->addRoute(['get'], 'create_inline', '@createInline', ['permission' => 'create']);
        $this->addRoute(['post'], 'insert', '@insert', ['permission' => 'create']);

        $this->addRoute(['get'], '{resource}/edit', '@edit', ['permission' => 'edit', 'as' => 'edit', 'where' => ['resource' => '[0-9]+']]);
        $this->addRoute(['get'], '{resource}/edit_inline', '@editInline', ['permission' => 'edit', 'as' => 'edit_inline', 'where' => ['resource' => '[0-9]+']]);
        $this->addRoute(['put', 'patch'], '{resource}/update', '@update', ['permission' => 'edit', 'as' => 'update', 'where' => ['resource' => '[0-9]+']]);
        $this->addRoute(['post'], '{resource}/upload', '@upload', ['permission' => 'edit', 'as' => 'upload', 'where' => ['resource' => '[0-9]+']]);

        $this->addRoute(['get'], 'input/{input}/{resource?}', '@input', ['permission' => 'edit', 'as' => 'input', 'where' => ['input' => '[\w,-\.]+', 'resource' => '[0-9]+']]);

        $this->addPermissions(
            ['view', 'create', 'edit']
        );

    }

    public function bootFormTrait(Request $request)
    {
        $this->bindService('form', function(){
            return FormService::make($this, \Form::make());
        }, false);

        $this->addUiCallback(\Snap\Admin\Ui\Components\Form::class, function ($ui, $request) {
            $resource = $ui->resource;
            $service = $this->service('form')->resource($resource);
            $ui->form = $this->buildForm($service, $request, $resource);

        }, UiComponent::EVENT_INITIALIZED);

        // Run post processors.
        $eventTypes = ['beforeValidation', 'beforeSave', 'afterSave'];
        foreach ($eventTypes as $type) {
            //$form = null;
            \Event::listen(static::eventName($type), function($resource, $request) use ($type) {
                $service = $this->service('form')->resource($resource);
                //  if (!isset($form)) {
                $this->buildForm($service, $request, $resource);
                //}

                $service->form->runPostProcessors($type, $request, $resource);
            });
        }
    }

    public function buildForm($service, $request, $resource = null)
    {
        if (!empty($this->scaffoldForm)) {
            $service->scaffold();
        }

        $service->inputs($this->inputs($request, $resource));
        $this->form($service, $request, $resource);

        return $service->form;
    }

    protected function form(FormService $form, Request $request = null, $resource = null)
    {

    }

    public function inputs(Request $request = null, $resource = null)
    {
        return [];
    }

    public function formValues($request, $resource = null)
    {
        return [];
    }

    public function validationValues($request, $resource = null)
    {
        return $request->all();
    }

    // @TODO Jackhackery here... This will load any javascript/css in template form but "There's got to be a better way!"
    public function loadFormJs()
    {
        static $loadedForm;
        if (is_null($loadedForm)) {
            $formService = $this->service('form');
            $this->buildForm($formService, request());
            $loadedForm = true;
        }
    }
}