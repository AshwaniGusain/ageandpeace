<?php

namespace Snap\Admin\Modules\Services;

use Illuminate\Http\Request;
use Snap\Database\DBUtil;

/*
$search = SearchService::make();
$search
    ->prefix('/about/')
    ->fromInput('slug')
;
 * */
class SearchService
{
    public $searchRequestParam = 'q';
    public $columns = '*';
    public $term;

    protected $module;
    protected $request;
    protected $searched;

    public function __construct($module, Request $request)
    {
        $this->module = $module;
        $this->request = $request;
        $this->term = $request->input($this->searchRequestParam);
    }

    public static function make($module)
    {
        $service = new static($module, request());

        return $service;
    }

    public function columns($columns)
    {
        $columns = is_array($columns) ? $columns : func_get_args();

        $this->columns = $columns;

        return $this;
    }

    public function query($term = null, array $columns = [])
    {
        // Only allow this query to be run once
        if ($this->searched) {
            return;
        }

        if ($term instanceof \Closure) {
            call_user_func($term, $this->module->getQuery());
            $this->searched = true;

        } else {

            if ($term) {
                $this->term = $term;
            }

            if ($columns) {
                $this->columns($columns);
            }

            $query = $this->module->getQuery();
            $model = $query->getModel();

            if ($this->term) {

                // Must use $query instead of $q which is a new Query object that hasn't been manipulated.
                $query->where(function ($q) use ($query, $model) {

                    if ($this->columns == '*') {
                        $columns = DBUtil::columnList($model->getTable())->toArray();
                        $this->columns = array_remove_value($columns, $model->getKeyName());
                    }

                    foreach ((array)$this->columns as $column) {

                        if (strpos($column, ':') === false) {
                            $column = $model->getTable().'.'.$column;
                            $q->orWhere($column, 'like', '%'.$this->term.'%');

                        } else {

                            $relationship = substr($column, 0, strpos($column, ':'));
                            if (method_exists($model, $relationship)) {
                                //$foreignTable = $foreignModel->getTable();
                                $column = substr($column, strpos($column, ':') + 1);

                                $divider = strpos($column, ':');
                                $q->orWhereHas($relationship, function($q2) use($column, $divider) {
                                    $q2->where(substr($column, $divider), 'like', $this->term . '%');
                                });

                            }
                        }
                    }
                });

                $this->searched = true;
            }
        }

    }

    /**
     * Determines if the query has run the search query.
     *
     * @return bool
     */
    public function isSearched()
    {
        return (bool) $this->searched;
    }

}