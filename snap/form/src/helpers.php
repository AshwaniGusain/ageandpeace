<?php

if (!function_exists('form')) {

    /**
     * @param array $elements
     * @return mixed
     */
    function form($elements = [])
    {
        return \Form::make($elements);
    }
}

if (!function_exists('form_element')) {

    /**
     * @param $name
     * @param string $type
     * @param array $props
     * @return mixed
     */
    function form_element($name, $type = 'text', $props = [])
    {
        return \Form::element($name, $type, $props);
    }
}

if (!function_exists('form_model')) {

    /**
     * @param $model
     * @param array $options
     * @return mixed
     */
    function form_model($model, $options = [])
    {
        return \Form::model($model, $options);
    }
}