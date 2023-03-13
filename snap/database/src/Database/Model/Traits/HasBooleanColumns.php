<?php

namespace Snap\Database\Model\Traits;

trait HasBooleanColumns {

    public function booleanColumns()
    {
        return isset(static::$booleans) ? static::$booleans : [];
    }

}
