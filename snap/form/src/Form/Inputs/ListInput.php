<?php

namespace Snap\Form\Inputs;

use Snap\Support\Helpers\ArrayHelper;
use Snap\Support\Helpers\UtilHelper;
use Snap\Ui\Traits\VueTrait;
use Snap\Ui\Traits\AttrsTrait;

class ListInput extends BaseInput
{
    use VueTrait;
    use AttrsTrait;

    protected $vue = 'snap-list-input';

    protected $scripts = [
        'assets/snap/js/components/form/ListInput.js',
    ];

    protected $view = 'form::inputs.textarea';
    protected $data = [
        'attrs' => [
        ],
    ];

    protected function _render()
    {
        $this->data['value'] = $this->getValue();

        $this->setAttrs(
            [
                'name'  => $this->getName(),
                'id'    => $this->getId(),
                //'class' => 'form-control',
                ':value' => $this->getValue() // For Vue.js component
            ]
        );

        return parent::_render();
    }

    public function setValue($value)
    {
        // Detect if it is just a comma separated list and if so, convert it into an array so we can properly encode it.
        if (is_string($value) && ! UtilHelper::isJson($value)) {
            $value = ArrayHelper::normalize($value);
        }

        $this->value = json_encode($value);

        return $this;
    }

}