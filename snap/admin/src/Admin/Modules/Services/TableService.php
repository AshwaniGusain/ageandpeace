<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;

/*
$table = TableService::make();
$table
    ->columns(['name', 'slug', ....])
    ->query(function($query){
    })
    ->defaultSort('-date_modified')
    ->limit()
    ->format('\Snap\DataTable\DataTableFormatters::booleanFormatter', ['active']);
;
 * */
class TableService
{
    public $sortRequestParam = 'sort';
    public $pagination;
    public $columns = [];
    public $ignored = [];
    public $formatters = [];
    public $actions = [
        '' => 'show',
        'edit' => 'form',
        'delete' => 'delete',
    ];
    public $sort = '';
    public $sortable = [];
    public $nonSortable = [];
    public $customSort = null;
    public $limit = 50;
    public $limitOptions = [
        50,
        100,
        200,
    ];

    protected $module;
    protected $request;
    protected $query;
    protected $table;

    public function __construct($module, Request $request, PaginationService $pagination)
    {
        $this->module = $module;
        $this->request = $request;
        $this->pagination = $pagination;

        $this->query = $this->module->getQuery();
    }

    public static function make($module)
    {
        $service = new static($module, request(), PaginationService::make($module));

        return $service;
    }

    public function columns($columns)
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();

        return $this;
    }

    public function ignored($ignored)
    {
        $this->ignored = $ignored;

        return $this;
    }

    public function defaultSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    public function sortable($sortable)
    {
        $this->sortable = is_array($sortable) ? $sortable : func_get_args();

        return $this;
    }

    public function nonSortable($nonSortable)
    {
        $this->nonSortable = is_array($nonSortable) ? $nonSortable : func_get_args();

        return $this;
    }

    public function customSort($callback)
    {
        $this->customSort = $callback;

        return $this;
    }

    public function format($formatter, $columns = null)
    {
        if (is_array($formatter)) {
            foreach ($formatter as $key => $val) {
                $this->format($key, $val);
            }
        } else {
            $this->formatters[] = [$formatter, $columns];
        }

        return $this;
    }

    public function actions(array $actions)
    {
        $this->actions = $actions;

        return $this;
    }

    public function limit($limit)
    {
        $this->limit = (int) $limit;

        return $this;
    }

    //public function limitOptions($options)
    //{
    //    $this->limitOptions = $options;
    //    $this->pagination->limits = $options;
    //
    //    return $this;
    //}

    public function query(\Closure $callback)
    {
        call_user_func($callback, $this->query);

        return $this;
    }

    public function pagination(\Closure $callback)
    {
        call_user_func($callback, $this->pagination);

        return $this;
    }

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->table, $method], $args);
    }

    public function __get($var)
    {
        return $this->table->{$var};
    }

    public function __set($var, $val)
    {
        $this->table->{$var} = $val;

        return $this;
    }

}