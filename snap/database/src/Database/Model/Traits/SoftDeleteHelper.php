<?php

namespace Snap\Database\Model\Traits;

trait SoftDeleteHelper {

    /**
     * Determines if the model is using soft deletes or not.
     *
     * @return bool
     */
    public function isSoftDelete()
    {
        return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_deep($this));
    }
}
