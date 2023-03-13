<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;

/*
$table = TableService::make();
$table
    ->columns(['name', 'slug', ....])
    ->defaultSort('-date_modified')
    ->sortRequest('sort')
    ->paginationLimit()
    ->format();
;
 * */
class PaginationService
{
    public $limitRequestParam = 'limit';
    public $limits = [50, 100, 200];

    //protected $default;
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

    public function limits(array $limits)
    {
        $this->limits = $limits;

        return $this;
    }
    /**
     * The query string parameter used to determine the pagination limit.
     *
     * @return string
     */
    //public function getLimitRequestParam()
    //{
    //    return $this->limitRequestParam;
    //}
    //
    //public function getLimits()
    //{
    //    return $this->limits;
    //}

    /**
     * The default limit of the pagination.
     *
     * @return int
     */
    public function getDefaultLimit()
    {
        return (int) current($this->limits);
    }

    /**
     * The limit value for the pagination.
     *
     * @return int
     */
    public function getLimit()
    {
        return $this->request->input($this->limitRequestParam) ?? $this->getDefaultLimit();
    }

}