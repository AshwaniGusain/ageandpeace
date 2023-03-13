<?php

namespace Snap\Form\Inputs;

use Snap\Support\Helpers\ArrayHelper;
use Snap\Support\Helpers\UtilHelper;
use Snap\Ui\Traits\VueTrait;
use Snap\Ui\Traits\AttrsTrait;

class Toggle extends Select
{
    use VueTrait;

    protected $vue = 'snap-toggle-input';

    protected $scripts = [
        'assets/snap/js/components/form/ToggleInput.js',
    ];

    protected $data = [
        'attrs' => [
            'context' => '.form',
            'selector' => '.toggle',
            'mode' => 'select',
            //'inline-template' => true,
        ],
    ];

    public function setMode($mode)
    {
        $this->setAttr('mode', $mode);

        return $this;
    }

    public function getMode()
    {
        return $this->getAttr('mode');

    }

    public function setSelector($selector)
    {
        $this->setAttr('selector', $selector);

        return $this;
    }

    public function getSelector()
    {
        return $this->getAttr('selector');

    }

    public function isInline()
    {
        return $this->getMode() == 'checkbox' ? true : false;
    }

    protected function _render()
    {
        if ($this->getMode() == 'checkbox') {
            $this->setAttr('class', false);
            $this->setAttr('value', ($this->value == 1 ? 1 : 0));
        }
        //$this->data['value'] = $this->getValue();
        //
        //$this->setAttrs(
        //    [
        //        'name'  => $this->getName(),
        //        'id'    => $this->getId(),
        //        //'class' => 'form-control',
        //        ':value' => $this->getValue() // For Vue.js component
        //    ]
        //);

        return parent::_render();
    }

    public function convertFromModel($props, $form)
    {
        if (!empty($props['options'])) {
            $this->setOptions($props['options']);
        } else {
            $booleanOptions = [
                '0' => trans('form::inputs.no'),
                '1' => trans('form::inputs.yes'),
            ];
            $this->setOptions($booleanOptions, false);
        }
    }

}