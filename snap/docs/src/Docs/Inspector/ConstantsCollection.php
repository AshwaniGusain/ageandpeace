<?php

namespace Snap\Docs\Inspector;

use Illuminate\Support\Collection;
use Snap\Support\Contracts\ToString;

class ConstantsCollection extends Collection implements ToString
{
    public function asString()
    {
        $c = [];

        foreach ($this->items as $name => $constant) {
            $c[] = $constant->asString();
        }

        return implode("\n", $c);
    }

    public function __toString()
    {
        return $this->asString();
    }
}