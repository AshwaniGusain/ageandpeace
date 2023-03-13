<?php

namespace Snap\Form\Inputs;

use Snap\Form\Fields\Traits\HasStepMinMax;
use Snap\Ui\Traits\VueTrait;

class Range extends Input
{
    use VueTrait;
    use HasStepMinMax;

    protected $vue = 'snap-range-input';
    protected $scripts = [
        'assets/snap/js/components/form/RangeInput.js',
    ];

    protected $inputType = 'range';

    protected $view = 'form::inputs.range';

    protected $data = [
        'max'  => 10,
        'min'  => 0,
        'step' => 1,
        'prefix' => '',
        'suffix' => '',
    ];

    public function setSuffix($suffix)
    {
        $this->setAttr('suffix', $suffix);

        return $this;
    }

    public function getSuffix()
    {
        return $this->getAttr('suffix');
    }

    public function setPrefix($prefix)
    {
        $this->setAttr('prefix', $prefix);

        return $this;
    }

    public function getPrefix()
    {
        return $this->getAttr('prefix');
    }

    protected function _render()
    {
        $this->setToVueLiteral(['max', 'min', 'step']);


        $this->setAttrs([
            'class' => false,
        ]);
        //
        //$this->removeAttr('class');

        return parent::_render();
    }

}