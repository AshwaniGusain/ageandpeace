<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;
use Snap\Admin\Modules\Traits\Filters\FilterManager;

/*
$calendar = CalendarService::make();
$calendar
    ->startDateColumn('start_date')
    ->endDateColumn('end_date')
;
*/
class CalendarService
{
    public $startDateColumn = 'start_date';
    public $endDateColumn = 'end_date';

    protected $module;
    protected $request;
    protected $model;

    public function __construct($module, Request $request)
    {
        $this->module = $module;
        $this->request = $request;
        $this->model = $this->module->getModel();

        $this->initializeStartDateColumn();
        $this->initializeEndDateColumn();
    }

    public static function make($module)
    {
        $service = new static($module, request());

        return $service;
    }

    public function startDateColumn($startDateColumn)
    {
        $this->startDateColumn = $startDateColumn;

        return $this;
    }

    public function endDateColumn($endDateColumn)
    {
        $this->endDateColumn = $endDateColumn;

        return $this;
    }

    protected function initializeStartDateColumn()
    {
        if (method_exists($this->model, 'getStartDateColumn')) {
            $this->startDate = $this->model->getStartDateColumn();
        } elseif (method_exists($this->model, 'getPublishDateColumn')) {
            $this->startDate = $this->model->getPublishDateColumn();
        }

        return $this;
    }

    protected function initializeEndDateColumn()
    {
        if (method_exists($this->model, 'getEndDateColumn')) {
            $this->startDate = $this->model->getEndDateColumn();
        } elseif (method_exists($this->model, 'getPublishDateColumn')) {
            $this->startDate = $this->model->getPublishDateColumn();
        }

        return $this;
    }

}