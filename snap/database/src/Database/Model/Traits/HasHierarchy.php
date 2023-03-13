<?php

namespace Snap\Database\Model\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasHierarchy {

    //@TODO ... check max depth on validation
    /**
     * Get the name of the parent column.
     *
     * @return string
     */
    public function getParentColumn()
    {
        return defined('static::PARENT_COLUMN') ? static::PARENT_COLUMN : 'parent_id';
    }

    public function hasParent()
    {
        return !empty($this->parent_id);
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(get_class($this), $this->getParentColumn());
    }

    public function getParentColumnValue()
    {
        return $this->{$this->getParentColumn()};
    }
}
