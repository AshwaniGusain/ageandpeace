<?php

namespace Snap\Setting;

use Snap\Form\Form;
use Snap\Setting\Models\Setting;

class SettingsManager
{
    protected $settings;

    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    public function dbValues()
    {
        if (is_null($this->settings)) {
            $this->settings = Setting::lists('value', 'key');
        }

        return $this->settings;
    }

    public function get($key, $default = null)
    {
        $settings = $this->dbValues();

        if (isset($settings[$key])) {
            return $settings[$key];
        }

        return config($key, $default);
    }

    public function form()
    {
        return $this->form;
    }

}