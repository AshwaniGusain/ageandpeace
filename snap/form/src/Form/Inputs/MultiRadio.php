<?php

namespace Snap\Form\Inputs;

use Snap\Form\Fields\Traits\HasOptions;
use Snap\Menu\Item;
use Snap\Menu\MenuBuilder;
use Snap\Ui\Traits\AttrsTrait;

class MultiRadio extends BaseInput
{
    use HasOptions;
    use AttrsTrait;

    protected $view = 'form::inputs.multi-radio';
    protected $data = [
        'radios' => [],
        'default' => null,
    ];

    public function initialize()
    {
    }

    public function convertFromModel($field, $form)
    {
        if (!empty($field['options'])) {
            $this->setOptions($field['options']);
        }
    }

    protected function _render()
    {
        $radios = [];

        foreach ($this->getOptions() as $value => $label) {
            if (!$value instanceof Radio) {
                if (substr((string) $value, 0, 1) === '*' && substr((string) $value, -1, 1) === '*') {
                    $radio = $this->createGroupLabel($label);
                } else {
                    $radio = $this->createRadio(humanize($label), $value);
                }
            } else {
                $radio = $value;
            }

            $radios[] = $radio;
        }

        $this->setRadios($radios);

        // Set the default checked radio to the 1st radio unless the default
        // value is explicitly set to false.
        $defaultValue = $this->default;
        if (is_null($defaultValue)) {
            foreach ($this->radios as $key => $radio) {
                // Set the value to the first radio if no value is set.
                if ($radio instanceof Radio) {
                    $defaultValue = $radio->getValue();
                    break;
                }
            }
        }

        // Now set the checked radio.
        foreach ($this->radios as $key => $radio) {
            $checkedValue = (!is_null($this->value)) ? $this->value : $defaultValue;
            if ($checkedValue === $radio->value) {
                $radio->setChecked(true);
                break;
            }
        }


        return parent::_render();
    }

    protected function createRadio($label, $value)
    {
        $radio = new Radio($this->getName(), ['value' => $value, 'label' => $label]);

        return $radio;
    }

    protected function createGroupLabel($label)
    {
        return '<div class="form-check-group-label">'.$label.'</div>';
    }

}