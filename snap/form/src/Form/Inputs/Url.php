<?php

namespace Snap\Form\Inputs;

class Url extends Input
{
    protected $inputType = 'url';

    /**
     * Returns the display value.
     *
     * @return string
     */
    public function getDisplayValue()
    {
        return anchor($this->value);
    }
}