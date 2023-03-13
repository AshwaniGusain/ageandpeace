<?php

namespace Snap\Database\Model\Traits\Observables;

use Illuminate\Database\Eloquent\Model;

class ShouldClearCacheObserver {

    /**
     * Clears the cache after save.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return boolean
     */
    public function saved(Model $model)
    {
        return $model->clearCache();
    }

    /**
     * Clears the cache on deleting.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return boolean
     */
    public function deleting(Model $model)
    {
        return $model->clearCache();
    }

}
