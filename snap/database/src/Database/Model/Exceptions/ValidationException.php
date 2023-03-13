<?php

namespace Snap\Database\Model\Exceptions;

use \RuntimeException;
use \Illuminate\Support\MessageBag;
use \Illuminate\Database\Eloquent\Model;
use \Illuminate\Contracts\Support\MessageProvider;

// Original is \Watson\Validating\ValidationException
class ValidationException extends RuntimeException implements MessageProvider {

    /**
     * The model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The validation errors.
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $errors;

    /**
     * Get the validation errors.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Get the messages for the instance.
     *
     * @return \Illuminate\Support\MessageBag
     */
    public function getMessageBag()
    {
        return $this->getErrors();
    }

    /**
     * Set the validation errors.
     *
     * @param  \Illuminate\Support\MessageBag $errors
     * @return void
     */
    public function setErrors(MessageBag $errors)
    {
        $this->errors = $errors;
    }

    /**
     * Get the model.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

}
