<?php

namespace Snap\Form\Inputs;

use Snap\Ui\Traits\VueTrait;

class DualMultiSelects extends MultiSelect
{
    use VueTrait;

    protected $view = 'form::inputs.dual-multi-selects';

    protected $scripts = [
        'assets/snap/js/components/form/DualMultiSelectsInput.js',
    ];

    protected function _render()
    {
        $this->setAttr('class', false);

        return parent::_render();
    }
}