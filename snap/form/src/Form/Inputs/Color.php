<?php

namespace Snap\Form\Inputs;

class Color extends Input
{
    protected $inputType = 'color';

    protected $data = [
        'attrs' => [':config' => null]
    ];

    protected function _render()
    {
        $this->setAttrs([
            'class' => ! is_null($this->getAttr('class')) ? $this->getAttr('class') : '',
        ]);

        return parent::_render();
    }
}