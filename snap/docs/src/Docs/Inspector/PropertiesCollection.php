<?php

namespace Snap\Docs\Inspector;

use Illuminate\Support\Collection;

class PropertiesCollection extends Collection
{
    public function asString()
    {
        $p = [];

        foreach ($this->items as $name => $param) {
            $str = '';
            if ($param->hasComment() && $param->comment->tag('var')) {
                $str .= (string) '('.$param->comment->tag('var').')';
            }

            $p[] = $str;
        }

        return implode(', ', $p);
    }

}