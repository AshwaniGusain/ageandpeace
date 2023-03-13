<?php

namespace Snap\Admin\Ui\Components;

use Snap\Ui\UiComponent;

class Heading extends UiComponent
{
    protected $view = 'admin::components.heading';

    protected $data = [
        'module'         => null,
        'title'          => '',
        'back'           => null,
        'create'         => false,
        'modal'          => false,
        'icon'           => null,
        ':preview_button' => PreviewButton::class,
    ];

    public function initialize()
    {
        if (is_null($this->icon) && $this->module) {
            $this->setIcon($this->module->icon());
        }

        $this->preview_button->visible(false);
    }

    // public function setBackUrl($url, $params = [])
    // {
    //     // if ( ! is_http_path($url))
    //     // {
    //     //     $url =  $this->module->adminUrl($url, $params);
    //     // }

    //     $this->data['back_url'] = $url;

    //     return $this;
    // }

}