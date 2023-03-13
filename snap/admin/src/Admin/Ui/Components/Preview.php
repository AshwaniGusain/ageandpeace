<?php

namespace Snap\Admin\Ui\Components;

use Illuminate\Support\Facades\Event;
use Snap\Ui\Traits\JsTrait;
use Snap\Ui\UiComponent;

class Preview extends UiComponent
{
    use JsTrait;

    protected $view = 'admin::components.preview';
    protected $scripts = [
        //'assets/snap/js/components/resource/ResourcePreview.js',
    ];

    protected $data = [
        'slug_input' => 'slug',
        'loading_url' => '',
        'prefix' => '',
    ];

    public function initialize()
    {
        //$this->setLoaderUrl();
        // Only load the script if it's visible
        Event::listen([$this->eventName('visible')], function($ui) {
            $this->addScript('assets/snap/js/components/resource/ResourcePreview.js');
        });
    }

}