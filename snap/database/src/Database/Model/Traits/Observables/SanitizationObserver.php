<?php namespace Snap\Database\Model\Traits\Observables;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

class SanitizationObserver
{
    /**
     * Register the sanitation event for saving the model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return boolean
     */
    public function saving(Model $model)
    {
        return $this->performSanitization($model, 'saving');
    }

    /**
     * Register the sanitation event for restoring the model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return boolean
     */
    public function restoring(Model $model)
    {
        return $this->performSanitization($model, 'restoring');
    }

    /**
     * Perform sanitization.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $event
     * @return null
     */
    protected function performSanitization(Model $model, $event)
    {
        // If the model has sanitization enabled, perform it.
        if ($model->hasSanitization()) {
            // Fire the namespaced sanitization event and prevent validation
            // if it returns a value.
            if ($this->fireSanitizingEvent($model, $event)) {
                return;
            }

            // An environment variable will allow us to globally disable for things like tests.
            if (env('MODEL_SANITIZE', true)) {
                $model->sanitize($model->getAttributes());
            }

            // Fire the sanitization event.
            $this->fireSanitizedEvent($model);
        } else {
            $this->fireSanitizedEvent($model);
        }
    }

    /**
     * Fire the namespaced sanitizing event.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $event
     * @return mixed
     */
    protected function fireSanitizingEvent(Model $model, $event)
    {
        return Event::until("eloquent.sanitizing: ".get_class($model), [$model, $event]);
    }

    /**
     * Fire the namespaced post-sanitizing event.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param string $status
     * @return void
     */
    protected function fireSanitizedEvent(Model $model)
    {
        Event::dispatch("eloquent.sanitized: ".get_class($model), [$model]);
    }
}
