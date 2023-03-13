<?php

namespace Snap\Admin\Ui\Module\Resource;

use Illuminate\Http\Request;
use Snap\Ui\Traits\AjaxRendererTrait;
use Snap\Ui\Traits\JsTrait;

class ListingPage extends IndexPage
{
    use JsTrait;
    use AjaxRendererTrait;

    protected $view = 'admin::module.resource.listing';

    protected $scripts = [
        'assets/snap/js/components/resource/ResourceIndex.js',
    ];

    protected $data = [
        ':listing' => '\Snap\Admin\Ui\Components\HierarchicalListing[module]',
        ':filters'      => '\Snap\Admin\Ui\Components\Filters[module]',
        ':scopes'       => '\Snap\Admin\Ui\Components\Scopes[module]',
    ];

    public function initialize(Request $request)
    {
        parent::initialize($request);

        $this->buttons->setActive('listing');
    }

    public function _renderAjax()
    {
        return $this->listing->render();
    }
}