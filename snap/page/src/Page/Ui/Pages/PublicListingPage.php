<?php

namespace Snap\Page\Ui\Pages;

use DB;
use Snap\Ui\UiComponent;

class PublicListingPage extends UiComponent
{
    protected $view = 'page::pages.module.listing';

    protected $data = [
        'query' => null, // Order matters here
        'model' => null,
        'limit' => 10,
        'models' => [],
        'year' => null,
        'month' => null,
        'day' => null,
        'date_field' => 'publish_date',
        'var' => null,
    ];

    public function setQuery($query)
    {
        if ($query instanceof \Closure) {
            $query($this->data['query']);
        } else {
            $this->data['query'] = $query;
        }

        return $this;
    }

    public function setModel($model)
    {
        if (is_string($model)) {
            $this->data['model'] = new $model();
        }

        $this->setQuery($this->data['model']->query());

        return $this;
    }

    protected function _render()
    {
        if ($this->day) {
            $this->query
                ->where(DB::raw('DAY('.$this->date_field.')'), '=', $this->day)
                ->where(DB::raw('MONTH('.$this->date_field.')'), '=', $this->month)
                ->where(DB::raw('YEAR('.$this->date_field.')'), '=', $this->year)
            ;
        } elseif ($this->month) {
            $this->query
                ->where(DB::raw('MONTH('.$this->date_field.')'), '=', $this->month)
                ->where(DB::raw('YEAR('.$this->date_field.')'), '=', $this->year)
            ;
        } elseif ($this->year) {
            $this->query
                ->where(DB::raw('YEAR('.$this->date_field.')'), '=', $this->year)
            ;
        }

        $this->setModels($this->query->paginate($this->limit));

        if ($this->var) {
            $this->data[$this->var] =& $this->models;
        } else {
            $var = snake_case(str_plural(class_basename($this->model)));
            $this->data[$var] =& $this->models;
        }

        return parent::_render();
    }
}