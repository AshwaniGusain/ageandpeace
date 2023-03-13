<?php

namespace Snap\Form\Inputs;

class DateTime extends Date
{
    //protected $inputType = 'datetime-local'; // Creates warning in console with incorrect format
    protected $inputType = 'text';
    protected $vue = 'snap-datetime-input';

    protected $scripts = [
        'assets/snap/js/components/form/DateTimeInput.js',
        'assets/snap/vendor/moment/moment.min.js',
        'assets/snap/vendor/bootstrap/daterangepicker.js',
    ];

    protected $format = 'm/d/Y h:i a';
}