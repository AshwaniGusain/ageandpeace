<?php

namespace Snap\Admin\Modules\Traits\Filters;

use Illuminate\Http\Request;

class FilterManager
{
    protected $filtered = false;
    protected $filters = [];
    protected $form;
    protected $query;
    protected $request;

    public function __construct($query, Request $request)
    {
        $this->query = $query;
        $this->request = $request;
    }

    /**
     * Adds filtering to the module's query object.
     *
     * @param $key
     * @param $closure
     * @return $this
     */
    public function add($key, $method = 'where', $operator = '=')
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                list($m, $o) = $v;
                $this->add($k, $m, $o);
            }
        } else {
            $filter = ($key instanceof Filter) ? $key : new Filter($key, $method, $operator);
            $this->filters[$filter->key] = $filter;
            $value = $this->request->input($filter->key);

            $filter->apply($this->query, $value, $this->request);

            if ($filter->isApplied()) {
                $this->filtered = true;
            }
            //$this->filters[] = [$key, $method, $operator];
            //
            //$value = $this->request->input($key);
            //
            //if ($method instanceof \Closure) {
            //    call_user_func($method, $this->getQuery(), $key, $value, $this->request);
            //} else {
            //    $column = (strpos($key, '.') === false) ? $this->module->getModel()->getTable() . '.' . $key : $key;
            //    $exists = (is_array($value)) ? array_exists($value) : !empty($value);
            //
            //    if ($exists) {
            //        call_user_func([$this->module->getQuery(), $method], $column, $operator, $value);
            //        $this->filtered = true;
            //    }
            //}
        }

        return $this;
    }

    public function get($key)
    {
        if (isset($this->filters[$key])) {
            return $this->filters[$key];
        }

        return null;
    }

    //public function apply()
    //{
    //    foreach ($this->filters as $filter) {
    //        $filter->apply($this->query, $this->request);
    //        if ($filter->isApplied()) {
    //            $this->filtered = true;
    //        }
    //    }
    //}

    public function getForm($inputs = [])
    {
        if (!isset($this->form)) {
            $this->form = \Form::make();
            $inputs = array_merge($this->getFilterInputs(), $inputs);
            $this->form->add($inputs);
            //$this->addFilters($this->extractFiltersFromInputs($inputs));
        }

        return $this->form;
    }

    public function getFilterInputs()
    {
        $inputs = [];
        foreach ($this->filters as $filter) {
            if ($filter->input) {
                $inputs[$filter->key] = $filter->input;
            }
        }

        return $inputs;
    }


    /**
     * Returns whether or not the query results have filters applied to them.
     *
     * @return bool
     */
    public function isFiltered()
    {
        return (bool) $this->filtered;
    }
    //public function withInput($type)
    //{
    //    $this->form()->add($type);
    //
    //    return $this;
    //}
    ///**
    // * Returns the Snap/Form/Form object used for the UIComponent.
    // *
    // * @return mixed
    // */
    //public function form($inputs = [])
    //{
    //    if (!isset($this->form)) {
    //        $this->form = \Form::make();
    //        $inputs = array_merge($this->getFilterInputs(), $inputs);
    //        $this->form->add($inputs);
    //        $this->addFilters($this->extractFiltersFromInputs($inputs));
    //    }
    //
    //    return $this->form;
    //}
    //
    //public function ui()
    //{
    //    //return $this->module-;
    //}
    //
    ///**
    // * @param $inputs
    // * @return array
    // */
    //protected function extractFiltersFromInputs($inputs)
    //{
    //    $filters = [];
    //    foreach ($inputs as $input) {
    //        if (!isset($this->filters[$input->key])) {
    //            $filters[] = $input->key;
    //        }
    //    }
    //
    //    return $filters;
    //}

}