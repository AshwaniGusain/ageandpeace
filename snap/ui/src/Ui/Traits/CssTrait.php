<?php

namespace Snap\Ui\Traits;

trait CssTrait {
    
    public function bootCssTrait()
    {
        if (!empty($this->styles)) {
            $this->addStyle($this->styles);
        }
    }
    
    public function addStyle($styles, $name = 'styles')
    {
        css($name, $styles);

        return $this;
    }
}