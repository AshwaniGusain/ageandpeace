<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;

/*
$show = ShowService::make();
$show
    ->resource($resource)
;
*/
class DuplicateService
{
    public $resource;
    public $form;

    protected $module;
    protected $request;

    public function __construct($module, Request $request)
    {
        $this->module = $module;
        $this->request = $request;
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

}