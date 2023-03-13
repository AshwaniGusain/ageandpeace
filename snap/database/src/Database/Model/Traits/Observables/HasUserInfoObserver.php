<?php namespace Snap\Database\Model\Traits\Observables;

use \Illuminate\Database\Eloquent\Model;
use Auth;

class HasUserInfoObserver {

    /**
     * Register the creating event to automatically attach a user's ID when saved to the last updated.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return boolean
     */
    public function creating(Model $model)
    {
        if (Auth::user()) {
            $model->{$model->getCreatedByColumn()} = Auth::user()->id;
        }

        return true;
    }

    /**
     * Register the saving event to automatically attach a user's ID when saved to the last updated.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return boolean
     */
    public function saving(Model $model)
    {
        if (Auth::user()) {
            $model->{$model->getLastUpdatedByColumn()} = Auth::user()->id;
        }

        return true;
    }
}
