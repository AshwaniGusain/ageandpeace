<?php

namespace Snap\Form\Inputs;

class Radio extends Input
{
    protected $inputType = 'radio';
    protected $view = 'form::inputs.radio';

    protected function _render()
    {
        $this->setAttrs(
            [
                'class' => !is_null($this->getAttr('class')) ? $this->getAttr('class') : 'form-check-input',
            ]
        );

        return parent::_render();
    }

    public function setChecked($checked) {
        $this->setAttr('checked', $checked);

        return $this;
    }

    public function getChecked() {
        return $this->getAttr('checked');
    }
}