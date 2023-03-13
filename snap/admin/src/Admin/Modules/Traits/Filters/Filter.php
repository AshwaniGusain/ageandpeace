<?php

namespace Snap\Admin\Modules\Traits\Filters;

use Snap\Form\Contracts\FormElementInterface;

class Filter
{
    public $key;
    public $method;
    public $operator;
    public $input;
    protected $applied;

    public function __construct($key, $method = 'where', $operator = null)
    {
        $this->key = $key;
        $this->method = $method;
        $this->operator = $operator;
    }

    public static function make($key, $method = 'where', $operator = null, $input = null)
    {
        $filter = new static($key, $method, $operator);

        if ($input) {
            if ($input instanceof FormElementInterface) {
                $filter->withInput($input);
            } else {
                call_user_func_array([$filter, 'withInput'], $input);
            }
        }

        return $filter;
    }

    public function apply($query, $value, $request)
    {
        if (!$this->isApplied()) {
            if ($this->method instanceof \Closure) {
                call_user_func($this->method, $query, $this->key, $value, $request);
            } elseif ($this->method !== false) {

                $column = (strpos($this->key, '.') === false) ? $query->getModel()->getTable() . '.' . $this->key : $this->key;
                $exists = (is_array($value)) ? array_exists($value) : (!is_null($value) && $value !== '');

                if ($exists) {
                    if ($this->operator) {
                        call_user_func([$query, $this->method], $column, $this->operator, $value);
                    } else {
                        call_user_func([$query, $this->method], $column, $value);
                    }

                    $this->applied = true;
                }
            }
        }
    }

    public function withInput($type, $props = [])
    {
        if (! $type instanceof FormElementInterface) {
            $this->input = \Form::element($this->key, $type, $props);
        } else {
            $this->input = $type;
        }

        return $this;
    }

    public function isApplied()
    {
        return $this->applied;
    }
}