<?php

namespace Snap\Page\Ui\Pages;

use Illuminate\Http\Request;
use Snap\Ui\UiComponent;

class PublicDetailPage extends UiComponent
{
    protected $view = 'page::pages.module.detail';

    protected $data = [
        'model' => null,
        'var' => null,
    ];

    protected function _render()
    {
        $this->model->fill($this->cleanedRequestVars());

        if ($this->var) {
            $this->data[$this->var] =& $this->model;
        } else {
            $var = snake_case(class_basename($this->model));
            $this->data[$var] =& $this->model;
        }

        return parent::_render();
    }

    protected function cleanedRequestVars()
    {
        $vars = [];
        $ignore = ['_method', '_token'];
        foreach(request()->all() as $key => $val) {
            if ( ! in_array($key, $ignore)) {
                $vars[$key] = $val;
            }
        }

        return $vars;
    }
}