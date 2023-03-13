<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;

/*
$show = ShowService::make();
$show
    ->resource($resource)
;
*/
class ShowService
{
    public $resource;
    public $form;

    protected $module;
    protected $request;

    public function __construct($module, Request $request)
    {
        $this->module = $module;
        $this->request = $request;
        $this->form = FormService::make($module, \Form::make());
        $this->form->displayOnly(true);
    }

    public static function make($module)
    {
        $service = new static($module, request());

        return $service;
    }

    public function resource($resource)
    {
        $this->resource = $resource;
        $this->form->withValues($this->resource->getAttributes());

        return $this;
    }

    public function inputs($inputs)
    {
        if (!empty($inputs)) {
            $this->form->add($inputs);
        }

        return $this;
    }

    public function scaffold($options = [])
    {
        //$model = $this->resource ?? $this->module->getModel();
       // $this->form->model($model,$options);
        $this->form->scaffold($options);
        return $this;
    }

    public function __call($method, $args)
    {
        //if (method_exists($this->form->form, $method)) {
        return call_user_func_array([$this->form, $method], $args);

        //}

        //throw new \BadMethodCallException("Method " . get_class($this) . "::{$method} does not exist.");
    }
}