<?php

namespace Snap\Decorator\Types;

use Snap\Decorator\AbstractDecorator;

class NumberDecorator extends AbstractDecorator
{
    protected static $decorators = [
        'byte'      => 'Snap\Support\Helpers\NumberHelper::byte',
        'currency'  => 'Snap\Support\Helpers\NumberHelper::currency',
        'ordinal'   => 'Snap\Support\Helpers\NumberHelper::ordinal',
        'percent'   => 'Snap\Support\Helpers\NumberHelper::percent',
        'formatter' => 'Snap\Support\Helpers\NumberHelper::formatter',
        'format'    => 'number_format',
        'round',
        'floor',
        'ceil',
    ];

    public static function detect($value, $name = null)
    {
        return (is_numeric($value));
    }
}