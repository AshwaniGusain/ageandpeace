<?php

namespace Snap\Form\Inputs;

use Illuminate\Support\Str;
use Snap\Ui\Traits\AttrsTrait;

class Table extends BaseInput
{
    use AttrsTrait;

    protected $view = 'form::inputs.table';

    protected $scripts = [
        'assets/snap/js/components/form/TableInput.js',
        'assets/snap/js/components/resource/DataTable.js',
        'assets/snap/js/components/form/EditInlinePopover.js',
    ];

    protected $data = [
        'module'        => null,
        'filters'       => [],
        'creatable'    => true,
        'create_params' => [],
    ];

    public function initialize()
    {
        $this->label->setText(false);

        if (empty($this->module)) {
            $module = Str::singular($this->key);
            if (\Admin::modules()->has($module)) {
                $this->setModule($module);
            }
        }
    }

    public function setModule($module)
    {
        if (is_string($module)) {
            $module = \Admin::modules()->get($module);
        }
        $this->data['module'] = $module;

        return $this;
    }

    public function isCreatable()
    {
        return $this->creatable && $this->module->hasPermission('create');
    }

    protected function _render()
    {
        $tableUrl = $this->module->url('table');
        $this->data['filters']['inline'] = 1;

        $tableUrl .= '?'.http_build_query($this->filters);

        $this->setAttr('table-url', $tableUrl);

        if ($this->isCreatable()) {
            $createUrl = $this->module->url('create_inline');
            if (! empty($this->create_params)) {
                $createUrl .= '?'.http_build_query($this->create_params);
            }
            $this->setAttr('create-url', $createUrl);
        }

        return parent::_render();
    }

}