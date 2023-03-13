<?php

namespace Snap\Admin\Ui\Components;

use Session;
use Snap\Ui\UiComponent;

class AlertMessages extends UiComponent
{
    protected $view = 'admin::components.alert-messages';

    protected $data = [
        ':alerts' => '\Snap\Ui\Components\Bootstrap\Alerts',
        'display_time' => 0,
    ];

    public function initialize()
    {
        if (Session::get('errors')) {
            $this->alerts->add(trans('admin::resources.save_error'), 'danger');
        } else if (Session::get('success')) {
            $this->setDisplayTime(2000);
        }
    }

    public function __call($method, $args)
    {
        if (method_exists($this->alerts, $method)) {
            return call_user_func_array([$this->alerts, $method], $args);
        }
        parent::__call($method, $args);
    }
}