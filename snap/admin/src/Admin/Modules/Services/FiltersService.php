<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Traits\Filters\FilterManager;

/*
$filters = FiltersService::make();
$filters
    ->add(Filter::make('parent_id', 'where', '=')->withInput('number'));
 * */
class FiltersService
{
    public $filters;

    protected $module;
    protected $request;

    public function __construct($module, Request $request, FilterManager $filterManager)
    {
        $this->module = $module;
        $this->request = $request;
        $this->filters = $filterManager;
    }

    public static function make($module)
    {
        $request = request();
        $filterManager = new FilterManager($module->getQuery(), $request);
        $service = new static($module, $request, $filterManager);

        return $service;
    }

    public function add($key, $method = 'where', $operator = '=')
    {
        $this->filters->add($key, $method, $operator);

        return $this;
    }

    public function isFiltered()
    {
        return $this->filters->isFiltered();
    }

}