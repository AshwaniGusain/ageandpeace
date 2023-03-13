<?php

namespace Snap\Database\Model\Traits\Observables;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Eloquent\Model;
use Snap\Database\Model\Exceptions\ValidationException;

// Original is \Watson\Validating\ValidatingObserver
class ValidationObserver {

    /**
     * Register the validation event for saving the model. Saving validation
     * should only occur if creating and updating validation does not.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return boolean
     */
    public function saving(Model $model)
    {
        return $this->validate($model, 'saving');
    }

    /**
     * Register the validation event for restoring the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return boolean
     */
    public function restoring(Model $model)
    {
        return $this->validate($model, 'restoring');
    }

    /**
     * Perform validation with the specified ruleset.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $event
     * @return boolean|null
     */
    protected function validate(Model $model, $event)
    {
        // If the model has validating enabled, perform it.
        if ($model->shouldValidate()) {

            // Fire the namespaced validating event and prevent validation
            // if it returns a value.
            if ($this->fireValidatingEvent($model, $event) !== null) return null;

            // An environment variable will allow us to globally disable for things like tests.
            $validated = (env('MODEL_VALIDATE', true)) ? $model->validate() : true;

            if ($validated === false) {

                // Fire the validating failed event.
                $this->fireValidatedEvent($model, 'failed');

                if ($model->getThrowValidationExceptions()) {
                    $model->throwValidationException();
                }

                return false;
            }

            // Fire the validating.passed event.
            $this->fireValidatedEvent($model, 'passed');

            return true;
        }
        else
        {
            $this->fireValidatedEvent($model, 'skipped');
        }
    }

    /**
     * Fire the namespaced validating event.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $event
     * @return mixed
     */
    protected function fireValidatingEvent(Model $model, $event)
    {
        return Event::until("eloquent.validating: ".get_class($model), [$model, $event]);
    }

    /**
     * Fire the namespaced post-validation event.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  string $status
     * @return void
     */
    protected function fireValidatedEvent(Model $model, $status)
    {
        Event::dispatch("eloquent.validated: ".get_class($model), [$model, $status]);
    }

}
