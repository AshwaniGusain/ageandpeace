<?php

namespace Snap\Decorator\Types;

use Illuminate\Support\Str;
use Snap\Decorator\AbstractDecorator;

class StringDecorator extends AbstractDecorator {

    protected static $decorators = [
                            // 'markdown'    => '', // markdown
                            'format'       => 'Snap\Support\Helpers\TextHelper::htmlify', // auto typography
                            'autolink'     => 'Snap\Support\Helpers\UrlHelper::autoLink', // auto link
                            'charlimit'    => 'Snap\Support\Helpers\TextHelper::characterLimiter',
                            'wordlimit'    => 'Snap\Support\Helpers\TextHelper::wordLimiter',
                            'stripped'     => 'strip_tags', 
                            'wrap'         => 'wordwrap',
                            'entities'     => 'htmlentities',
                            'specialchars' => 'htmlspecialchars',
                            'title'        => 'Illuminate\Support\Str::title',
                            'camelize'     => 'Illuminate\Support\Str::camel',
                            'upper'        => 'Illuminate\Support\Str::upper',
                            'lower'        => 'Illuminate\Support\Str::lower',
                            'limit'        => 'Illuminate\Support\Str::limit',
                            'studly'       => 'Illuminate\Support\Str::studly',
                            'snake'        => 'Illuminate\Support\Str::snake',
                            'slug'         => 'Illuminate\Support\Str::slug',
                            'length'       => 'mb_strlen',
                            'ucfirst',
                            'trans',
                            ];

    public static function detect($value, $name = null)
    {
        return (is_string($value) && !is_numeric($value));
    }


}