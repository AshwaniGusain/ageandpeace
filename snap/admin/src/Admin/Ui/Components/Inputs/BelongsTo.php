<?php

namespace Snap\Admin\Ui\Components\Inputs;

use Snap\Admin\Ui\Components\Inputs\Traits\HasModule;

class BelongsTo extends \Snap\Form\Inputs\BelongsTo
{
    use HasModule;

    protected $view = 'admin::components.inputs.module-select';

}