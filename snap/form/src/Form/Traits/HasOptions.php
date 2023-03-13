<?php

namespace Snap\Form\Fields\Traits;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

trait HasOptions
{
    protected $equalizeKeyValue = 'auto';
    protected $model = null;
    //protected $multiple = false;
    //protected $placeholder = '';

    public function setOptions($options, $equalize = null)
    {
        if (is_bool($equalize)) {
            $this->setEqualizeKeyValue($equalize);
        }

        if ($options instanceof Closure) {
            $options = call_user_func($options, $this);
            //} elseif (is_subclass_of($options, Model::class)) {
            //    $options = (new $options())->get()->pluck('name', 'id');
        } elseif (is_string($options)) {
            $parts = $this->parseModelMethod($options);
            $options = call_user_func_array($parts['class'].'::'.$parts['method'], $parts['params']);
        }

        // $options = $this->normalizeKeyValues($options);
        if ($options instanceof Arrayable) {
            $options = $options->toArray();
        }

        $this->data['options'] = collect($options);

        return $this;
    }

    protected function parseModelMethod($modelMethod)
    {
        $parts = explode('::', $modelMethod);
        $class = $parts[0];
        $method = 'lists';
        $params = [];


        // Looks for "Class" :: "method" : "value" : "key"
        // e.g. \App\Models\MyModel::pluck:id:name
        if (! empty($parts[1])) {
            $arr = explode(',', $parts[1]);
            $method = $arr[0];

            if (! empty($arr[1])) {
                $params = preg_split('#\s*,\s*#', $arr[1]);
            }
        } else {
            $arr = explode(':', $modelMethod);
            $class = $arr[0];
            array_shift($arr);

            if (! empty($arr)) {
                $params = $arr;

                if (empty($params[1])) {
                    $params[1] = $params[0];
                }

                $params = array_reverse($params, true);
            }
        }

        return ['class' => $class, 'method' => $method, 'params' => $params];
    }

    public function getOptions()
    {
        $options = $this->data['options'] ?? collect([]);
        $options = $this->normalizeKeyValues($options);

        $placeholder = $this->getPlaceholder();
        if ($placeholder === true) {
            $placeholder = $this->getDefaultPlaceholder();
        }

        // Add the arrays instead of array_merge to preserve the keys
        if ($placeholder && ! $options->contains($placeholder)) {
            $options->prepend($placeholder, '');
        }

        return $options;
    }

    public function hasOptions()
    {
        return ! empty($this->data['options']);
    }

    public function setPlaceholder($placeholder)
    {
        if ($placeholder === true) {
            $placeholder = $this->getDefaultPlaceholder();
        }

        $this->data['placeholder'] = $placeholder;

        return true;
    }

    public function getPlaceholder()
    {
        return $this->data['placeholder'] ?? '';
    }

    public function getDefaultPlaceholder()
    {
        return ($this->isMultiple()) ? trans('form::inputs.select_one_or_more') : trans('form::inputs.select_one');
    }

    public function setMultiple($multiple)
    {
        $this->data['multiple'] = $multiple;
        if ($multiple) {
            $this->name = str_replace('[]', '', $this->name) . '[]';
        }

        return $this;
    }

    public function isMultiple()
    {
        return $this->data['multiple'];
    }
//
//    protected function getSelectedValue($selected)
//    {
//        if (is_array($selected)) {
//            return in_array($this->value, $selected) ? 'selected' : null;
//        }
//
//        return ((string) $this->value == (string) $selected) ? 'selected' : null;
//    }

    protected function normalizeKeyValues($options)
    {
        if ($this->isEqualizeKeyValue()) {
            $newOptions = [];

            foreach ($options as $key => $val) {
                $newOptions[$val] = $val;
            }
            $options = collect($newOptions);
        }

        return $options;
    }

    public function isEqualizeKeyValue()
    {
        $options = $this->data['options'] ?? collect([]);
        return $this->equalizeKeyValue === true || (strtolower($this->equalizeKeyValue) == 'auto' && key($options->toArray()) === 0);
    }

    public function getEqualizeKeyValue()
    {
        return $this->equalizeKeyValue;
    }

    public function setEqualizeKeyValue($equalizeKeyValue)
    {
        $this->equalizeKeyValue = (bool) $equalizeKeyValue;

        return $this;
    }

    public function setModel($model, $setOptions = null)
    {
        if (is_string($model)) {
            $model = new $model;
        }

        if (is_null($setOptions) && (is_array($this->data['options']) && !count($this->data['options']) || (is_object($this->data['options']) && !$this->data['options']->count()))) {
            $setOptions = true;
        }

        if ($setOptions) {
            $label = (method_exists($model, 'getDisplayNameAttribute')) ? 'display_name' : $model->getKeyName();
            $key = $model->getKeyName();
            $query = $model->withoutGlobalScopes();
            if ($model->isSoftDelete()) {
                $query->withoutTrashed();
            }
            $opts = $query->get()->pluck($label, $key);

            $this->setOptions($opts);
        }

        $this->model = $model;

        return $this;
    }

    public function getModel()
    {
        return $this->model;
    }

}