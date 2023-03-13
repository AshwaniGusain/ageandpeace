<?php

namespace Snap\Form\Inputs;

use Snap\Ui\Traits\AttrsTrait;
use Snap\Form\Contracts\OptionableInterface;
use Snap\Form\Fields\Traits\HasOptions;
use Illuminate\Contracts\Support\Arrayable;

class MultiSelect extends Select
{

    protected function _render()
    {
        $this->multiple = true;

        return parent::_render();
    }

}