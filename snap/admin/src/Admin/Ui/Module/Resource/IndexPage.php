<?php

namespace Snap\Admin\Ui\Module\Resource;

use Snap\Admin\Ui\BasePage;
use Snap\Ui\Traits\JsTrait;
use Illuminate\Http\Request;

class IndexPage extends BasePage
{
    use JsTrait;

    protected $scripts = [
        'assets/snap/js/components/resource/ResourceIndex.js',
    ];
    protected $data = [
        ':heading' => '\Snap\Admin\Ui\Components\Heading[module]',
        ':buttons' => '\Snap\Admin\Ui\Components\IndexButtonBar[module]',
        ':search' => '\Snap\Admin\Ui\Components\Search[module]',
        ':dropdown' => '\Snap\Admin\Ui\Components\IndexDropdown[module]',
    ];
    protected $request;

    public function initialize(Request $request)
    {
        $this->request = $request;
        $this->heading
            ->setTitle($this->module->pluralName())
            ->setCreate($this->module->hasTrait('form'))
            ;

        $this->setPageTitle($this->module->pluralName());
    }
}