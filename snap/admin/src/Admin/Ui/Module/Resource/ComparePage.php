<?php

namespace Snap\Admin\Ui\Module\Resource;

use Snap\Admin\Ui\BasePage;
use Snap\Ui\Traits\JsTrait;

class ComparePage extends BasePage
{
    use JsTrait;

    protected $scripts = [
        'assets/snap/js/components/resource/ResourceCompare.js',
    ];

    protected $view = 'admin::module.resource.compare';

    protected $data = [
        ':heading'     => '\Snap\Admin\Ui\Components\Heading[module]',
        ':alerts'      => '\Snap\Admin\Ui\Components\AlertMessages',
        'resource'     => null,
        'version'      => null,
        'restore_data' => [],
    ];

    public function initialize()
    {
        $this->heading->setTitle($this->module->pluralName().': '.trans('admin::resources.compare'))->setCreate(false)
                      ->setBackUrl('edit', [$this->resource->getKey()])
        ;

        $this->restore_data = $this->resource->getRestoreData($this->version->version);

        $this->setPageTitle([$this->resource->display_name, $this->module->pluralName(), trans('admin::resources.compare')]);
    }

    public function setVersion($version)
    {
        if (is_numeric($version)) {
            $version = $this->resource->version($version);
        }

        $this->data['version'] = $version;

        return $this;
    }
}