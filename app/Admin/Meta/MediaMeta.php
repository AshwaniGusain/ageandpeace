<?php

namespace App\Admin\Meta;

use Snap\Form\Inputs\Text;
use Snap\Meta\MetaForm;

class MediaMeta extends MetaForm
{
    public function inputs()
    {
        return [
            Text::make('alt'),
            Text::make('caption'),
        ];
    }
}