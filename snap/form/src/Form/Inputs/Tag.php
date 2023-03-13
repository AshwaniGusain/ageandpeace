<?php

namespace Snap\Form\Inputs;

class Tag extends Select2
{
    protected $vue = 'snap-tag-input';

    protected $scripts = [
        'assets/snap/vendor/select2/js/select2.min.js',
        'assets/snap/js/components/form/TagInput.js',
    ];

    protected $data = [
        'attrs' => [':config' => null]
    ];

}