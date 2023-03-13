<?php

namespace Snap\Decorator\Types;


use Snap\Support\Helpers\UtilHelper;
use Snap\Decorator\AbstractDecorator;

class BooleanDecorator extends AbstractDecorator {

    protected static $decorators = [
                            'istrue'       => 'Snap\Support\Helper\UtilHelper::isTrue',
                            'isfalse'       => 'Snap\Support\Helper\UtilHelper::isFalse',
                            ];

    public static function detect($value, $name = null)
    {
        return (is_bool($value) || UtilHelper::isTrue($value) == true || UtilHelper::isFalse($value) == false);
    }

}