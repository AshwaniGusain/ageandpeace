<?php

namespace Snap\Form;

use Illuminate\Support\Collection;
use Snap\Form\Contracts\FormElementInterface;

class FormElements extends Collection
{
    public function clone()
    {
        $items = [];
        foreach ($this->items as $key => $item) {
            $items[$key] = clone ($item);
        }

        return new static($items);
    }

    /**
     * @param $name
     * @param null $input
     * @return $this
     */
    public function set($name, $input = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                $this->set($key, $val);
            }
        } elseif ($name instanceof FormElements && ! $name->isEmpty()) {
            $this->items->merge($name);
        } elseif ($name instanceof FormElementInterface) {
            $this->items[$name->getKey()] = $name;
        } elseif ($input instanceof FormElementInterface) {
            $this->items[$name] = $input;
        }

        return $this;
    }

    public function assign($prop, $val = null, $inputs = null)
    {
        if (! is_callable($prop)) {
            $filter = function ($input) use ($prop, $val, $inputs) {
                if (empty($inputs) || (! empty($inputs)) && in_array($input->getKey(), $inputs)) {
                    //$method = 'set'.ucfirst(camel_case($prop));
                    $input->{$prop} = $val;
                }
            };
        } else {
            $filter = $prop;
        }

        $this->each($filter);

        return $this;
    }

    /**
     * @param mixed $key
     * @param mixed $value
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

}