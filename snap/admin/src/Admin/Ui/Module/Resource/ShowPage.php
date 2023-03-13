<?php

namespace Snap\Admin\Ui\Module\Resource;

use Snap\Admin\Ui\BasePage;

class ShowPage extends BasePage
{
    protected $view = 'admin::module.resource.show';
    protected $data = [
        'resource'       => null, // <!-- Order matters here because it's needed for the form object below.
        ':heading'       => '\Snap\Admin\Ui\Components\Heading[module]',
        ':alerts'        => '\Snap\Admin\Ui\Components\AlertMessages',
        ':form'          => '\Snap\Admin\Ui\Components\Form[module,resource]',
        ':related_panel' => '\Snap\Admin\Ui\Components\RelatedPanel[module,resource]',
    ];

    public function initialize()
    {
        $this->heading
            ->setTitle($this->resource->display_name)
            ->setCreate(true)
            ->setBack($this->module->url())
        ;

        $this->form->displayOnly(true);
        $this->related_panel->visible(false);

    }


}