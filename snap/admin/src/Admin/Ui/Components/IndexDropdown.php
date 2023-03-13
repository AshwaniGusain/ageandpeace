<?php

namespace Snap\Admin\Ui\Components;

use Snap\Ui\Traits\JsTrait;
use Snap\Ui\Components\Bootstrap\Dropdown;

class IndexDropdown extends Dropdown
{
    use JsTrait;

    protected $scripts = [
        'assets/snap/js/components/form/Form.js',
    ];

    public function initialize()
    {
        $this->label = '<i class="fa fa-gear"></i>';
        $this->container = false;


        //if ($this->module->hasTrait('delete')) {
        //    $this->add($this->module->url(), 'Delete Selected Items');
        //}
        //
        //if ($this->module->hasTrait('export')) {
        //    $this->add($this->module->url('export'), 'Export');
        //}
        //if (! $this->options->count()) {
        //    $this->visible(false);
        //}

        //if ($this->items)
    }

    protected function createLink()
    {
        // return '<a href="' . $this->module->adminUrl(. '"></a>';
    }

    protected function _render()
    {
        if ($this->options->count()) {
            return parent::_render();
        }
        return '';
    }
}