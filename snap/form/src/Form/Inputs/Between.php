<?php

namespace Snap\Form\Inputs;

use Snap\Ui\Traits\AttrsTrait;

class Between extends BaseInput
{
    use AttrsTrait;
    protected $view = 'form::inputs.between';

    protected $inputType = 'number';
    protected $data = [
        'divider' => '-',
    ];

    /**
     * @return string
     */
    public function getInputType(): string
    {
        return $this->inputType;
    }

    /**
     * @param string $inputType
     * @return Between
     */
    public function setInputType(string $inputType)
    {
        $this->inputType = $inputType;

        return $this;
    }

    protected function _render()
    {
        $value = (array) $this->getValue();
        $value1 = $value[0] ?? null;
        $value2 = $value[1] ?? null;

        $this->setData(
            [
                'input1'      => \Form::element($this->getName().'[]', $this->inputType, ['label' => false, 'value' => $value1, 'attrs' => $this->getAttrs()]),
                'input2'      => \Form::element($this->getName().'[]', $this->inputType, ['label' => false, 'value' => $value2, 'attrs' => $this->getAttrs()]),
            ]
        );

        return parent::_render();
    }

}