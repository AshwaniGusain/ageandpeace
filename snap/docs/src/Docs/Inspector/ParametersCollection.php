<?php

namespace Snap\Docs\Inspector;

use Illuminate\Support\Collection;
use Snap\Support\Contracts\ToString;

class ParametersCollection extends Collection implements ToString
{
    public function asString()
    {
        $p = [];

        foreach ($this->items as $name => $param) {
            $p[] = $param->asString();
        }

        return implode(', ', $p);
    }

    public function __toString()
    {
        return $this->asString();
    }
}