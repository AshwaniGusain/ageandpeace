<?php

namespace Snap\Ui\Components\Bootstrap;

use Snap\Ui\UiComponent;
use Snap\Ui\Traits\CssClassesTrait;

class Dropdown extends UiComponent {

    use CssClassesTrait;

    protected $view = 'component::bootstrap.dropdown';
    protected $data = [
        'label' => '',
        // 'class' => '',
        'btn_class' => 'secondary',
        'id' => null,
        'object:options' => 'Illuminate\Support\Collection',
        'size' => 'md',
        'container' => true,
    ];

    public function add($key, $val = null)
    {
        if (is_array($key)) {
            foreach($key as $k => $v) {
                $this->add($k, $v);
            }
        } else {
            if (is_null($val)) {
                $val = $key;
                $key = count($this->data['options']);
            }

            $this->data['options'][$key] = $val;
        }

        return $this;
    }

}