<?php

namespace Snap\Form\Inputs;

class Password extends Input
{
    protected $inputType = 'password';

    public function initialize()
    {
        $this->setPostProcess(function($value, $input, $request, $resource){
            if ($request->{$this->key}) {
                $resource->{$this->key} = \Hash::make($value);
            }
        }, 'beforeSave');
    }

    // So it doesn't display the password information.
    public function setValue($value)
    {
        return $this;
    }
}