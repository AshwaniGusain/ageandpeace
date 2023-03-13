<?php

namespace Snap\Form\Buttons;

use Snap\Form\Contracts\ButtonInterface;
use Snap\Form\FormElement;
use Snap\Ui\Traits\AttrsTrait;
use Snap\Ui\Traits\CssClassesTrait;

abstract class BaseButton extends FormElement implements ButtonInterface
{
    use AttrsTrait;
    use CssClassesTrait;

    protected $size = '';
    protected $data = [
        'label' => '',
    ];

    protected function _render()
    {
        $this->setAttrs([
                'class' => 'btn btn-secondary',
            ]);

        if ($this->size) {
            $this->addClass('btn-'.$this->size);
        }

        return parent::_render();
    }

    public function getSize()
    {
        return $this->size;
    }

    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }
}