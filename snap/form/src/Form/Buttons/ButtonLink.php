<?php

namespace Snap\Form\Buttons;

class ButtonLink extends BaseButton
{
    protected $view = 'form::buttons.link';

    protected $value;
    protected $data = [
        'attrs' => ['href' => '#']
    ];

    public function __construct($label, array $attrs = [])
    {
        parent::__construct();

        if ($label) $this->setLabel($label);
        $this->with($attrs);

        $this->setKey($label);
    }

    public function getHref()
    {
        return $this->data['attrs']['href'];
    }

    public function setHref($href)
    {
        $this->data['attrs']['href'] = $href;

        return $this;
    }
}