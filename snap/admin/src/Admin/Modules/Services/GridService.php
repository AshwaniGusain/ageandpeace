<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Traits\Filters\FilterManager;
use Snap\Admin\Ui\Components\GridItem;

/*
$grid = GridService::make();
;
*/
class GridService
{
    public $itemTemplate = null;
    public $pagination;
    public $cols = 4;
    public $limit = 50;

    protected $module;
    protected $request;

    public function __construct($module, Request $request, PaginationService $pagination)
    {
        $this->module = $module;
        $this->request = $request;
        $this->pagination = $pagination;

        $this->itemTemplate = function($item) {
            return new GridItem(['item' => $item, 'module' => $this->module]);
        };
    }

    public static function make($module)
    {
        $service = new static($module, request(), PaginationService::make($module));

        return $service;
    }

    public function renderItem($item)
    {
        $callback = $this->itemTemplate;

        return $callback($item);
    }

    public function limit($limit)
    {
        $this->limit = (int) $limit;

        return $this;
    }

    public function cols($cols)
    {
        $this->cols = (int) $cols;

        return $this;
    }

    public function pagination(\Closure $callback)
    {
        call_user_func($callback, $this->pagination);

        return $this;
    }

}