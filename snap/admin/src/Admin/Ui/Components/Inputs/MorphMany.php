<?php

namespace Snap\Admin\Ui\Components\Inputs;
use Snap\Admin\Ui\Components\Inputs\Traits\HasModule;

class MorphMany extends \Snap\Form\Inputs\MorphMany
{
    use HasModule;

    protected $view = 'admin::components.inputs.module-select';

}