<?php namespace Snap\Database\Model\Traits\Observables;

use \Illuminate\Database\Eloquent\Model;
use Snap\Support\Helpers\GoogleHelper;

class LatLngObserver {

    /**
     * Register the saving event to automatically geolocate.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return boolean
     */
    public function saving(Model $model)
    {
        if ($model->hasAddressChanged()) {
            $loc = GoogleHelper::geoLocate($model->full_address);
            $model->{$model->getLatColumn()} = $loc['latitude'];
            $model->{$model->getLngColumn()} = $loc['longitude'];
        }

        return true;
    }
}
