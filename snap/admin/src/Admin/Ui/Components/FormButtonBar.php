<?php

namespace Snap\Admin\Ui\Components;

use Snap\Ui\Components\Bootstrap\ButtonGroup;

class FormButtonBar extends ButtonGroup
{
    protected $data = [
        'module' => null,
        'resource' => null,
    ];

    public function initialize()
    {
        // Moved to FormPage
        //$this->add(trans('admin::resources.btn_save'), ['id' => 'btn-save', 'type' => 'primary', 'class' => 'border']);
        //
        //if ($this->module) {
        //
        //    $this->add(trans('admin::resources.btn_save_and_exit'), ['id' => 'btn-save-exit', 'type' => 'primary', 'class' => 'border']);
        //
        //    if ($this->module->hasPermission('create')) {
        //        $this->add(trans('admin::resources.btn_save_and_create'), ['id' => 'btn-save-create', 'type' => 'primary', 'class' => 'border']);
        //
        //        //if ($this->resource && $this->resource->getKey()) {
        //        //    $this->add(trans('admin::resources.btn_duplicate'), ['id' => 'btn-duplicate', 'type' => 'primary', 'class' => 'border', 'attrs' => ['href' => $this->module->url('duplicate', ['id' => $this->resource->id])]]);
        //        //}
        //    }
        //}
    }

    protected function _render()
    {
        return parent::_render();
    }
}