<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;
use Snap\Admin\Modules\ResourceModule;

/*
$others = OthersService::make();
*/
class OthersService
{
    public $query;
    public $nameField = null;
    public $valueField = null;

    protected $module;
    protected $request;
    protected $model;

    public function __construct(ResourceModule $module, Request $request)
    {
        $this->module = $module;
        $this->request = $request;
        $this->model = $module->getModel();
        $this->nameField = $this->model->getDisplayNameKey();
        $this->valueField = $this->model->getKeyName();
        $this->query = $this->model->newQuery();
    }

    public static function make($module)
    {
        $service = new static($module, request());

        return $service;
    }

    public function nameField($nameField)
    {
        $this->nameField = $nameField;

        return $this;
    }

    public function valueField($valueField)
    {
        $this->valueField = $valueField;

        return $this;
    }

    public function query(\Closure $callback)
    {
        $callback($this->query);

        return $this;
    }

}