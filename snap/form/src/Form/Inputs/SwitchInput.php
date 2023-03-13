<?php

namespace Snap\Form\Inputs;

class SwitchInput extends Checkbox
{
    protected $view = 'form::inputs.switch';

    protected function _render()
    {
        $this->setAttrs(
            [
                'class' => !is_null($this->getAttr('class')) ? $this->getAttr('class') : 'custom-control-input',
            ]
        );
        return parent::_render();
    }

}