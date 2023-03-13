<?php

namespace Snap\Decorator\Types;

use Snap\Decorator\AbstractDecorator;

class EmailDecorator extends AbstractDecorator {

    protected static $decorators = [
                            'obfuscate' => 'HtmlHelper::obfuscate',
                            'email'     => 'HtmlHelper::email',
                            'mailto'    => 'HtmlHelper::mailto',
                            ];

    public static function detect($value, $name = null)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }
}
