<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;
use Snap\Admin\Modules\ResourceModule;

/*
$restorable = RestorableService::make();
*/
class RestorableService
{
    protected $module;
    protected $request;
    protected $model;

    public function __construct(ResourceModule $module, Request $request)
    {
        $this->module = $module;
        $this->request = $request;
        $this->model = $module->getModel();
    }

    public static function make($module)
    {
        $service = new static($module, request());

        return $service;
    }

    public function max($max)
    {
        $this->model->$DEFAULT_ARCHIVE_MAX = $max;

        return $this;
    }
}