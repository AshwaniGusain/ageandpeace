<?php 

namespace Snap\Form\Buttons;

class Submit extends BaseButton
{
    protected $view = 'form::buttons.submit';

    protected $value;

    public function __construct($value, array $attrs = [])
    {
        parent::__construct();

        if ($value) $this->setValue($value);
        $this->with($attrs);

        $key = (empty($attrs['name'])) ? $value : $attrs['name'];

        $this->setKey($key);
    }

    protected function _render()
    {
        $this->setAttrs(
            [
                'value' => $this->getValue(),
            ]
        );
    
        return parent::_render();
    }

    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }
    
    public function getValue()
    {
        return $this->value;
    }
    
}