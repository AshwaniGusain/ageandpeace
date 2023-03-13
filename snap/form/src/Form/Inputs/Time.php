<?php

namespace Snap\Form\Inputs;

use Snap\Ui\Traits\VueTrait;

class Time extends BaseInput
{
    use VueTrait;
    protected $vue = 'snap-time-input';
    protected $scripts = [
        'assets/snap/js/components/form/TimeInput.js',
        //'assets/snap/vendor/moment/moment.min.js',
    ];

    protected $view = 'form::inputs.time';

    protected $data = [
        'display_seconds' => 'false',
        'placeholder_hr' => 'hh',
        'placeholder_min' => 'mm',
        'placeholder_sec' => 'ss',
    ];

    public function setDisplaySec(bool $display)
    {
        if ($display === false) {
            $this->data['display_seconds'] = 'false';
        } else {
            $this->data['display_seconds'] = 'true';
        }
    }

    protected function _render()
    {
        $this->data['id'] = $this->id;

        return parent::_render();
    }
}
