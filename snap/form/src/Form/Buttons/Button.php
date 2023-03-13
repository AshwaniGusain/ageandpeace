<?php

namespace Snap\Form\Buttons;

class Button extends BaseButton
{
	protected $view = 'form::buttons.button';

	protected $label;

    public function __construct($label, array $attrs = [])
    {
        parent::__construct($attrs);

        if ($label) $this->setLabel($label);
        $this->with($attrs);

        $this->setKey($label);
    }

    protected function _render()
    {
        $this->data['label'] = $this->getLabel();

        return parent::_render();
    }

    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }
    
    public function getLabel()
    {
        return $this->label;
    }
	
}