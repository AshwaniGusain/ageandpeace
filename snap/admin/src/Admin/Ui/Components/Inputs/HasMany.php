<?php

namespace Snap\Admin\Ui\Components\Inputs;

use Snap\Admin\Ui\Components\Inputs\Traits\HasModule;

class HasMany extends \Snap\Form\Inputs\HasMany
{
    use HasModule;

    protected $view = 'admin::components.inputs.module-select';

}