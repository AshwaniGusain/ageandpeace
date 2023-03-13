<?php

namespace Snap\Ui\Traits;

trait JsTrait {
    
    public function bootJsTrait()
    {
        if (!empty($this->scripts)) {
            $this->addScript($this->scripts);
        }
    }

    public function addScript($scripts, $name = 'scripts')
    {
        js($name, $scripts);

        return $this;
    }

}