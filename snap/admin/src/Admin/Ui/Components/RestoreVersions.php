<?php

namespace Snap\Admin\Ui\Components;

use Snap\Ui\Traits\JsTrait;
use Snap\Ui\UiComponent;

class RestoreVersions extends UiComponent
{
    use JsTrait;

    protected $view = 'admin::components.restore-versions';
    protected $scripts = [
        'assets/snap/js/components/resource/RestoreVersions.js',
    ];
    protected $data = [
        'resource' => null,
        'versions' => [],
    ];

    public function initialize()
    {

    }

    public function setResource($resource)
    {
        $this->data['resource'] = $resource;

        if ($resource) {
            $this->setVersions($resource->versionsList());
        }

        return $this;
    }

}