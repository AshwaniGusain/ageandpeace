<?php

namespace Snap\Decorator\Types;

use Snap\Support\Helpers\UrlHelper;
use Snap\Decorator\AbstractDecorator;

class LinkDecorator extends AbstractDecorator {

    protected static $decorators = [
                            'url',
                            'prep' => 'Snap\Support\Helper\UrlHelper::prep',
                            'target' => 'Snap\Support\Helper\UrlHelper::target',
                            'html' => 'Snap\Support\Helper\UrlHelper::anchor',
                            ];

    public static function detect($value, $name = null)
    {
        return (UrlHelper::isAbsoluteUrl($value) || strpos($value, '/') === 0);
    }
}
