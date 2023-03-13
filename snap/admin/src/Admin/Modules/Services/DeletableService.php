<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Traits\Filters\FilterManager;

/*
$deletable = DeletableService::make();
$deletable->allowForceDelete(true)
;
*/
class DeletableService
{
    public $resource;
    public $allowForceDelete = false;
    public $canDelete = false;

    protected $module;
    protected $request;

    public function __construct($module, Request $request)
    {
        $this->module = $module;
        $this->request = $request;
        $this->canDelete = $this->module->hasPermission('delete');
    }

    public static function make($module)
    {
        $service = new static($module, request());

        return $service;
    }

    public function resource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    public function allowForceDelete($force)
    {
        $this->allowForceDelete = $force;

        return $this;
    }

    public function canDelete($can)
    {
        $this->canDelete = $can;

        return $this;
    }
}