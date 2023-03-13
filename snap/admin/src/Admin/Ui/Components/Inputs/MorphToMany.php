<?php

namespace Snap\Admin\Ui\Components\Inputs;

use Snap\Admin\Ui\Components\Inputs\Traits\HasModule;

class MorphToMany extends \Snap\Form\Inputs\MorphToMany
{
    use HasModule;

    protected $view = 'admin::components.inputs.module-select';
}