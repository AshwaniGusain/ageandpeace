<?php

namespace Snap\Admin\Ui\Module\Resource;

class ShowInlinePage extends FormInlinePage
{
    protected $view = 'admin::module.resource.show-inline';
    protected $data = [
        'resource'       => null, // <!-- Order matters here because it's needed for the form object below.
        ':heading'       => '\Snap\Admin\Ui\Components\Heading[module]',
        ':alerts'        => '\Snap\Admin\Ui\Components\AlertMessages',
        ':form'          => '\Snap\Admin\Ui\Components\Form[module,resource]',
    ];

    public function initialize()
    {
        parent::initialize();
        $this->heading
            ->setTitle($this->resource->display_name)
            ->setCreate(true)
            ->setBack($this->module->url())
        ;

        $this->form->displayOnly(true);

    }


}