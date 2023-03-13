<?php

\Illuminate\Database\Eloquent\Builder::macro('lists', function ($name = null, $key = null) {

    $model = $this->getModel();
    if (empty($key)) {
        $key = $model->getKeyName();
    }

    if (empty($name)) {
        $name = $key;
    }

    $options = $this->get()->pluck($name, $key);

    return $options;
});