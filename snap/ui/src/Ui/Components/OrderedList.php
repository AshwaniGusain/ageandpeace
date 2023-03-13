<?php

namespace Snap\Ui\Components;

use Snap\Ui\Traits\CssClassesTrait;
use Snap\Ui\UiComponent;

class OrderedList extends UiComponent
{
    use CssClassesTrait;

    protected $view = 'component::ordered-list';

    protected $data = [
        'object:items' => 'Illuminate\Support\Collection', // collection
        'item_class'   => '',
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