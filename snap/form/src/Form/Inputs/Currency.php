<?php

namespace Snap\Form\Inputs;

class Currency extends Input
{
    protected $inputType = 'text';
    protected $view = 'form::inputs.currency';
    protected $scripts = [
        'assets/snap/js/components/form/CurrencyInput.js',
    ];
    protected $data = [
        'symbol' => '$',
    ];

    public function initialize()
    {
        // This isn't necessary since we have a hidden field taking care of the true value, but will leave here as a reference.
        $this->setPostProcess(function($value, $input, $request){
            $request->request->set($this->key, (float) str_replace(['$', ','], '', $value));
        });

        //$this->label->setFor($this->key.'_formatted');

    }

    protected function _render()
    {
        $this->setAttrs([
            'symbol' => $this->getSymbol(),
            'class' => ! is_null($this->getAttr('class')) ? $this->getAttr('class') : '',
        ]);

        return parent::_render();
    }
}