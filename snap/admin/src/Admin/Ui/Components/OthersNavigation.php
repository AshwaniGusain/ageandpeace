<?php

namespace Snap\Admin\Ui\Components;

use Snap\Ui\Traits\JsTrait;
use Snap\Ui\UiComponent;

class OthersNavigation extends UiComponent
{
    use JsTrait;

    protected $view = 'admin::components.others-navigation';
    protected $scripts = [
        'assets/snap/js/components/resource/OthersNavigation.js',
    ];
    protected $data = [
        'resource' => null,
        'others' => [],
        'current' => null,
    ];

    public function initialize()
    {

    }

    //public function setResource($resource)
    //{
    //    $this->data['resource'] = $resource;
    //
    //    if ($resource) {
    //        $this->setVersions($resource->versionsList());
    //    }
    //
    //    return $this;
    //}

}