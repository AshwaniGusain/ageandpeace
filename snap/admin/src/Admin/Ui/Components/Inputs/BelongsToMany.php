<?php

namespace Snap\Admin\Ui\Components\Inputs;

use Snap\Admin\Ui\Components\Inputs\Traits\HasModule;

class BelongsToMany extends \Snap\Form\Inputs\BelongsToMany
{
    use HasModule;

    protected $view = 'admin::components.inputs.module-select';
}