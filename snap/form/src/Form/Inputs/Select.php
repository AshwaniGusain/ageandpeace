<?php

namespace Snap\Form\Inputs;

use Snap\Form\Contracts\HiddenInterface;
use Snap\Ui\Traits\AttrsTrait;
use Snap\Form\Contracts\OptionableInterface;
use Snap\Form\Fields\Traits\HasOptions;
use Illuminate\Contracts\Support\Arrayable;

class Select extends BaseInput implements OptionableInterface, HiddenInterface
{
    use AttrsTrait;
    use HasOptions;

    protected $data = [
        'hide_if_one' => false,
        'placeholder' => null,
        'multiple' => false,
        'options' => [],
        'disabled_options' => [],
    ];
    protected $view = 'form::inputs.select';
    //protected $multiple = false;
    //protected $placeholder = '';

    protected function _render()
    {
        // Must set it this way because we are overwriting the data property
        $this->data['value'] = $this->getValue();

        $this->with(
            [
                'options' => $this->getOptions(),
            ]
        );

        $this->setAttrs(
            [
                'name'     => $this->getName(),
                'id'       => $this->getId(),
                'multiple' => $this->getMultiple(),
                'class'    => $this->getAttr('class') ? $this->getAttr('class') : ($this->getAttr('class') !== false ? 'form-control' : ''),
            ]
        );

        return parent::_render();
    }

    /**
     * Force values to array since the options could potentially be multiple.
     *
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        if ($value instanceof Arrayable) {
            $value = $value->toArray();
        }
        $this->value = $value;

        return $this;
    }

    public function convertFromModel($props, $form)
    {
        if (!empty($props['options'])) {
            $this->setOptions($props['options']);
        }
    }

    public function isHidden()
    {
        return $this->hide_if_one && $this->getOptions()->count() <= 1;
    }
}