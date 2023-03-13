<?php

namespace Snap\Form\Inputs;

use Snap\Form\Contracts\HiddenInterface;

class Hidden extends Input implements HiddenInterface
{
    protected $inputType = 'hidden';

    protected $view = 'form::inputs.hidden';

    // Overwritten because there are no special properties coming from a table's field.
    public function convertFromModel($props, $form)
    {
    	
    }

    public function isHidden()
    {
        return true;
    }

}