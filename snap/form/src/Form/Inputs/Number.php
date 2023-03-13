<?php

namespace Snap\Form\Inputs;

use Snap\Ui\Traits\VueTrait;

use Snap\Form\Fields\Traits\HasStepMinMax;

class Number extends Input
{
    use VueTrait;
    use HasStepMinMax;

    protected $vue = 'snap-number-input';
    protected $scripts = [
        'assets/snap/js/components/form/NumberInput.js',
    ];
    protected $inputType = 'number';

    protected $data = [
        'max'  => null,
        'min'  => null,
        'step' => null,
    ];

    protected function _render()
    {
        $this->setToVueLiteral(['max', 'min']);
        return parent::_render();
    }
}