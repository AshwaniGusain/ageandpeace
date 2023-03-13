<?php

namespace Snap\Database\Model\Traits;

use Illuminate\Validation\Rule;
use Snap\Database\Model\Traits\Observables\HasUniqueColumnsObserver;

trait HasUniqueColumns {

    public static function bootHasUniqueColumns()
    {
        static::observe(new HasUniqueColumnsObserver());
    }

    public function getUniqueRule($resource)
    {
        return Rule::unique($this->getTable())->ignore($this->id);
    }

    public function uniqueColumns()
    {
        return static::$unique ?? [];
    }

    public function setUniqueColumnExcludeValue($column)
    {
        $this->uniqueColumnExcludeValue = $column;

        return $this;
    }

    public function getUniqueColumnExcludeValue()
    {
        if (property_exists($this, 'uniqueColumnExcludeValue')) {
            return $this->uniqueColumnExcludeValue;
        } else {
            return 'id';
        }
    }
}
