<?php

namespace Snap\Form\Inputs;

use Snap\Form\Contracts\CheckableInterface;
use Snap\Form\Contracts\InlineLabelInterface;

class Checkbox extends Input implements CheckableInterface, InlineLabelInterface
{
    protected $value = 1;
    protected $view = 'form::inputs.checkbox';
    protected $inputType = 'checkbox';
    protected $data = [
        'enforce_if_empty' => true,
    ];
    protected function _render()
    {
        $this->setAttrs(
            [
                'class' => !is_null($this->getAttr('class')) ? $this->getAttr('class') : 'form-check-input',
            ]
        );
        return parent::_render();
    }

    public function setValue($value)
    {
        if ($this->checkIfMatchValue($value)) {
            $this->setChecked(true);
        } else {
            $this->setChecked(false);
        }

        return $this;
    }

    public function checkIfMatchValue($value)
    {
        if ($value == 1) {
            return true;
        }

        return false;
    }

    public function setChecked($checked)
    {
        $this->setAttr('checked', $checked);

        return $this;
    }

    public function getChecked()
    {
        return $this->getAttr('checked');
    }

    public function isInlineLabel()
    {
        return true;
    }

    public function _renderDisplayValue()
    {
        return ($this->checked) ? trans('form::inputs.yes') : trans('form::inputs.no');
    }
}