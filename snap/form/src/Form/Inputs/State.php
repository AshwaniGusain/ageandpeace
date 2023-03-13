<?php

namespace Snap\Form\Inputs;

use Snap\Ui\Traits\AttrsTrait;
use Snap\Form\Contracts\OptionableInterface;
use Snap\Form\Fields\Traits\HasOptions;
use Illuminate\Contracts\Support\Arrayable;

class State extends Select
{

    public function initialize()
    {
        $this->setOptions(config('snap.states'));
    }

}