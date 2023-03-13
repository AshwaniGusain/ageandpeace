<?php

namespace Snap\Ui\Components;

use Snap\Ui\Traits\CssClassesTrait;
use Snap\Ui\UiComponent;

class ElementGroup extends UiComponent
{
    use CssClassesTrait;

    protected $view = 'component::element-group';

    protected $data = [
        'object:items' => 'Illuminate\Support\Collection', // collection
    ];

    public function add($val = null)
    {
        if (is_iterable($val)) {
            foreach ($val as $v) {
                $this->add($v);
            }
        } else {
            $this->data['items'][] = $val;
        }

        return $this;
    }
}