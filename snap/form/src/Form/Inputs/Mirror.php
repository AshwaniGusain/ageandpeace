<?php

namespace Snap\Form\Inputs;

use Snap\Ui\Traits\VueTrait;

class Mirror extends Input
{
    use VueTrait;

    protected $vue = 'snap-mirror-input';
    protected $view = 'form::inputs.input';
    protected $scripts = [
        'assets/snap/js/components/form/MirrorInput.js',
    ];

    protected $boundTo = 'name';
    protected $mask = '[^A-Za-z0-9\/]';
    protected $maskReplacer = '';

    public function initialize()
    {
        //$this->setPostProcess(function($value, $input, $request){
        //    $request->request->set($this->key, str_slug($value));
        //});
    }

    public function getMask()
    {
        return $this->mask;
    }

    public function setMask($mask)
    {
        $this->mask = $mask;

        return $this;
    }

    public function getMaskReplacer()
    {
        return $this->maskReplacer;
    }

    public function setMaskReplacer($mask)
    {
        $this->maskReplacer = $mask;

        return $this;
    }

    public function getBoundTo()
    {
        return $this->boundTo;
    }

    public function setBoundTo($boundTo)
    {
        $this->boundTo = $boundTo;

        return $this;
    }

    protected function _render()
    {
        $this->setAttrs(
            [
                'bound-to' => $this->getBoundTo(),
                'mask' => $this->getMask(),
                'is'       => 'snap-mirror-input',
            ]
        );

        return parent::_render();
    }
}