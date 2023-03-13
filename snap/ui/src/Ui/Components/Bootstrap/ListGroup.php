<?php

namespace Snap\Ui\Components\Bootstrap;

use Snap\Ui\Traits\CssClassesTrait;
use Snap\Ui\UiComponent;

class ListGroup extends UiComponent
{
    use CssClassesTrait;

    protected $view = 'component::bootstrap.list-group';

    protected $data = [
        'tag'          => 'ul',
        'item_tag'     => 'li',
        'object:items' => 'Illuminate\Support\Collection', // collection
        'active'       => null,
        'flush'        => false,
    ];

    public function add($val = null, $index = null)
    {
        if (is_iterable($val)) {
            foreach ($val as $v) {
                $this->add($v);
            }
        } else {
            if (!is_null($index)) {
                $this->data['items'][$index] = $val;
            } else {
                $this->data['items'][] = $val;
            }
        }

        $this->data['items'] = $this->data['items']->sortKeys();

        return $this;
    }
}